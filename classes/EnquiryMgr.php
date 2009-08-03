<?php
/**
 * Seagull PHP Framework
 *
 * LICENSE
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *
 * o Redistributions of source code must retain the above copyright
 *   notice, this list of conditions and the following disclaimer.
 * o Redistributions in binary form must reproduce the above copyright
 *   notice, this list of conditions and the following disclaimer in the
 *   documentation and/or other materials provided with the distribution.
 * o The names of the authors may not be used to endorse or promote
 *   products derived from this software without specific prior written
 *   permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * PHP version 5
 *
 * @category  Modules
 * @package   Enquiry
 * @author    Yevgeniy A. Viktorov <wik@osmonitoring.com>
 * @author    Andrew A. Viktorov <nasa51@osmonitoring.com>
 * @copyright 2009 Demian Turner
 * @license   http://opensource.org/licenses/bsd-license.php BSD License
 * @version   SVN: $Id$
 * @link      http://github.com/yviktorov/seagull_module_enquiry/tree/master
 */

require_once SGL_MOD_DIR . '/cms/lib/Content.php';

/**
 * To allow users to submit specific enquiries
 *
 * @category Modules
 * @package  Enquiry
 * @author   Yevgeniy A. Viktorov <wik@osmonitoring.com>
 * @author   Andrew A. Viktorov <nasa51@osmonitoring.com>
 * @license  http://opensource.org/licenses/bsd-license.php BSD License
 * @link     http://github.com/yviktorov/seagull_module_enquiry/tree/master
 */
class EnquiryMgr extends SGL_Manager
{
    /**
     * Constructor.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        // override default prefix for the methods names
        $this->_methodNamePrefix = 'execute';

        // set default title
        $this->pageTitle = 'Enquiry';
        $this->template  = 'form.html';

        $this->_aActionsMapping =  array(
            'form'   => array('Form'),
        );
    }

    /**
     * Validate content type
     *
     * Only existent and allowed content types will pass
     *
     * @param SGL_Request  $req   SGL_Request object received from user agent
     * @param SGL_Registry $input SGL_Registry for storing data
     *
     * @access public
     * @return void
     */
    public function validate($req, $input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $this->validated    = true;
        $input->pageTitle   = $this->pageTitle;
        $input->template    = $this->template;
        $input->action      = 'form';
        $input->submitted   = $req->get('submitted');
        $input->contentType = ($req->get('type'))?$req->get('type'):'';

        $allowed_types = explode(',', $this->conf['EnquiryMgr']['allowedTypes']);
        if (in_array($input->contentType, $allowed_types)) {
            $input->oContentType = SGL_Content::getByType($input->contentType);
            if (PEAR::isError($input->oContentType)) {
                SGL::raiseError(
                    $input->contentType . ' content type does not exist',
                    SGL_ERROR_RESOURCENOTFOUND
                );
            }
        } else {
            SGL::raiseError(
                $input->contentType . ' content type does not allowed',
                SGL_ERROR_RESOURCENOTFOUND
            );
        }
    }

    /**
     * Build specific form using cms api and process it using observers
     *
     * Unified form.html template used by default, but you can specify
     * custom template on content type basis,
     * in modules:
     *   enquiry/templates/contentTypes/<contentType>.html
     * or in your themes:
     *   <your_theme>/enquiry/contentTypes/<contentType>.html
     *
     * @param SGL_Registry $input  Input object received from validate()
     * @param SGL_Output   $output Processed result
     *
     * @access public
     * @return void
     */
    public function executeForm($input, $output)
    {
        foreach ($input->oContentType->aAttribs as $key => $element) {
            $input->oContentType->aAttribs[$key]
                = SGL_Attribute::getById($element->id);
        }

        $output->template = 'form.html';

        include_once SGL_LIB_DIR . '/SGL/WizardController.php';
        include_once 'PageForm.php';
        include_once 'PageConfirm.php';

        // Instantiate the Controller
        $controller =& new SGL_WizardController('clientWizard');

        // Add pages to Controller
        $controller->addPage(
            new PageForm(
                'page_form',
                $input->oContentType,
                'post',
                '',
                ''
            )
        );
        $controller->addPage(new PageConfirm('page_confirm'));

        // Add actions to controller
        $controller->addAction('display', new SGL_WizardControllerDisplay());
        $controller->addAction('jump', new SGL_WizardControllerJump());
        $controller->addAction('process', new SGL_WizardControllerProcess());

        // Process the request
        $controller->run();

        // Get the current page name
        list($pageName, $actionName) = $controller->getActionName();
        $page = $controller->getPage($pageName);

        // Set page output vars
        if ($pageName == 'page_confirm') {
            $output->wizardData = $page->wizardData;
        }

        if(!isset($page->wizardOutput)) {
            // Do somsing - data is submited
            $output->template = 'thank_you.html';
        } else {
            $output->wizardOutput = $page->wizardOutput;
        }
        SGL::logMessage(null, PEAR_LOG_DEBUG);
    }
}

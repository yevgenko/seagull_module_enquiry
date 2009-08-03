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
 * @copyright 2009 Demian Turner
 * @license   http://opensource.org/licenses/bsd-license.php BSD License
 * @version   SVN: $Id$
 * @link      http://github.com/yviktorov/seagull_module_enquiry/tree/master
 */

/**
 * Form Page of enquiry module
 *
 * @category Modules
 * @package  Enquiry
 * @author   Andry A. Viktorov <nasa51@osmonitoring.com>
 * @license  http://opensource.org/licenses/bsd-license.php BSD License
 * @link     http://github.com/yviktorov/seagull_module_enquiry/tree/master
 */
class PageForm extends HTML_QuickForm_Page
{
    /**
    * Class constructor
    *
    * @param string $formName     name of generated form
    * @param string $content_type cms object assigned to form
    * @param string $method       posting method
    * @param string $action       action url
    * @param string $target       url to execute form
    * @param string $attributes   attributes for form
    *
    * @access public
    */
    function PageForm($formName, $content_type, $method = 'post', $action = '', $target = '', $attributes = null)
    {
        $this->HTML_QuickForm_Page($formName, $method, $target, $attributes);
        $this->content = $content_type;
        if ($action != '') {
            $this->setAttribute('action', $action);
        }

    }

    /**
     * Build specific form using cms api and process it using observers
     *
     * @access public
     * @return void
     */ 
    function buildForm()
    {
        $this->_formBuilt = true;

        // Add some elements to the form
        $this->addElement('header', null, SGL_String::translate('Contact Details'));
        $this->addElement(
            'text',
            'name',
            SGL_String::translate('Name'),
            array('size' => 50, 'maxlength' => 255)
        );
        $this->addElement(
            'text',
            'email',
            SGL_String::translate('Email'),
            array('size' => 50, 'maxlength' => 255)
        );

        foreach ($this->content->aAttribs as $element) {
            switch ($element->typeName) {
            case 'Large text':
                    $this->addElement(
                        'textarea',
                        $element->name,
                        $element->alias,
                        array(
                            'cols' => 50,
                            'rows' => '7'
                        )
                    );
                break;
            case 'Rich text':
                    $this->addElement(
                        'textarea',
                        $element->name,
                        $element->alias,
                        array(
                            'cols' => 50,
                            'rows' => '7'
                        )
                    );
                break;
            case 'Combo':
                    $this->addElement(
                        'select',
                        $element->name,
                        $element->alias,
                        $element->getParams()
                    );
                break;
            case 'Checkbox':
                    $checkboxes = array();
                foreach ($element->getParams() as $value => $label) {
                    $checkboxes[] = $this->createElement(
                        'checkbox',
                        '',
                        null,
                        $label
                    );
                }
                    $this->addGroup(
                        $checkboxes,
                        $element->name,
                        $element->alias,
                        ' '
                    );
                break;
            case 'Radio':
                    $radio = array();
                foreach ($element->getParams() as $value => $label) {
                    $radio[] = $this->createElement(
                        'radio',
                        null,
                        null,
                        $label,
                        $value
                    );
                }
                    $this->addGroup(
                        $radio,
                        $element->name,
                        $element->alias,
                        ' '
                    );
                break;
            case 'File':
                    $this->addElement('file', $element->name, $element->alias);
                break;
            case 'Date':
                    $this->addElement('date', $element->name, $element->alias);
                break;
            default:
                    $this->addElement('text', $element->name, $element->alias);
                break;
            }
        }

        $resetnext[] =& $this->createElement(
            'submit',
            $this->getButtonName('next'),
            SGL_String::translate('Submit'),
            array('id'=>'submit')
        );
        $resetnext[] =& $this->createElement(
            'reset',
            $this->getButtonName('reset'),
            SGL_String::translate('Reset'),
            array('id'=>'reset')
        );
        $this->addGroup($resetnext, 'button', '', '&nbsp;', false);
        $this->addRule(
            'name',
            SGL_String::translate('Please enter your name'),
            'required'
        );
        $this->addRule(
            'email',
            SGL_String::translate('Please enter your email'),
            'required'
        );
        $this->setDefaultAction('next');
    }
}
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
require_once SGL_LIB_DIR . '/SGL/Emailer2.php';
/**
 * Enquiry observer to email data to administrator
 *
 * @category Modules
 * @package  Enquiry
 * @author   Yevgeniy A. Viktorov <wik@osmonitoring.com>
 * @license  http://opensource.org/licenses/bsd-license.php BSD License
 * @link     http://github.com/yviktorov/seagull_module_enquiry/tree/master
 */
class EmailEnquiry extends SGL_Observer
{
    /**
     * update
     *
     * @param SGL_Observable $observable Observable object
     *
     * @access public
     * @return void
     */
    public function update($observable)
    {
        // email enquiry
        $c = &SGL_Config::singleton();

        // delivery options
        $aDeliveryOpts['toEmail']  = $c->aProps['email']['info'];
        $aDeliveryOpts['toRealName'] = 'Info contact';

        $aDeliveryOpts['fromEmail'] = $observable->output->wizardData['email'];
        $aDeliveryOpts['fromRealName']  = $observable->output->wizardData['name'];

        $aDeliveryOpts['subject']  = 'Contact Enquiry from ' . $c->aProps['site']['name'];

        // template vars
        if (isset($observable->output->wizardData['captcha'])) unset($observable->output->wizardData['captcha']);
        $aTplOpts['data'] =  $observable->output->wizardData;

        // obligatory template options
        $aTplOpts['moduleName']    = 'enquiry';
        $aTplOpts['htmlTemplate']  = 'email_template.html';
        $aTplOpts['mode'] = SGL_Emailer_Builder::MODE_HTML_ONLY;

        $bEmailSent = SGL_Emailer2::send($aDeliveryOpts, $aTplOpts);

        if (!$bEmailSent) {
            return SGL::raiseError('Problem sending email', SGL_ERROR_EMAILFAILURE);
        }
    }
}

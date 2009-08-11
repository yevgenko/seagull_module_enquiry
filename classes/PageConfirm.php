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
class PageConfirm extends HTML_QuickForm_Page
{
    /**
     * Build specific form using cms api and process it using observers
     *
     * @access public
     * @return void
     */
    function buildForm()
    {
        $this->_formBuilt = true;
        SGL_Session::set('n_captcha', SGL_Session::get('captcha'));

        include_once SGL_CORE_DIR . '/Captcha.php';
        $captcha = new SGL_Captcha();

        // set code in users session
        $oCaptcha = $captcha->generateCaptcha();
        $this->addElement(
            'html',
            '<b>Human verification</b>'.
            ' (please, enter the number you see below into captcha field)'.
            '<br/><br/><pre>' . $oCaptcha . '</pre>'
        );
        $this->addElement(
            'text',
            'captcha',
            SGL_String::translate('Captcha'),
            array('size' => 50, 'maxlength' => 255)
        );
        //  submit
        $prevnext[] =& $this->createElement(
            'submit',
            $this->getButtonName('back'),
            SGL_String::translate('<< Back')
        );
        $prevnext[] =& $this->createElement(
            'submit',
            $this->getButtonName('next'),
            SGL_String::translate('Confirm >>')
        );
        $this->addGroup($prevnext, 'button', '', '&nbsp;', false);

        $this->registerRule(
            'captchaCheck',
            '',
            'HTML_QuickForm_Rule_Captcha',
            SGL_MOD_DIR . '/enquiry/classes/Rule/Captcha.php'
        );
        $this->addRule(
            'captcha',
            'You must enter the number in this field',
            'captchaCheck'
        );
        $this->addRule(
            'captcha',
            'You must enter the number in this field',
            'required'
        );

        $this->setDefaultAction('next');
    }
}

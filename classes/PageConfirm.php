<?php

class PageConfirm extends HTML_QuickForm_Page
{
    function buildForm()
    { 
        $this->_formBuilt = true;

        // Add some elements to the form
        $this->addElement('header', null, SGL_String::translate('Thanl You'));

        //  submit
        $this->addElement('submit', $this->getButtonName('back'), SGL_String::translate('<< Back'));

        $this->setDefaultAction('back');
    }
}

?>
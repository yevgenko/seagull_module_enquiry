<?php

class PageForm extends HTML_QuickForm_Page
{

    function PageForm($formName, $method = 'post', $target = '', $attributes = null, $content_type)
    {
        $this->HTML_QuickForm_Page($formName, $method, '', $target, $attributes);
        $this->content = $content_type;
    }

    function buildForm()
    {
        $this->_formBuilt = true;

        // Add some elements to the form
        $this->addElement('header', null, SGL_String::translate('Contact Details'));
        $this->addElement('text', 'name', SGL_String::translate('Name'), array('size' => 50, 'maxlength' => 255));
        $this->addElement('text', 'email', SGL_String::translate('Email'), array('size' => 50, 'maxlength' => 255));

        foreach($this->content->aAttribs as $element) {
            switch ($element->typeName) {
                case 'Large text':
                    $this->addElement('textarea', $element->name, $element->alias, array('style' => 'width: 300px;', 'cols' => 50, 'rows' => '7'));
                break;
                default:
                    $this->addElement('text', $element->name, $element->alias);
                break;
            }
        }

        $this->addElement('submit', $this->getButtonName('next'), SGL_String::translate('Submit'));

        $this->addRule('name', SGL_String::translate('Please enter your name'), 'required');
        $this->addRule('email', SGL_String::translate('Please enter your email'), 'required');

        $this->setDefaultAction('next');
    }
}

?>
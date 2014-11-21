<?php

/**
 * @User:  ThanhChat
 * Date:   Nov 18, 2014
 * Time:   8:27:51 PM
 */
class Portal_Form_LoginForm extends Zend_Form
{
    public function init()
    {
        $email = $this->createElement('text','email');
        $email->setRequired(true);
        $password = $this->createElement('password','password');
        $password->setRequired(true);
        $signin = $this->createElement('submit','signin');
        $signin->setLabel('Sign in')
               ->setIgnore(true);
        $this->addElements(array(
            $email,
            $password,
            $signin,
        ));
        $this->setDecorators(array(
                          array('viewScript',
                          array('viewScript'=>'LoginForm.phtml'),
        )));
        $email->removeDecorator('Errors');
        $password->removeDecorator('Errors');
        $email->removeDecorator('HtmlTag')->removeDecorator('Label');
        $password->removeDecorator('HtmlTag')->removeDecorator('Label');
        $signin->removeDecorator('DtDdWrapper');
    }
}
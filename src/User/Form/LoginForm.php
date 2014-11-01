<?php
namespace User\Form;

use Zend\Form\Form;

/**
 * Class LoginForm
 * @package User\Form
 */
class LoginForm extends Form
{
    public function __construct($name = 'Login')
    {
        parent::__construct($name);

        $this->setAttributes(array(
            'method' => 'post',
            'id'     => 'user-login-form',
        ));

        // email
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'      => 'email',
                'id'        => 'login-email',
                'maxlength' => 255,
                'size'      => 25,
                'placeholder' => 'Email',
                'required'  => 'required',
                'class'     => 'form-control top',
                'autofocus' => 'autofocus',
            ),
            'options' => array(
                'label' => 'E-Mail',
            ),
        ));

        // password
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'        => 'password',
                'id'          => 'login-password',
                'maxlength'   => 255,
                'placeholder' => 'Password',
                'required'    => 'required',
                'class'       => 'form-control bottom',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        // rememberMe
        $this->add(array(
            'name' => 'rememberMe',
            'type' => 'Checkbox',
            'attributes' => array(
                'id'   => 'login-rememberMe'
            ),
            'options' => array(
                'use_hidden_element' => false,
                // 'checked_value' => 1,
                'label' => 'Remember me',
            ),
        ));
    }
}

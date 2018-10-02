<?php

namespace frontend\models;

use Da\User\Form\RegistrationForm as BaseForm;


class RegistrationForm extends BaseForm {
    
    public $captcha;

    /**
     * Override from parent
     */
    public function rules() {

        $rules = parent::rules();

        $rules[] = ['captcha', 'required'];
        $rules[] = ['captcha', 'captcha'];

        return $rules;
    }
}
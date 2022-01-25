<?php

namespace app\controllers;

use app\forms\TestRunForm;
use core\Utils;
use core\App;
use core\ParamUtils;
use core\Validator;

class TestRunCtrl {

    private $form;

    public function __construct() {
        $this->form = new TestRunForm();
    }
}
<?php

namespace app\controllers;

use app\forms\TestCaseForm;
use core\Utils;
use core\App;
use core\ParamUtils;
use core\Validator;

class TestCaseCtrl {

    private $form;

    public function __construct() {
        $this->form = new TestCaseForm();
    }

    private function validateForm() {
        $this->form->id = ParamUtils::getFromPost('id');
        $this->form->name = ParamUtils::getFromPost('name');
        $this->form->preconditions = htmlspecialchars(ParamUtils::getFromPost('preconditions'));
        $this->form->steps = htmlspecialchars(ParamUtils::getFromPost('steps'));
        $this->form->expectedResult = htmlspecialchars(ParamUtils::getFromPost('expected_result'));

        if (!isset($this->form->name) || !isset($this->form->preconditions) || !isset($this->form->steps) || !isset($this->form->expectedResult)) {
            return false;
        }

        $v = new Validator();

        $this->form->name = $v->validate($this->form->name, [
            'required' => true,
            'required_message' => "Uzupełnij nazwę",
        ]);
        
        $this->form->steps = $v->validate($this->form->steps, [
            'required' => true,
            'required_message' => "Uzupełnij kroki wykonania"
        ]);

        $this->form->expectedResult = $v->validate($this->form->expectedResult, [
            'required' => true,
            'required_message' => "Uzupełnij oczekiwany rezultat"
        ]);

        if (App::getMessages()->isError()) {
            return false;
        }

        return true;
    }

    public function action_testCaseSave() {
        if ($this->validateForm()) {
            try {
                if (empty($this->form->id)) {
                    App::getDB()->insert("test_case", [
                        "name" => $this->form->name,
                        "preconditions" => $this->form->preconditions,
                        "steps" => $this->form->steps,
                        "expected_result" => $this->form->expectedResult
                    ]);
    
                    Utils::addInfoMessage("Dodano przypadek testowy");
                } else {     
                    // TODO: Update
                    //Utils::addInfoMessage("Zapisano przypadek testowy");
                }
            } catch (\PDOException $e) {
                Utils::addErrorMessage("Błąd zapisu przypadku testowego " . $e->getMessage());
            }
            App::getRouter()->forwardTo("testCaseList");
        }

        $this->generateFormView();
    }
 
    
    private function generateFormView() {
        App::getSmarty()->assign('form', $this->form);
        App::getSmarty()->display('testCaseForm.tpl');
    }

    public function action_testCaseList() {

        $search = ParamUtils::getFromPost("search");
        $where["ORDER"] = "id";
        if (isset($search) && strlen($search) > 0) {
            $where["OR"] = [
                "name[~]" => $search
            ];
        }

        try {
            $testCaseList = App::getDB()->select("test_case", [
                "id",
                "name",
            ], $where);
        } catch (\PDOException $e) {
            Utils::addErrorMessage("Błąd pobierania listy przypadkow testowych " . $e->getMessage());
        }

        App::getSmarty()->assign('search', $search);
        App::getSmarty()->assign('testCaseList', $testCaseList);
        App::getSmarty()->display('testCaseList.tpl');
    }
}
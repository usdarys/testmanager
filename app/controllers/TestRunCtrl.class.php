<?php

namespace app\controllers;

use app\forms\TestRunForm;
use app\types\TestResultStatusType;
use core\Utils;
use core\App;
use core\ParamUtils;
use core\Validator;
use core\ArrayUtils;

class TestRunCtrl {

    private $form;

    public function __construct() {
        $this->form = new TestRunForm();
    }

    private function validateForm() {
        $this->form->id = ParamUtils::getFromPost('id');
        $this->form->name = ParamUtils::getFromPost('name');
        $this->form->description = htmlspecialchars(ParamUtils::getFromPost('description'));
        $this->form->caseIncludeType = ParamUtils::getFromPost("case_include_type");
        $this->form->selectedTestCases = ArrayUtils::preg_grep_keys('/^tc_/', $_POST);

        if (!isset($this->form->name) || !isset($this->form->description)) {
            return false;
        }

        $v = new Validator();

        $this->form->name = $v->validate($this->form->name, [
            'required' => true,
            'required_message' => "Uzupełnij nazwę"
        ]);

        if ($this->form->caseIncludeType == "selected" && empty($this->form->selectedTestCases)) {
            Utils::addErrorMessage("Nie wybrano przypadków testowych");
        }

        if (App::getMessages()->isError()) {
            return false;
        }

        return true;
    }

    public function action_testRunSave() {
        if ($this->validateForm()) {
            try {
                App::getDB()->pdo->beginTransaction();
                if (empty($this->form->id)) {
                    date_default_timezone_set("Europe/Warsaw");
                    App::getDB()->insert("test_run", [
                        "name" => $this->form->name,
                        "description" => $this->form->description,
                        "date_created" => date('Y-m-d H:i:s')
                    ]);

                    $this->form->id = App::getDB()->id();

                    if ($this->form->caseIncludeType == "selected") {
                        $where = 'id IN (' . implode(', ', $this->form->selectedTestCases) . ')';
                    } else {
                        $where = "1 = 1";
                    }

                    App::getDB()->query('
                        INSERT INTO test_result (test_run_id, test_case_id, status)
                        SELECT ' . $this->form->id . ', id, \'' . TestResultStatusType::UNTESTED . '\'
                        FROM test_case
                        WHERE ' . $where . ';
                    ');
    
                    Utils::addInfoMessage("Dodano przebieg testów");
                } else {     
                    // TODO: Update
                    //Utils::addInfoMessage("Zapisano przypadek testowy");
                }
                App::getDB()->pdo->commit();
            } catch (\PDOException $e) {
                App::getDB()->pdo->rollBack();
                Utils::addErrorMessage("Błąd zapisu przebiegu testów " . $e->getMessage());
            }
            App::getRouter()->forwardTo("testRunList");
        }

        $this->generateFormView();
    }
 
    
    private function generateFormView() {
        try {
            $testCaseList = App::getDB()->select("test_case", [
                "id",
                "name"
            ]);
        } catch (\PDOException $e) {
            Utils::addErrorMessage("Błąd pobierania listy przypadkow testowych " . $e->getMessage());
        }

        App::getSmarty()->assign('testCaseList', $testCaseList);
        App::getSmarty()->assign('form', $this->form);
        App::getSmarty()->display('testRunForm.tpl');
    }

    public function action_testRunList() {

        $search = ParamUtils::getFromPost("search");
        $where["ORDER"] = "id";
        if (isset($search) && strlen($search) > 0) {
            $where["OR"] = [
                "name[~]" => $search
            ];
        }

        try {
            $testRunList = App::getDB()->select("test_run", [
                "id",
                "name",
                "date_created"
            ], $where);
        } catch (\PDOException $e) {
            Utils::addErrorMessage("Błąd pobierania listy przebiegów testów " . $e->getMessage());
        }

        App::getSmarty()->assign('search', $search);
        App::getSmarty()->assign('testRunList', $testRunList);
        App::getSmarty()->display('testRunList.tpl');
    }
    
}
<?php

namespace app\controllers;

use app\forms\TestResultForm;
use app\types\TestResultStatusType;
use core\ParamUtils;
use core\App;
use core\Utils;
use core\Validator;

class TestResultCtrl {

    private $testRun;
    private $form;

    public function __construct() {
        $this->form = new TestResultForm();
    }

    public function action_testResultList() {
        $testRunId = $this->getResourceIdFromURL(1);

        if (!$testRunId) {
            App::getRouter()->forwardTo("testRunList");
        }

        try {
            $this->testRun = App::getDB()->get("test_run", "*", ["id" => $testRunId]);
            $this->form->testRunId = $this->testRun["id"];

            $testResultList = App::getDB()->select("test_result", [
                "[><]test_case" => ["test_case_id" => "id"],
                "[>]user_account" => ["user_account_id" => "id"]
            ], [
                "test_case.id",
                "test_case.name",
                "user_account.first_name",
                "user_account.last_name",
                "test_result.date_run",
                "test_result.status"
            ], [
                "test_result.test_run_id" => $this->testRun["id"]
            ]);
        } catch (\PDOException $e) {
            Utils::addErrorMessage("Błąd pobierania listy rezultatów " . $e->getMessage());
        }

        $testRunStats = [
            "all" => count($testResultList),
            "tested" => 0,
            "passed" => 0,
            "failed" => 0
        ];
        foreach($testResultList as $r) {
            if ($r["status"] != TestResultStatusType::UNTESTED) {
                $testRunStats["tested"]++;
            } elseif ($r["status"] != TestResultStatusType::PASSED) {
                $testRunStats["passed"]++;
            } elseif ($r["status"] != TestResultStatusType::FAILED) {
                $testRunStats["failed"]++;
            }
        }
        // foreach($testResultList as $r) {
        //     Utils::addWarningMessage($r["id"] . ';' . $r["name"] . ';' . $r["first_name"] . ';' . $r["last_name"] . ';' . $r["date_run"] . ';' . $r["status"]);
        // }

        App::getSmarty()->assign("testRunStats", $testRunStats);
        App::getSmarty()->assign('testRun', $this->testRun);
        App::getSmarty()->assign('testResultList', $testResultList);
        App::getSmarty()->display('testResultList.tpl');
    }   

    public function action_testResultSave() {

    }

    public function action_testResultUpdate() {
        $testRunId = $this->getResourceIdFromURL(1);
        $testCaseId = $this->getResourceIdFromURL(2);

        if (!$testRunId || !$testCaseId) {
            App::getRouter()->forwardTo("testRunList");
        }

        try {
            $result = App::getDB()->get("test_result", [
                "[><]test_case" => ["test_case_id" => "id"]
            ], [
                "test_case.name",
                "test_case.preconditions",
                "test_case.steps",
                "test_case.expected_result",
                "test_result.status",
                "test_result.comment"
                
            ], [
               "AND" => [
                    "test_result.test_run_id" => $testRunId,
                    "test_result.test_case_id" => $testCaseId
               ] 
            ]);

            $this->form->testRunId = $testRunId;
            $this->form->testCaseId = $testCaseId;
            $this->form->testCaseName = $result["name"];
            $this->form->testCasePreconditions = $result["preconditions"];
            $this->form->testCaseSteps = $result["steps"];
            $this->form->testCaseExpectedResult = $result["expected_result"];
            $this->form->status = $result["status"];
            $this->form->comment = $result["comment"];
        } catch (\PDOException $e) {
            Utils::addErrorMessage("Błąd podczas pobierania wynikow wykonania przypadku testowego " . $e->getMessage());
        }
        $this->generateFormView();     
    }

    private function getResourceIdFromURL($paramNumber) {
        $v = new Validator();
        $id = $v->validateFromCleanURL($paramNumber, [
            "required" => true,
            "required_message" => "Brak id",
            "int" => true,
            "min" => 1,
            "validator_message" => "Nieprawidlowe id"
        ]);
        if ($v->isLastOK()) {
            return $id;
        }
        return;
    }

    private function generateFormView() {
        App::getSmarty()->assign('form', $this->form);
        App::getSmarty()->display('testResultForm.tpl');
    }

}
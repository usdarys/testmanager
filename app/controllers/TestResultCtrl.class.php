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

    public function action_testResultSave() {
        if ($this->validateForm()) {
            $userId = 1; // pobierz ID aktualnego usera -> wyciagnac to z sesji. Zapisac se go przy logowaniu
            $date = "aktualna data"; // todo

            try {
                App::getDB()->update("test_result", [
                    "user_account_id" => $userId,
                    "date_run" => $date,
                    "status" => $this->form->status,
                    "comment" => $this->form->comment
                ], [
                    "AND" => [
                        "test_run_id" => $this->form->testRunId,
                        "test_case_id" => $$this->form->testCaseId
                    ] 
                ]);
            } catch (\PDOException $e) {
                Utils::addErrorMessage("Błąd zapisu wyniku testu " . $e->getMessage());
            }
        }
        if (App::getMessages()->isError()) {
            $this->generateFormView();
        }
        App::getRouter()->forwardTo("testResultList");
    }

    private function validateForm() {
        $v = new Validator();

        $this->form->testRunId = $v->validateFromPost("test_run_id", [
            "required" => true
        ]);

        $this->form->testCaseId = $v->validateFromPost("test_case_id", [
            "required" => true
        ]);

        $this->form->status = ParamUtils::getFromPost("status");
        if (!in_array($this->form->status, TestResultStatusType::getList())) {
            Utils::addErrorMessage("Nieprawidłowy status testu");
        }

        $this->form->comment = htmlspecialchars(ParamUtils::getFromPost("comment"));

        if (App::getMessages()->isError()) {
            return false;
        }

        return true;
    } 

    public function action_testResultUpdate() {
        $testRunId = ParamUtils::getResourceIdFromCleanURL(1);
        $testCaseId = ParamUtils::getResourceIdFromCleanURL(2);

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

    private function generateFormView() {
        App::getSmarty()->assign('form', $this->form);
        App::getSmarty()->display('testResultForm.tpl');
    }

    public function action_testResultList() {
        $testRunId = ParamUtils::getResourceIdFromCleanURL(1);

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

        App::getSmarty()->assign("testRunStats", $this->getStats($testResultList));
        App::getSmarty()->assign('testRun', $this->testRun);
        App::getSmarty()->assign('testResultList', $testResultList);
        App::getSmarty()->display('testResultList.tpl');
    }

    private function getStats($testResultList) {
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
        return $testRunStats;
    }

}
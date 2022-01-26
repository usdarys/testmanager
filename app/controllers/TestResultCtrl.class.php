<?php

namespace app\controllers;

use app\types\TestResultStatusType;
use core\ParamUtils;
use core\App;
use core\Utils;
use core\Validator;

class TestResultCtrl {

    private $testRun;

    public function action_testResultList() {

        $v = new Validator();
        $testRunId = $v->validateFromCleanURL(1, [
            "required" => true,
            "required_message" => "Brak id",
            "int" => true,
            "min" => 1,
            "validator_message" => "Nieprawidlowe id"
        ]);
        if (!$v->isLastOK()) {
            App::getRouter()->forwardTo("testRunList");
        }

        try {
            $this->testRun = App::getDB()->get("test_run", "*", ["id" => $testRunId]);

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

}
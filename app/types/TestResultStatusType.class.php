<?php 

namespace app\types;

class TestResultStatusType {

    const UNTESTED = 'Niewykonany';
    const PASSED = 'Zaliczony';
    const FAILED = 'Niezaliczony';

    public static function getList() {
        return array(
            "UNTESTED" => self::UNTESTED,
            "PASSED" => self::PASSED,
            "FAILED" => self::FAILED
        );
    }

}
<?php

namespace core;

class ArrayUtils {

    public static function preg_grep_keys($pattern, $input, $flags = 0) {
        return array_intersect_key(
            $input,
            array_flip(preg_grep(
               $pattern,
               array_keys($input),
               $flags
            ))
        );
    }

    public static function flatten(array $array) {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
        return $return;
    }

}
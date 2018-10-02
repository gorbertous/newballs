<?php

namespace common\dictionaries;

/**
 * Class Somenumbers
 * @package common\dictionaries
 */
abstract class Somenumbers
{

    use TraitDictionaries;

    /**
     * @return array
     */
    public static function all(): array
    {

        $numberslist = array();
        for ($i = 0; $i <= 20; $i++) {
            array_push($numberslist, $i);
        }

        return $numberslist;
    }

}

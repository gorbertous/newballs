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
    public static function all($max = 20): array
    {

        $numberslist = array();
        for ($i = 0; $i <= $max; $i++) {
            array_push($numberslist, $i);
        }

        return $numberslist;
    }

}

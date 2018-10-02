<?php

namespace common\dictionaries;

/**
 * Class ChemicalsHealthTypes
 * @package common\dictionaries
 */
abstract class ChemicalsHealthTypes
{
    use TraitDictionaries;

    const NULL   = null;
    const GREEN  = 1;
    const ORANGE = 2;
    const RED    = 3;

    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::NULL   => '<i class="fa fa-heart fa-2x"style="color: green;"></i>',
            self::GREEN  => '<i class="fa fa-heart fa-2x"style="color: green;"></i>',
            self::ORANGE => '<i class="fa fa-heart fa-2x"style="color: orange;"></i>',
            self::RED    => '<i class="fa fa-heart fa-2x"style="color: red;"></i>'
        ];
    }
}
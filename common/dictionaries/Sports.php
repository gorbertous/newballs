<?php

namespace common\dictionaries;

use Yii;

/**
 * Class Sports
 * @package common\dictionaries
 */
abstract class Sports
{

    use TraitDictionaries;

    const TENNIS = 1;
    const BADMINTON = 2;
    const FOOTBALL = 3;
    const GRASSHOCKEY = 4;
    const HANDBALL = 5;
    const VOLLEYBALL = 6;

    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::TENNIS      => Yii::t('modelattr', 'Tennis'),
            self::BADMINTON   => Yii::t('modelattr', 'Badminton'),
            self::FOOTBALL    => Yii::t('modelattr', 'Football'),
            self::GRASSHOCKEY => Yii::t('modelattr', 'Grass Hockey'),
            self::HANDBALL    => Yii::t('modelattr', 'Handball'),
            self::VOLLEYBALL  => Yii::t('modelattr', 'Volleyball'),
        ];
    }

}

<?php

namespace common\dictionaries;

use Yii;

/**
 * Class CompartmentFireLevels
 * @package common\dictionaries
 */
abstract class Grades
{

    use TraitDictionaries;
    
    const TBEGINNER  = 1;
    const BEGINNER = 2;
    const INTERMEDIATE  = 3;
    const ABAVERAGE  = 4;
    const GOOD = 5;
    const VGOOD  = 6;
    const LTAR = 7;
    const ITFR = 8;
    const NATRANK = 9;
    const NONE = 10;
            
    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::TBEGINNER => Yii::t('modelattr', 'Total Beginner'),
            self::BEGINNER => Yii::t('modelattr', 'Beginner'),
            self::INTERMEDIATE => Yii::t('modelattr', 'Intermediate'),
            self::ABAVERAGE => Yii::t('modelattr', 'Above average'),
            self::GOOD => Yii::t('modelattr', 'Good'),
            self::VGOOD => Yii::t('modelattr', 'Very good'),
            self::LTAR => Yii::t('modelattr', 'LTA Ranking'),
            self::ITFR => Yii::t('modelattr', 'ITF Ranking'),
            self::NATRANK => Yii::t('modelattr', 'Country Ranking'),
            self::NONE => Yii::t('modelattr', 'None of the above'),
        ];
    }

}
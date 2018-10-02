<?php

namespace common\dictionaries;

use Yii;

/**
 * Class Slots
 * @package common\dictionaries
 */
abstract class Slots
{
    use TraitDictionaries;

    const S1 = 1;
    const S2 = 2;
    const S3 = 3;
    const S4 = 4;
    const S5 = 5;
    const S6 = 6;
    const S7 = 7;
    const S8 = 8;
    const S9 = 9;
    const S10 = 10;
    const S11 = 11;
    const S12 = 12;
    const S13 = 13;
    const S14 = 14;
    const S15 = 15;
    const S16 = 16;
    const S17 = 17;
    const S18 = 18;
    const S19 = 19;
    const S20 = 20;

    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::S1  => Yii::t('modelattr', 'Slot') . ' 1',
            self::S2  => Yii::t('modelattr', 'Slot') . ' 2',
            self::S3  => Yii::t('modelattr', 'Slot') . ' 3',
            self::S4  => Yii::t('modelattr', 'Slot') . ' 4',
            self::S5  => Yii::t('modelattr', 'Slot') . ' 5',
            self::S6  => Yii::t('modelattr', 'Slot') . ' 6',
            self::S7  => Yii::t('modelattr', 'Slot') . ' 7',
            self::S8  => Yii::t('modelattr', 'Slot') . ' 8',
            self::S9  => Yii::t('modelattr', 'Slot') . ' 9',
            self::S10 => Yii::t('modelattr', 'Slot') . ' 10',
            self::S11 => Yii::t('modelattr', 'Slot') . ' 11',
            self::S12 => Yii::t('modelattr', 'Slot') . ' 12',
            self::S13 => Yii::t('modelattr', 'Slot') . ' 13',
            self::S14 => Yii::t('modelattr', 'Slot') . ' 14',
            self::S15 => Yii::t('modelattr', 'Slot') . ' 15',
            self::S16 => Yii::t('modelattr', 'Slot') . ' 16',
            self::S17 => Yii::t('modelattr', 'Slot') . ' 17',
            self::S18 => Yii::t('modelattr', 'Slot') . ' 18',
            self::S19 => Yii::t('modelattr', 'Slot') . ' 19',
            self::S20 => Yii::t('modelattr', 'Slot') . ' 20',
        ];
    }

    /**
     * @return array
     */
    public static function filtered(): array
    {
        return [
            self::S1 => Yii::t('modelattr', 'Slot') . ' 1',
            self::S2 => Yii::t('modelattr', 'Slot') . ' 2',
            self::S3 => Yii::t('modelattr', 'Slot') . ' 3',
            self::S4 => Yii::t('modelattr', 'Slot') . ' 4',
        ];
    }

}
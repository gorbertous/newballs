<?php

namespace common\dictionaries;

use Yii;

/**
 * Class Courts
 * @package common\dictionaries
 */
abstract class Courts
{

    use TraitDictionaries;

    const C1 = 1;
    const C2 = 2;
    const C3 = 3;
    const C4 = 4;
    const C5 = 5;
    const C6 = 6;
    const C7 = 7;
    const C8 = 8;
    const C9 = 9;
    const C10 = 10;
    const C11 = 11;
    const C12 = 12;
    const C13 = 13;
    const C14 = 14;
    const C15 = 15;
    const C16 = 16;
    const C17 = 17;
    const C18 = 18;
    const C19 = 19;
    const C20 = 20;

    /**
     * @return array
     */
//    public static function all(): array
//    {
//        return [
//            self::C1  => Yii::t('modelattr', 'Court') . ' 1',
//            self::C2  => Yii::t('modelattr', 'Court') . ' 2',
//            self::C3  => Yii::t('modelattr', 'Court') . ' 3',
//            self::C4  => Yii::t('modelattr', 'Court') . ' 4',
//            self::C5  => Yii::t('modelattr', 'Court') . ' 5',
//            self::C6  => Yii::t('modelattr', 'Court') . ' 6',
//            self::C7  => Yii::t('modelattr', 'Court') . ' 7',
//            self::C8  => Yii::t('modelattr', 'Court') . ' 8',
//            self::C9  => Yii::t('modelattr', 'Court') . ' 9',
//            self::C10 => Yii::t('modelattr', 'Court') . ' 10',
//            self::C11 => Yii::t('modelattr', 'Court') . ' 11',
//            self::C12 => Yii::t('modelattr', 'Court') . ' 12',
//            self::C13 => Yii::t('modelattr', 'Court') . ' 13',
//            self::C14 => Yii::t('modelattr', 'Court') . ' 14',
//            self::C15 => Yii::t('modelattr', 'Court') . ' 15',
//            self::C16 => Yii::t('modelattr', 'Court') . ' 16',
//            self::C17 => Yii::t('modelattr', 'Court') . ' 17',
//            self::C18 => Yii::t('modelattr', 'Court') . ' 18',
//            self::C19 => Yii::t('modelattr', 'Court') . ' 19',
//            self::C20 => Yii::t('modelattr', 'Court') . ' 20',
//        ];
//    }
    
    public static function all(): array
    {

        $numberslist = array();
        for ($i = 0; $i <= 20; $i++) {
            if($i > 0){
                array_push($numberslist, Yii::t('modelattr', 'Court') . ' '.$i);
            }else{
                array_push($numberslist, null);
            }
        }

        return $numberslist;
    }

    /**
     * @return array
     */
    public static function filtered(): array
    {
        return [
            self::C1 => Yii::t('modelattr', 'Court') . ' 1',
            self::C2 => Yii::t('modelattr', 'Court') . ' 2',
            self::C3 => Yii::t('modelattr', 'Court') . ' 3',
            self::C4 => Yii::t('modelattr', 'Court') . ' 4',
        ];
    }

}

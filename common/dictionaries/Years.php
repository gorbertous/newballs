<?php

namespace common\dictionaries;

use Yii;

/**
 * Class Years
 * @package common\dictionaries
 */
abstract class Years
{

    use TraitDictionaries;

    const Y1 = 2005;
    const Y2 = 2006;
    const Y3 = 2007;
    const Y4 = 2008;
    const Y5 = 2009;
    const Y6 = 2010;
    const Y7 = 2011;
    const Y8 = 2012;
    const Y9 = 2013;
    const Y10 = 2014;
    const Y11 = 2015;
    const Y12 = 2016;
    const Y13 = 2017;
    const Y14 = 2018;
    const Y15 = 2019;
   

    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::Y1  => Yii::t('modelattr', 'Year') . ' 2005',
            self::Y2  => Yii::t('modelattr', 'Year') . ' 2006',
            self::Y3  => Yii::t('modelattr', 'Year') . ' 2007',
            self::Y4  => Yii::t('modelattr', 'Year') . ' 2008',
            self::Y5  => Yii::t('modelattr', 'Year') . ' 2009',
            self::Y6  => Yii::t('modelattr', 'Year') . ' 2010',
            self::Y7  => Yii::t('modelattr', 'Year') . ' 2011',
            self::Y8  => Yii::t('modelattr', 'Year') . ' 2012',
            self::Y9  => Yii::t('modelattr', 'Year') . ' 2013',
            self::Y10 => Yii::t('modelattr', 'Year') . ' 2014',
            self::Y11 => Yii::t('modelattr', 'Year') . ' 2015',
            self::Y12 => Yii::t('modelattr', 'Year') . ' 2016',
            self::Y13 => Yii::t('modelattr', 'Year') . ' 2017',
            self::Y14 => Yii::t('modelattr', 'Year') . ' 2018',
            self::Y15 => Yii::t('modelattr', 'Year') . ' 2019',
            
        ];
    }
  

}

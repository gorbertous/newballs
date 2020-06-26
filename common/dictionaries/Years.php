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

    const Y0 = 0;
    const Y1 = 1;
    const Y2 = 2;
    const Y3 = 3;
    const Y4 = 4;
    const Y5 = 5;
    const Y6 = 6;
    const Y7 = 7;
    const Y8 = 8;
    const Y9 = 9;
    const Y10 = 10;
    const Y11 = 11;
    const Y12 = 12;
    const Y13 = 13;
    const Y14 = 14;
//    const Y15 = 15;
   

    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::Y0  => Yii::t('app', 'All time'),
            self::Y1  => '2006/2007',
            self::Y2  => '2007/2008',
            self::Y3  => '2008/2009',
            self::Y4  => '2009/2010',
            self::Y5  => '2010/2011',
            self::Y6  => '2011/2012',
            self::Y7  => '2012/2013',
            self::Y8  => '2013/2014',
            self::Y9  => '2014/2015',
            self::Y10 => '2015/2016',
            self::Y11 => '2016/2017',
            self::Y12 => '2017/2018',
            self::Y13 => '2018/2019',
            self::Y14 => '2019/2020',
//            self::Y15 => '2019',
            
        ];
    }
    
     /**
     * @param $type
     *
     * @return mixed|string
     */
    public static function getYear($type)
    {
        $all = self::all();
        if (isset($all[$type])) {
            return $all[$type];
        }
        return '-';
    }
  

}

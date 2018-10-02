<?php

namespace common\dictionaries;

use Yii;

/**
 * Class ClubSessions
 * @package common\dictionaries
 */
abstract class ClubSessions
{

    use TraitDictionaries;

    const HALFHOUR = 1;
    const THREEQHOUR = 2;
    const HOUR = 3;
    const TWOHOURS = 4;
    const THREEHOURS = 5;
    const HOURANDHALF = 6;


    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::HALFHOUR   => Yii::t('modelattr', '30 minutes'),
            self::THREEQHOUR => Yii::t('modelattr', '45 minutes'),
            self::HOUR  => Yii::t('modelattr', '1 hour'),
            self::TWOHOURS   => Yii::t('modelattr', '2 hours'),
            self::THREEHOURS => Yii::t('modelattr', '3 hours'),
            self::HOURANDHALF  => Yii::t('modelattr', '90 minutes'),

        ];
    }

}

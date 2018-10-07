<?php

namespace common\dictionaries;

use Yii;

class BooleanFilter
{
    use TraitDictionaries;

    const ALL = -1;
    const FUTURE = 1;
    const PAST     = 2;

    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::ALL =>  Yii::t('modelattr', 'Entire Rota'),
            self::FUTURE =>  Yii::t('modelattr', 'Future Games'),
            self::PAST     =>  Yii::t('modelattr', 'Past Games')
        ];
    }
}
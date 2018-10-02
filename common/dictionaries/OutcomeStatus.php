<?php

namespace common\dictionaries;

use Yii;

/**
 * Class AbsenceTypes
 * @package common\dictionaries
 */
abstract class OutcomeStatus
{

    use TraitDictionaries;
    
    const PLAYED = 1;
    const FOUNDSUB = 2;
    const NOSHOW = 3;
    const PENDING = 4;
    const CANCELLED = 5;
    const COACHED = 6;
    const NONSCHPLAY = 7;

    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::PLAYED      => Yii::t('modelattr', 'Played'),
            self::FOUNDSUB   => Yii::t('modelattr', 'Found Substitute'),
            self::NOSHOW    => Yii::t('modelattr', 'No Show'),
            self::PENDING => Yii::t('modelattr', 'Pending'),
            self::CANCELLED    => Yii::t('modelattr', 'Cancelled'),
            self::COACHED  => Yii::t('modelattr', 'Coached'),
            self::NONSCHPLAY  => Yii::t('modelattr', 'Non Scheduled Play'),
        ];
    }

}
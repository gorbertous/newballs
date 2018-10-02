<?php

namespace common\dictionaries;

use Yii;

/**
 * Class ContactTitlesHon
 * @package common\dictionaries
 */
abstract class ContactTitlesHon
{

    use TraitDictionaries;
    
    const Dr = 'Dr';
    const Me = 'Me';
    const Pr = 'Pr';
            
    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::Dr => Yii::t('modelattr', 'Dr'),
            self::Me => Yii::t('modelattr', 'Me'),
            self::Pr => Yii::t('modelattr', 'Pr')
        ];
    }

}
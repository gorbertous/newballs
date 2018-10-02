<?php

namespace common\dictionaries;

use Yii;

/**
 * Class ContactTitles
 * @package common\dictionaries
 */
abstract class ContactTitles
{

    use TraitDictionaries;
    
    const Mr = 'Mr';
    const Ms = 'Ms';
            
    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::Mr => Yii::t('modelattr', 'Mr'),
            self::Ms => Yii::t('modelattr', 'Ms')
        ];
    }

}
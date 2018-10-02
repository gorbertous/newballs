<?php

namespace common\dictionaries;

use Yii;

/**
 * Class NewsCategories
 * @package common\dictionaries
 */
abstract class NewsCategories
{

    use TraitDictionaries;

    const GENERAL = 'G';
    const SPORTRELATED = 'S';
    const CLUBINTERNAL = 'C';

    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::GENERAL      => Yii::t('modelattr', 'General News'),
            self::SPORTRELATED => Yii::t('modelattr', 'Sport News'),
            self::CLUBINTERNAL => Yii::t('modelattr', 'Club News'),
        ];
    }

}

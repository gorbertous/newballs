<?php

namespace common\dictionaries;

use Yii;

class StatusTypes
{
    use TraitDictionaries;

    const NOT_REQUIRED = 0;
    const NOT_VERIFIED = 1;
    const VERIFIED     = 2;

    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::NOT_REQUIRED => '<i class="text-info block-center fa fa-circle fa-lg" data-toggle="tooltip" title="' . Yii::t('modelattr', 'Not required') . '" aria-hidden="true"></i>',
            self::NOT_VERIFIED => '<i class="text-danger block-center fa fa-times fa-lg" data-toggle="tooltip" title="' . Yii::t('modelattr', 'Not verified') . '" aria-hidden="true"></i>',
            self::VERIFIED     => '<i class="text-success block-center fa fa-check fa-lg" data-toggle="tooltip" title="' . Yii::t('modelattr', 'Verified') . '" aria-hidden="true"></i>'
        ];
    }
}
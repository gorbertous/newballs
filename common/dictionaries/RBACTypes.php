<?php

namespace common\dictionaries;

use Yii;

/**
 * Class RBACTypes
 * @package common\dictionaries
 */
abstract class RBACTypes
{

    use TraitDictionaries;
    
    const RBAC_ROLES = 1;
    const RBAC_PERMISSIONS = 2;
            
    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::RBAC_ROLES => Yii::t('modelattr', 'Roles'),
            self::RBAC_PERMISSIONS => Yii::t('modelattr', 'Permissions'),
        ];
    }

}
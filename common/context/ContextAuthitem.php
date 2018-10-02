<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextAbsences
 * @package common\helpers
 */
abstract class ContextAuthitem implements Context
{
    const LBL_SINGULAR_U = 'RBAC';
    const LBL_PLURAL_U   = 'RBAC';
    const LBL_SINGULAR_L = 'RBAC';
    const LBL_PLURAL_L   = 'RBAC';
    
      /** - */
    const LBL_FS_SINGULAR_U = 'User';
    const LBL_FP_PLURAL_U   = 'Users';
    const LBL_F_SINGULAR_L  = 'user';
    const LBL_F_PLURAL_L    = 'users';

    public static function getContextArray()
    {
        // authitem -> title/header
        $context_array[ContextLetter::RBAC] = [
            'title1'    => Menu::adminText(),
            'ti_icon1'  => Menu::ADMIN_ICON,
            'title2'   => Menu::adminusersText(),
            'ti_icon2' =>Menu::ADMINRBAC_ICON
        ];
        
        // USERS first button -> Users

        $context_array[ContextLetter::RBAC][] = [
            'CW_type'       => ContextLetter::RBAC,
            'button_title' => Menu::usersText(),
            'new_label'    => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'    => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'   => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'    => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'  => Yii::t('appMenu', self::LBL_PRINT),
            'link'         => '/adminusers/index',
            'create'       => '/adminusers/create',
            'fa_icon'      => Menu::USERS_ICON,
            'perm_key'      => 'user',
        ];
        
        
        // authitem -> index
        $context_array[ContextLetter::RBAC][] = [
            'CW_type'       => ContextLetter::RBAC,
            'button_title'  => Menu::adminauthitemText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/authitem/index',
            'create'        => '/authitem/create',
            'perm_key'      => 'authitem',
            'fa_icon'       => Menu::ADMINAUTHITEM_ICON_MENU
        ];
        
        // authitem -> indexp
        $context_array[ContextLetter::RBAC][] = [
            'CW_type'       => ContextLetter::RBAC,
            'button_title'  => Menu::adminauthitempText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/authitem/indexp',
            'create'        => '/authitem/create',
            'perm_key'      => 'authitem',
            'fa_icon'       => Menu::ADMINAUTHITEMP_ICON_MENU
        ];
        
       

        return $context_array;
    }
}

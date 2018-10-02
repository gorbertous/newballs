<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextUser
 * @package common\context
 */
abstract class ContextUser implements Context
{
    const LBL_SINGULAR_U = 'User';
    const LBL_PLURAL_U   = 'Users';
    const LBL_SINGULAR_L = 'user';
    const LBL_PLURAL_L   = 'users';
    /** - */
    const LBL_FS_SINGULAR_U = 'Permission';
    const LBL_FP_PLURAL_U   = 'Permissions';
    const LBL_F_SINGULAR_L  = 'permission';
    const LBL_F_PLURAL_L    = 'permissions';

    public static function getContextArray()
    {
        // USERS HEADER
        $context_array[ContextLetter::USER] = [
            'CW_type'  => ContextLetter::USER,
            'title1'   => Menu::utilitiesText(),
            'ti_icon1' => Menu::UTILITIES_ICON,
            'title2'   => Menu::usersText(),
            'ti_icon2' => Menu::USERS_ICON
        ];

        // USERS first button -> Users
        $context_array[ContextLetter::USER][] = [
            'CW_type'      => ContextLetter::USER,
            'button_title' => Menu::usersText(),
            'new_label'    => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'    => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'   => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'    => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'  => Yii::t('appMenu', self::LBL_PRINT),
            'link'         => '/user/index',
            'create'       => '/user/create',
            'perm_key'      => 'user',
            'fa_icon'      => Menu::USERS_ICON
        ];

        if (Yii::$app->session->get('fieldpermissions')) {
            // USERS second button -> Form field permissions
            $context_array[ContextLetter::USER][] = [
                'CW_type'      => ContextLetter::USER,
                'button_title' => Yii::t('app', self::LBL_FP_PLURAL_U),
                'new_label'    => Yii::t('appMenu', self::LBL_NEW),
                'mod_label'    => Yii::t('appMenu', self::LBL_MODIFY),
                'view_label'   => Yii::t('appMenu', self::LBL_VIEW),
                'del_label'    => Yii::t('appMenu', self::LBL_DELETE),
                'print_label'  => Yii::t('appMenu', self::LBL_PRINT),
                'link'         => '/userrolefields/index',
                'perm_key'      => 'userrolefields',
                'fa_icon'      => 'fa fa-terminal'
            ];
        }

        return $context_array;
    }
}

<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextMandants
 * @package common\context
 */
abstract class ContextMembers implements Context
{
    public static function getContextArray()
    {
        // MEMBERS -> title/header
        $context_array[ContextLetter::MEMBERS] = [
            'title1'    => Menu::membersText(),
            'ti_icon1'  => Menu::MEMBERS_ICON,
            'title2'    => 'Members ' . Menu::dataText(),
            'ti_icon2'  => Menu::DATA_ICON
        ];
        if(Yii::$app->user->can('team_admin')){
           
             // MEMBERS ADMIN -> index
            $context_array[ContextLetter::MEMBERS][] = [
                'new_label'     => Yii::t('appMenu', self::LBL_NEW),
                'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
                'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
                'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
                'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
                'link'          =>  '/members/index',
                'create'        => '/members/create',
                'perm_key'      => 'members',
                ];
        }else{
            // MEMBERS -> index
            $context_array[ContextLetter::MEMBERS][] = [
                'new_label'     => Yii::t('appMenu', self::LBL_NEW),
                'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
                'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
                'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
                'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
                'link'          =>  '/members/index',
    //            'create'        => '/members/create',
                'perm_key'      => 'members',
            ];
        }
        // MEMBERS public -> index
        $context_array[ContextLetter::MEMBERS][] = [
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/membership/index',
            'perm_key'      => 'members',
        ];
      

        return $context_array;
    }
}

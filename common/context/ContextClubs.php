<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextClubs
 * @package common\context
 */
abstract class ContextClubs implements Context
{
    public static function getContextArray()
    {
        // CLUBS -> title/header
        $context_array[ContextLetter::CLUBS] = [
            'title1'   => Menu::adminText(),
            'ti_icon1' => Menu::ADMIN_ICON,
            'title2'    => Menu::clubsText(),
            'ti_icon2'  => Menu::CLUBS_ICON
        ];

        // CLUBS -> index
        $context_array[ContextLetter::CLUBS][] = [
            'button_title' => Menu::clubsText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/clubs/index',
            'create'        => '/clubs/create',
            'perm_key'      => 'clubs',
            'fa_icon'      => Menu::CLUBS_ICON
        ];
        
         // Lcations -> index
        $context_array[ContextLetter::CLUBS][] = [
            'button_title'     => Menu::locationsText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/location/index',
            'create'        => '/location/create',
            'perm_key'      => 'location',
            'fa_icon'      => Menu::LOCATIONS_ICON
        ];
        
         // Fees -> index
        $context_array[ContextLetter::CLUBS][] = [
            'button_title'     => Menu::feesText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/fees/index',
            'create'        => '/fees/create',
            'perm_key'      => 'fees',
            'fa_icon'      => Menu::FEES_ICON
        ];
        
         // Membership types -> index
        $context_array[ContextLetter::CLUBS][] = [
            'button_title'     => Menu::memtypeText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/membershiptype/index',
            'create'        => '/membershiptype/create',
            'perm_key'      => 'membershiptype',
            'fa_icon'      => Menu::MEMTYPE_ICON
        ];


        return $context_array;
    }
}

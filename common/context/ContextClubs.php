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
            'new_label'     => Yii::t('appMenu', 'New club'),
            'mod_label'     => Yii::t('appMenu', 'Modify club data'),
            'view_label'    => Yii::t('appMenu', 'View club data'),
            'print_label'   => Yii::t('appMenu', 'Print club data'),
            'del_label'     => Yii::t('appMenu', 'Delete club data'),
            'link'          => '/clubs/index',
            'create'        => '/clubs/create',
            'perm_key'      => 'clubs',
            'fa_icon'      => Menu::CLUBS_ICON
        ];
        
         // Lcations -> index
        $context_array[ContextLetter::CLUBS][] = [
            'button_title'     => Yii::t('appMenu', 'Locations'),
            'new_label'     => Yii::t('appMenu', 'New location'),
            'mod_label'     => Yii::t('appMenu', 'Modify location data'),
            'view_label'    => Yii::t('appMenu', 'View location data'),
            'print_label'   => Yii::t('appMenu', 'Print location data'),
            'del_label'     => Yii::t('appMenu', 'Delete location data'),
            'link'          => '/location/index',
            'create'        => '/location/create',
            'perm_key'      => 'location',
            'fa_icon'      => 'fa fa-sitemap'
        ];

        return $context_array;
    }
}

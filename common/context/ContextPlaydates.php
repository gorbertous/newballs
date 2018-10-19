<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextConfig
 * @package common\context
 */
abstract class ContextPlaydates implements Context
{
    const LBL_SINGULAR_U = 'Publish Rota';
    const LBL_PLURARL_U  = 'Publish Rota';
    const LBL_SINGULAR_L = 'publish rota';
    const LBL_PLURARL_L  = 'publish rota';

    public static function getContextArray()
    {
        // Config -> title/header
        $context_array[ContextLetter::PLAYDATES] = [
            'CW_type'  => ContextLetter::PLAYDATES,
            'title1'   => Menu::utilitiesText(),
            'ti_icon1' => Menu::UTILITIES_ICON,
            'title2'   => Menu::playdatesText(),
            'ti_icon2' => Menu::PLAYDATES_ICON
        ];

        // Playdates -> index
        $context_array[ContextLetter::PLAYDATES][] = [
            'button_title'  => Menu::playdatesText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/playdates/index',
            'create'        => '/playdates/create',
            'perm_key'      => 'playdates',
            'fa_icon'       => Menu::PLAYDATES_ICON
        ];
        
        // Rota -> index
        $context_array[ContextLetter::PLAYDATES][] = [
            'button_title'  => Menu::rotaText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/gamesboard/index',
            'create'        => '/gamesboard/create',
            'perm_key'      => 'gamesboard',
            'fa_icon'       => Menu::ROTA_ICON
        ];
        
        // Reserves -> index
        $context_array[ContextLetter::PLAYDATES][] = [
            'button_title'  => Menu::reservesText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/reserves/index',
            'create'        => '/reserves/create',
            'perm_key'      => 'reserves',
            'fa_icon'       => Menu::RESERVES_ICON
        ];
        
         // Scores -> index
        $context_array[ContextLetter::PLAYDATES][] = [
            'button_title'  => Menu::scoresText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/scores/index',
            'create'        => '/scores/create',
            'perm_key'      => 'scores',
            'fa_icon'       => Menu::SCORES_ICON
        ];

        return $context_array;
    }
}

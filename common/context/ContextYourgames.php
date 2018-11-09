<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextConfig
 * @package common\context
 */
abstract class ContextYourgames implements Context
{
    const LBL_SINGULAR_U = 'Your Game';
    const LBL_PLURARL_U  = 'Your Games';
    const LBL_SINGULAR_L = 'your game';
    const LBL_PLURARL_L  = 'your games';

    public static function getContextArray()
    {
        // Config -> title/header
        $context_array[ContextLetter::YOURGAMES] = [
            'CW_type'  => ContextLetter::YOURGAMES,
            'title1'   => Menu::rotaText(),
            'ti_icon1' => Menu::UTILITIES_ICON,
            'title2'   => Menu::yourGamesText(),
            'ti_icon2' => Menu::Y_GAMES_ICON
        ];

    
        // Rota -> index
        $context_array[ContextLetter::YOURGAMES][] = [
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/yourgames/index',
            'perm_key'      => 'yourgames',
            'fa_icon'       => Menu::Y_GAMES_ICON
        ];
     

        return $context_array;
    }
}

<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextConfig
 * @package common\context
 */
abstract class ContextRota implements Context
{
    
    public static function getContextArray()
    {
        // Config -> title/header
        $context_array[ContextLetter::ROTA] = [
            'CW_type'  => ContextLetter::ROTA,
            'title1'   => Menu::rotaText(),
            'ti_icon1' => Menu::ROTA_ICON,
            'title2'   => Menu::playdatesText(),
            'ti_icon2' => Menu::PLAYDATES_ICON
        ];
        
        // Rota -> index
        $context_array[ContextLetter::ROTA][] = [
            'button_title'  => Menu::rotaText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/rota/index',
//            'create'        => '/gamesboard/create',
            'perm_key'      => 'rota',
            'fa_icon'       => Menu::ROTA_ICON
        ];
        
        return $context_array;
    }
}

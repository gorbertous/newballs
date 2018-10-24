<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextTexts
 * @package common\context
 */
abstract class ContextLog implements Context
{
    const LBL_SINGULAR_U = 'Log';
    const LBL_PLURAL_U   = 'Logs';
    const LBL_SINGULAR_L = 'log';
    const LBL_PLURAL_L   = 'logs';

    public static function getContextArray()
    {
        // LOGS HEADER
        $context_array[ContextLetter::LOGS] = [
            'CW_type'  => ContextLetter::LOGS,
            'title1'   => Menu::utilitiesText(),
            'ti_icon1' => Menu::UTILITIES_ICON,
            'title2'   => Menu::logText(),
            'ti_icon2' => Menu::LOGS_ICON
        ];

        // LOGS first button -> logs
        $context_array[ContextLetter::LOGS][] = [
            'button_title' => Menu::logText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/log/index',
            'create'       => '/log/create',
            'fa_icon'       => Menu::LOGS_ICON
        ];
        // LOGS first button -> logs
        $context_array[ContextLetter::LOGS][] = [
            'button_title' => Menu::userLogText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/log/users',
            'create'       => '/log/create',
            'fa_icon'       => Menu::LOGS_ICON
        ];

        return $context_array;
    }
}

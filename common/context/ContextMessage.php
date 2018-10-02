<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextMessage
 * @package common\context
 */
abstract class ContextMessage implements Context
{
    const LBL_SINGULAR_U  = 'Translation';
    const LBL_PLURAL_U    = 'Translations';
    const LBL_SINGULAR_L  = 'translation';
    const LBL_PLURAL_L    = 'translations';

    public static function getContextArray()
    {
        // Translations -> title/header
        $context_array[ContextLetter::MESSAGE] = [
            'title1'    => Menu::adminText(),
            'ti_icon1'  => Menu::ADMIN_ICON,
            'title2'   => Menu::translationsText(),
            'ti_icon2' => Menu::TRANSLATIONS_ICON
        ];

        // Translations -> index
        $context_array[ContextLetter::MESSAGE][] = [
            'CW_type'      => ContextLetter::MESSAGE,
            'button_title' => Menu::translationsText(),
            'new_label'    => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'    => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'   => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'    => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'  => Yii::t('appMenu', self::LBL_PRINT),
            'link'         => '/message/index',
            'create'       => '/message/create',
            'perm_key'      => 'message',
            'fa_icon'      => Menu::TRANSLATIONS_ICON
        ];

        // Translations -> duplicate items
        $context_array[ContextLetter::MESSAGE][] = [
            'CW_type'      => ContextLetter::MESSAGE,
            'button_title' => 'Duplicate items',
            'new_label'    => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'    => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'   => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'    => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'  => Yii::t('appMenu', self::LBL_PRINT),
            'link'         => '/message/indexdupes',
            'create'       => '/message/create',
            'perm_key'      => 'message',
            'fa_icon'      => Menu::TRANSLATIONS_ICON
        ];

        // Translations -> scanner unused items
        $context_array[ContextLetter::MESSAGE][] = [
            'CW_type'      => ContextLetter::MESSAGE,
            'button_title' => 'Unused items',
            'mod_label'    => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'   => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'    => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'  => Yii::t('appMenu', self::LBL_PRINT),
            'link'         => '/message/indexunused',
            'perm_key'      => 'message',
            'fa_icon'      => Menu::TRANSLATIONS_ICON
        ];

        // Translations -> run new scan
        $context_array[ContextLetter::MESSAGE][] = [
            'CW_type'      => ContextLetter::MESSAGE,
            'button_title' => 'Run Scan',
            'link'         => '/message/scanrun',
            'perm_key'      => 'message',
            'fa_icon'      => Menu::TRANSLATIONS_ICON
        ];

        // Translations -> scanner new items
        $context_array[ContextLetter::MESSAGE][] = [
            'CW_type'      => ContextLetter::MESSAGE,
            'button_title' => 'New items',
            'new_label'    => Yii::t('appMenu', self::LBL_NEW),
            'link'         => '/message/scannew',
            'create'       => '/message/create',
            'blacklist'    => '/message/blacklist',
            'perm_key'      => 'message',
            'fa_icon'      => Menu::TRANSLATIONS_ICON
        ];

        // Translations -> scanner blacklisted items
        $context_array[ContextLetter::MESSAGE][] = [
            'CW_type'      => ContextLetter::MESSAGE,
            'button_title' => 'Blacklisted items',
            'link'         => '/message/scanblacklisted',
            'whitelist'    => '/message/whitelist',
            'perm_key'      => 'message',
            'fa_icon'      => Menu::TRANSLATIONS_ICON
        ];

        return $context_array;
    }
}

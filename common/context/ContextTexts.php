<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextTexts
 * @package common\context
 */
abstract class ContextTexts implements Context
{
    const LBL_SINGULAR_U = 'Text';
    const LBL_PLURAL_U   = 'Texts';
    const LBL_SINGULAR_L = 'text';
    const LBL_PLURAL_L   = 'texts';

    public static function getContextArray()
    {
        // TEXTS HEADER
        $context_array[ContextLetter::TEXTS] = [
            'CW_type'  => ContextLetter::TEXTS,
            'title1'   => Menu::utilitiesText(),
            'ti_icon1' => Menu::UTILITIES_ICON,
            'title2'   => Menu::textblocksText(),
            'ti_icon2' => Menu::TEXTBLOCKS_ICON
        ];

        // TEXTS first button -> Texts
        $context_array[ContextLetter::TEXTS][] = [
            'CW_type'       => ContextLetter::TEXTS,
//            'button_title'  => Menu::textblocksText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/texts/index',
            'appendCW_type' => true,
            'create'        => '/texts/create',
            'fromlib'       => '/texts/fromlibrary',
            'lib_link'      => '/texts/library',
            'perm_key'      => 'texts',
            'fa_icon'       => Menu::TEXTBLOCKS_ICON
        ];

        return $context_array;
    }
}

<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextNews
 * @package common\helpers
 */
abstract class ContextNews implements Context
{
    const LBL_SINGULAR_U = 'News';
    const LBL_PLURAL_U   = 'News';
    const LBL_SINGULAR_L = 'news';
    const LBL_PLURAL_L   = 'news';
    
     /** - */
    const LBL_TG_SINGULAR_U = 'Tag';
    const LBL_TG_PLURAL_U   = 'Tags';
    const LBL_TG_SINGULAR_L = 'tag';
    const LBL_TG_PLURAL_L   = 'tags';

    public static function getContextArray()
    {

        // News -> title/header
        $context_array[ContextLetter::NEWS] = [
            'title1'   => Menu::Company_text(),
            'ti_icon1' => Menu::UTILITIES_ICON,
            'title2'   => Menu::newsText(),
            'ti_icon2' => Menu::NEWS_ICON
        ];

        // News -> index
        $context_array[ContextLetter::NEWS][] = [
            'button_title'  => Menu::newsText(),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/news/index',
            'create'        => '/news/create',
            'perm_key'      => 'news',
            'fa_icon'       => Menu::NEWS_ICON
        ];
        
         // LEGISLATION -> index ( third button ) -> Tags
            $context_array[ContextLetter::NEWS][] = [
                'button_title'  => Yii::t('appMenu', self::LBL_TG_PLURAL_U),
                'new_label'     => Yii::t('appMenu', self::LBL_NEW),
                'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
                'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
                'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
                'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
                'link'          => '/tags/index',
                'create'        => '/tags/create',
                'appendCW_type' => true,
                'perm_key'      => 'tags',
                'fa_icon'       => 'fa fa-sitemap'
            ];

        return $context_array;
    }
}

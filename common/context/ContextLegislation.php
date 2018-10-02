<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextContacts
 * @package common\context
 */
abstract class ContextLegislation implements Context
{

    /** - */
    const LBL_L_SINGULAR_U = 'Legislation';
    const LBL_L_PLURAL_U = 'Legislations';
    const LBL_L_SINGULAR_L = 'legislation';
    const LBL_L_PLURAL_L = 'legislations';
    
    /** - */
    const LBL_LT_SINGULAR_U = 'Legislation type';
    const LBL_LT_PLURAL_U   = 'Legislation types';
    const LBL_LT_SINGULAR_L = 'legislation type';
    const LBL_LT_PLURAL_L   = 'legislation types';
    
     /** - */
    const LBL_TG_SINGULAR_U = 'Tag';
    const LBL_TG_PLURAL_U   = 'Tags';
    const LBL_TG_SINGULAR_L = 'tag';
    const LBL_TG_PLURAL_L   = 'tags';

    public static function getContextArray()
    {


        /** -> Legislation */
        // LEGISLATION HEADER
        $context_array[ContextLetter::LEGISLATION] = [
            'CW_type'  => ContextLetter::LEGISLATION,
            'title1'   => Menu::Company_text(),
            'ti_icon1' => Menu::COMPANY_ICON,
            'title2'   => Menu::Legislation_text(),
            'ti_icon2' => Menu::LEGISLATION_ICON
        ];

        // LEGISLATION first button -> Legislation

        $context_array[ContextLetter::LEGISLATION][] = [
            'CW_type'       => ContextLetter::LEGISLATION,
            'button_title'  => Yii::t('appMenu', self::LBL_L_PLURAL_U),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'print_btntext' => 'A04',
            'print_btnlink' => '/legislation/print',
            'link'          => '/legislation/index',
            'appendCW_type' => true,
            'create'        => '/legislation/create',
            'perm_key' => 'legislation/' . ContextLetter::LEGISLATION,
            'fa_icon'  => Menu::LEGISLATION_ICON
        ];
        
         // LEGISLATION -> index ( second button ) -> Types
        if(Yii::$app->user->can('team_member')){
            $context_array[ContextLetter::LEGISLATION][] = [
                'CW_type'       => ContextLetter::LEGISLATION,
                'button_title'  => Yii::t('appMenu', self::LBL_LT_PLURAL_U),
                'new_label'     => Yii::t('appMenu', self::LBL_NEW),
                'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
                'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
                'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
                'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
                'link'          => '/legislationtypes/index',
                'create'        => '/legislationtypes/create',
                'appendCW_type' => true,
                'perm_key'      => 'legislationtypes',
                'fa_icon'       => 'fa fa-sitemap'
            ];

             // LEGISLATION -> index ( third button ) -> Tags
            $context_array[ContextLetter::LEGISLATION][] = [
                'CW_type'       => ContextLetter::LEGISLATION,
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
        }



        return $context_array;
    }

}

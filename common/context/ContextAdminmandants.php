<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextMandants
 * @package common\context
 */
abstract class ContextAdminmandants implements Context
{
    public static function getContextArray()
    {
        // Mandants -> title/header
        $context_array[ContextLetter::MANDANTS] = [
            'title1'    => Menu::adminText(),
            'ti_icon1'  => Menu::ADMIN_ICON,
            'title2'    => 'Mandants ' . Menu::dataText(),
            'ti_icon2'  => Menu::DATA_ICON
        ];

        // Mandants -> index
        $context_array[ContextLetter::MANDANTS][] = [
            'new_label'     => Yii::t('appMenu', 'New company'),
            'mod_label'     => Yii::t('appMenu', 'Modify company data'),
            'view_label'    => Yii::t('appMenu', 'View company data'),
            'print_label'   => Yii::t('appMenu', 'Print company data'),
            'del_label'     => Yii::t('appMenu', 'Delete company data'),
//            'print_btntext' => 'A01',
//            'print_btnlink' => '/mandants/printall',
            'link'          => '/adminmandants/index',
            'create'        => '/adminmandants/create',
            'perm_key'      => 'mandants',
        ];

        return $context_array;
    }
}

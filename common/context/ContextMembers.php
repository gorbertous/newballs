<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class ContextMandants
 * @package common\context
 */
abstract class ContextMembers implements Context
{
    public static function getContextArray()
    {
        // CLUBS -> title/header
        $context_array[ContextLetter::MEMBERS] = [
            'title1'    => Menu::membersText(),
            'ti_icon1'  => Menu::MEMBERS_ICON,
            'title2'    => 'Members ' . Menu::dataText(),
            'ti_icon2'  => Menu::DATA_ICON
        ];

        // CLUBS -> index
        $context_array[ContextLetter::MEMBERS][] = [
            'new_label'     => Yii::t('appMenu', 'New member'),
            'mod_label'     => Yii::t('appMenu', 'Modify member data'),
            'view_label'    => Yii::t('appMenu', 'View member data'),
            'print_label'   => Yii::t('appMenu', 'Print member data'),
            'del_label'     => Yii::t('appMenu', 'Delete member data'),
            'link'          => '/members/index',
            'create'        => '/members/create',
            'perm_key'      => 'members',
        ];

        return $context_array;
    }
}

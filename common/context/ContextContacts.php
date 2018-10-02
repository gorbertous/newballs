<?php

namespace common\context;

use Yii;
use common\dictionaries\ContextLetter;
use common\dictionaries\MenuTypes as Menu;
use common\helpers\Helpers;

/**
 * Class ContextContacts
 * @package common\context
 */
abstract class ContextContacts implements Context
{
    // ContextLetter::CONTACTS
    const LBL_C_SINGULAR_U  = 'Contact person';
    const LBL_C_PLURAL_U    = 'Contact persons';
    const LBL_C_SINGULAR_L  = 'contact person';
    const LBL_C_PLURAL_L    = 'contact persons';
    /** - */
    const LBL_CC_SINGULAR_U = 'HSE Organisation';
    const LBL_CC_PLURAL_U   = 'HSE Organisations';
    const LBL_CC_SINGULAR_L = 'HSE organisation';
    const LBL_CC_PLURAL_L   = 'HSE organisation';

    // ContextLetter::WORKPLACES
    const LBL_P_SINGULAR_U  = 'Contact person';
    const LBL_P_PLURAL_U    = 'Contact persons';
    const LBL_P_SINGULAR_L  = 'contact person';
    const LBL_P_PLURAL_L    = 'contact persons';
    /** - */
    const LBL_PC_SINGULAR_U = 'Workplace';
    const LBL_PC_PLURAL_U   = 'Workplaces';
    const LBL_PC_SINGULAR_L = 'workplace';
    const LBL_PC_PLURAL_L   = 'workplaces';

    // ContextLetter::EMPLOYER
    const LBL_E_SINGULAR_U  = 'Contact person';
    const LBL_E_PLURAL_U    = 'Contact persons';
    const LBL_E_SINGULAR_L  = 'contact person';
    const LBL_E_PLURAL_L    = 'contact persons';
    /** - */
    const LBL_EC_SINGULAR_U = 'Employer';
    const LBL_EC_PLURAL_U   = 'Employers';
    const LBL_EC_SINGULAR_L = 'employer';
    const LBL_EC_PLURAL_L   = 'employers';

    // ContextLetter::SUPPLIERS
    const LBL_S_SINGULAR_U  = 'Contact person';
    const LBL_S_PLURAL_U    = 'Contact persons';
    const LBL_S_SINGULAR_L  = 'contact person';
    const LBL_S_PLURAL_L    = 'contact persons';
    /** - */
    const LBL_SC_SINGULAR_U = 'Supplier';
    const LBL_SC_PLURAL_U   = 'Suppliers';
    const LBL_SC_SINGULAR_L = 'supplier';
    const LBL_SC_PLURAL_L   = 'suppliers';

    // ContextLetter::WORKERS
    const LBL_W_SINGULAR_U = 'Worker';
    const LBL_W_PLURAL_U   = 'Workers';
    const LBL_W_SINGULAR_L = 'worker';
    const LBL_W_PLURAL_L   = 'workers';
    /** - */
    const LBL_WWP_SINGULAR_U = 'Work permit';
    const LBL_WWP_PLURAL_U   = 'Work permits';
    const LBL_WWP_SINGULAR_L = 'work permit';
    const LBL_WWP_PLURAL_L   = 'work permits';
    /** - */
    const LBL_WF_SINGULAR_U  = 'Function';
    const LBL_WF_PLURAL_U    = 'Functions';
    const LBL_WF_SINGULAR_L  = 'function';
    const LBL_WF_PLURAL_L    = 'functions';

    public static function getContextArray()
    {
        /** -> HSE Contacts */

        // HSE CONTACTS HEADER
        $context_array[ContextLetter::CONTACTS] = [
            'CW_type'  => ContextLetter::CONTACTS,
            'title1'   => Menu::Company_text(),
            'ti_icon1' => Menu::COMPANY_ICON,
            'title2'   => Menu::HSEcontacts_text(),
            'ti_icon2' => Menu::HSE_CONTACTS_ICON
        ];

        // HSE CONTACTS first button -> Contacts
        $context_array[ContextLetter::CONTACTS][] = [
            'CW_type'       => ContextLetter::CONTACTS,
            'button_title'  => Yii::t('appMenu', self::LBL_C_PLURAL_U),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'print_btntext' => 'A03',
            'print_btnlink' => '/contacts/print',
            'link'          => '/contacts/index',
            'appendCW_type' => true,
            'create'        => '/contacts/create',
            'fromlib'       => '/contacts/fromlibrary',
            'lib_link'      => '/contacts/library',
            'perm_key'      => 'contacts/' . ContextLetter::CONTACTS,
            'fa_icon'       => Menu::HSE_CONTACTS_ICON
        ];

        // HSE CONTACTS second button -> Companies
        $context_array[ContextLetter::CONTACTS][] = [
            'CW_type'       => ContextLetter::CONTACTS,
            'button_title'  => Yii::t('appMenu', self::LBL_CC_PLURAL_U),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/company/index',
            'appendCW_type' => true,
            'create'        => '/company/create',
            'fromlib'       => '/company/fromlibrary',
            'lib_link'      => '/company/library',
            'perm_key'      => 'company/' . ContextLetter::CONTACTS,
            'fa_icon'       => 'fa fa-sitemap'
        ];

        /** -> Workplaces */

        // WORKPLACES HEADER
        $context_array[ContextLetter::WORKPLACES] = [
            'CW_type'  => ContextLetter::WORKPLACES,
            'title1'   => Menu::Company_text(),
            'ti_icon1' => Menu::COMPANY_ICON,
            'title2'   => Menu::Workplaces_text(),
            'ti_icon2' => Menu::WORKPLACES_ICON
        ];

        // WORKPLACES first button -> Workplaces
        if(Helpers::getConfig('Workplaces', 'ShowContacts ', Yii::$app->session->get('mandant_id'))){
            $context_array[ContextLetter::WORKPLACES][] = [
                'CW_type'       => ContextLetter::WORKPLACES,
                'button_title'  => Yii::t('appMenu', self::LBL_P_PLURAL_U),
                'new_label'     => Yii::t('appMenu', self::LBL_NEW),
                'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
                'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
                'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
                'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
                'print_btntext' => 'A04',
                'print_btnlink' => '/workplaces/print',
                'link'          => '/workplaces/index',
                'appendCW_type' => true,
                'create'        => '/workplaces/create',
                'fromlib'       => '/workplaces/fromlibrary',
                'lib_link'      => '/workplaces/library',
                'perm_key'      => 'contacts/' . ContextLetter::CONTACTS,
                'fa_icon'       => Menu::WORKPLACES_ICON
            ];
        }

        // WORKPLACES second button -> Companies
        $context_array[ContextLetter::WORKPLACES][] = [
            'CW_type'       => ContextLetter::WORKPLACES,
            'button_title'  =>Yii::t('appMenu', self::LBL_PC_PLURAL_U),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/company/index',
            'appendCW_type' => true,
            'create'        => '/company/create',
            'fromlib'       => '/company/fromlibrary',
            'lib_link'      => '/company/library',
            'perm_key'      => 'company/' . ContextLetter::WORKPLACES,
            'fa_icon'       => 'fa fa-sitemap'
        ];
        
        // WORKPLACES third button -> ToDo
        if(Helpers::getConfig('Workplaces', 'ShowContacts ', Yii::$app->session->get('mandant_id'))){
            $context_array[ContextLetter::WORKPLACES][] = [
                'CW_type'       => ContextLetter::WORKPLACES,
                'button_title'  => Menu::ToDo_text(),
                'new_label'     => '',
                'mod_label'     => '',
                'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
                'del_label'     => '',
                'appendCW_type' => true,
                'print_label'   => '',
                'link'          => '/workplaces/todos',
                'create'        => '',
                'perm_key'      => 'contacts/' . ContextLetter::WORKPLACES,
                'fa_icon'       => Menu::TODO_ICON
            ];
        }

        /** -> Employer */

        // EMPLOYER HEADER
        $context_array[ContextLetter::EMPLOYERS] = [
            'CW_type'  => ContextLetter::EMPLOYERS,
            'title1'   => Menu::Company_text(),
            'ti_icon1' => Menu::COMPANY_ICON,
            'title2'   => Menu::Employers_text(),
            'ti_icon2' => Menu::EMPLOYERS_ICON
        ];

        // EMPLOYER first button -> Employers
        if(Helpers::getConfig('Employers', 'ShowContacts ', Yii::$app->session->get('mandant_id'))){
            $context_array[ContextLetter::EMPLOYERS][] = [
                'CW_type'       => ContextLetter::EMPLOYERS,
                'button_title'  =>Yii::t('appMenu', self::LBL_E_PLURAL_U),
                'new_label'     => Yii::t('appMenu', self::LBL_NEW),
                'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
                'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
                'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
                'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
                'print_btntext' => 'A03',
                'print_btnlink' => '/employers/print',
                'link'          => '/employers/index',
                'appendCW_type' => true,
                'create'        => '/employers/create',
                'fromlib'       => '/employers/fromlibrary',
                'lib_link'      => '/employers/library',
                'perm_key'      => 'contacts/' . ContextLetter::EMPLOYERS,
                'fa_icon'       => Menu::EMPLOYERS_ICON
            ];
        }

        // EMPLOYER second button -> Companies
        $context_array[ContextLetter::EMPLOYERS][] = [
            'CW_type'       => ContextLetter::EMPLOYERS,
            'button_title'  =>Yii::t('appMenu', self::LBL_EC_PLURAL_U),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/company/index',
            'appendCW_type' => true,
            'create'        => '/company/create',
            //'fromlib'       => '/company/fromlibrary',
            //'lib_link'      => '/company/library',
            'perm_key'      => 'company/' . ContextLetter::EMPLOYERS,
            'fa_icon'       => 'fa fa-sitemap'
        ];

        /** -> Supplier */

        // SUPPLIER HEADER
        $context_array[ContextLetter::SUPPLIERS] = [
            'CW_type'  => ContextLetter::SUPPLIERS,
            'title1'   => Menu::Company_text(),
            'ti_icon1' => Menu::COMPANY_ICON,
            'title2'   => Menu::Suppliers_text(),
            'ti_icon2' => Menu::SUPPLIERS_ICON
        ];

        // SUPPLIER first button -> Suppliers
        $context_array[ContextLetter::SUPPLIERS][] = [
            'CW_type'       => ContextLetter::SUPPLIERS,
            'button_title'  =>Yii::t('appMenu', self::LBL_S_PLURAL_U),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'print_btntext' => 'A05',
            'print_btnlink' => '/suppliers/print',
            'link'          => '/suppliers/index',
            'appendCW_type' => true,
            'create'        => '/suppliers/create',
            'fromlib'       => '/suppliers/fromlibrary',
            'lib_link'      => '/suppliers/library',
            'perm_key'      => 'contacts/' . ContextLetter::SUPPLIERS,
            'fa_icon'       => Menu::SUPPLIERS_ICON
        ];

        // SUPPLIER second button -> Companies
        $context_array[ContextLetter::SUPPLIERS][] = [
            'CW_type'       => ContextLetter::SUPPLIERS,
            'button_title'  =>Yii::t('appMenu', self::LBL_SC_PLURAL_U),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'link'          => '/company/index',
            'appendCW_type' => true,
            'create'        => '/company/create',
            'fromlib'       => '/company/fromlibrary',
            'lib_link'      => '/company/library',
            'perm_key'      => 'company/' . ContextLetter::SUPPLIERS,
            'fa_icon'       => 'fa fa-sitemap'
        ];

        /** -> Workers */

        // WORKERS HEADER
        $context_array[ContextLetter::WORKERS] = [
            'CW_type'  => ContextLetter::WORKERS,
            'title1'   => Menu::HumanResources_text(),
            'ti_icon1' => Menu::HUMANRESOURCES_ICON,
            'title2'   => Menu::Workers_text(),
            'ti_icon2' => Menu::WORKERS_ICON
        ];

        // WORKER first button -> Workers
        $context_array[ContextLetter::WORKERS][] = [
            'CW_type'       => ContextLetter::WORKERS,
            'button_title'  => Yii::t('appMenu', self::LBL_W_PLURAL_U),
            'new_label'     => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'     => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'   => Yii::t('appMenu', self::LBL_PRINT),
            'print_btntext' => 'B02',
            'print_btnlink' => '/workers/print',
            'link'          => '/workers/index',
            'appendCW_type' => true,
            'create'        => '/workers/create',
            'perm_key'      => 'contacts/' . ContextLetter::WORKERS,
            'fa_icon'       => Menu::WORKERS_ICON
        ];

        // WORKER second button -> Functions
        $context_array[ContextLetter::WORKERS][] = [
            'CW_type'      => ContextLetter::WORKERS,
            'button_title' => Yii::t('appMenu', self::LBL_WF_PLURAL_U),
            'new_label'    => Yii::t('appMenu', self::LBL_NEW),
            'mod_label'    => Yii::t('appMenu', self::LBL_MODIFY),
            'view_label'   => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'    => Yii::t('appMenu', self::LBL_DELETE),
            'print_label'  => Yii::t('appMenu', self::LBL_PRINT),
            'link'         => '/contacttypes/index',
            'appendCW_type' => true,
            'create'       => '/contacttypes/create',
            'fromlib'      => '/contacttypes/fromlibrary',
            'lib_link'     => '/contacttypes/library',
            'perm_key'      => 'contacttypes',
            'fa_icon'      => 'fa fa-sitemap'
        ];

        // WORKER third button -> ToDo
        $context_array[ContextLetter::WORKERS][] = [
            'CW_type'       => ContextLetter::WORKERS,
            'button_title'  => Menu::ToDo_text(),
            'new_label'     => '',
            'mod_label'     => '',
            'view_label'    => Yii::t('appMenu', self::LBL_VIEW),
            'del_label'     => '',
            'print_label'   => '',
            'link'          => '/workers/todos',
            'appendCW_type' => true,
            'create'        => '',
            'perm_key'      => 'contacts/' . ContextLetter::WORKERS,
            'fa_icon'       => Menu::TODO_ICON
        ];
        
        return $context_array;
    }
}

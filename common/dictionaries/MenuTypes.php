<?php

namespace common\dictionaries;

use Yii;

/**
 * Class MenuTypes
 * @package common\dictionaries
 */
abstract class MenuTypes
{
    use TraitDictionaries;

    public static function Dashboard_text(): string {
        return Yii::t('appMenu', 'Dashboard');
    }
    const DASHBOARD_ICON = 'fa fa-dashboard';
    const DASHBOARD_ICON_MENU = 'dashboard';

    /** -- */

    public static function logText(): string {
        return Yii::t('appMenu', 'Logs');
    }
    const LOGS_ICON = 'fa fa-bolt';
    const LOGS_ICON_MENU = 'bolt';

    /** -- */

    public static function Accidents_text(): string {
        return Yii::t('appMenu', 'Accidents');
    }
    const ACCIDENTS_ICON = 'fa fa-ambulance';
    const ACCIDENTS_ICON_MENU = 'ambulance';

    /** -- */

    public static function Company_text(): string {
        if (!empty(Yii::$app->session) && Yii::$app->session->get('ispublic')) {
            return Yii::t('appMenu', 'Commune');
        } else {
            return Yii::t('appMenu', 'Company');
        }
    }
    const COMPANY_ICON = 'fa fa-industry';
    const COMPANY_ICON_MENU = 'industry';

    /** -- */

    public static function dataText(): string {
        return Yii::t('appMenu', 'Data');
    }
    const DATA_ICON = 'fa fa-file-text';
    const DATA_ICON_MENU = 'file-text';

    /** -- */

    public static function Documents_text(): string {
        return Yii::t('appMenu', 'Documents');
    }
    const DOCUMENTS_ICON = 'fa fa-certificate';
    const DOCUMENTS_ICON_MENU = 'certificate';

    /** -- */

    public static function Documenttypes_text(): string {
        return Yii::t('appMenu', 'Document types');
    }
    const DOCUMENTTYPES_ICON = 'fa fa-sitemap';
    const DOCUMENTTYPES_ICON_MENU = 'sitemap';

    /** -- */

    public static function HSEcontacts_text(): string {
        return Yii::t('appMenu', 'HSE contacts');
    }
    const HSE_CONTACTS_ICON = 'fa fa-user';
    const HSE_CONTACTS_ICON_MENU = 'user';

    /** -- */

    public static function membersText(): string {
        return Yii::t('appMenu', 'Members');
    }
    const MEMBERS_ICON = 'fa fa-user-plus';
    const MEMBERS_ICON_MENU = 'user-plus';

    /** -- */

    public static function Workplaces_text(): string {
        return Yii::t('appMenu', 'Buildings');
    }
    const WORKPLACES_ICON = 'fa fa-map-marker';
    const WORKPLACES_ICON_MENU = 'map-marker';

    /** -- */

    public static function Suppliers_text(): string {
        return Yii::t('appMenu', 'Suppliers');
    }
    const SUPPLIERS_ICON = 'fa fa-truck';
    const SUPPLIRS_ICON_MENU = 'truck';

    /** -- */

    public static function clubsText(): string {
        return Yii::t('appMenu', 'Clubs');
    }
    const CLUBS_ICON = 'fa fa-industry';
    const CLUBS_ICON_MENU = 'industry';

    /** -- */

    public static function Workers_text(): string {
        return Yii::t('appMenu', 'Workers');
    }
    const WORKERS_ICON = 'fa fa-user';
    const WORKERS_ICON_MENU = 'user';

    /** -- */

    public static function Medicalcare_text(): string {
        return Yii::t('appMenu', 'Medical care');
    }
    const MEDICALCARE_ICON = 'fa fa-stethoscope';
    const MEDICALCARE_ICON_MENU = 'stethoscope';

    /** -- */

    public static function Postings_text(): string {
        return Yii::t('appMenu', 'Postings');
    }
    const POSTINGS_ICON = 'fa fa-briefcase';
    const POSTINGS_ICON_MENU = 'briefcase';

    /** -- */

    public static function Trainings_text(): string {
        return Yii::t('appMenu', 'Trainings');
    }
    const TRAININGS_ICON = 'fa fa-graduation-cap';
    const TRAININGS_ICON_MENU = 'graduation-cap';

    /** -- */

    public static function Authorizations_text(): string {
        return Yii::t('appMenu', 'Authorizations');
    }
    const AUTHORIZATIONS_ICON = 'fa fa-minus-circle';
    const AUTHORIZATIONS_ICON_MENU = 'minus-circle';

    /** -- */

    public static function Absences_text(): string {
        return Yii::t('appMenu', 'Leaves');
    }
    const ABSENCES_ICON = 'fa fa-medkit';
    const ABSENCES_ICON_MENU = 'medkit';

    /** -- */

    public static function SafetyAndHealth_text(): string {
        return Yii::t('appMenu', 'Safety and health');
    }
    const SAFETY_AND_HEALTH_ICON = 'fa fa-warning';
    const SAFETY_AND_HEALTH_ICON_MENU = 'warning';

    /** -- */

    public static function Workunits_text(): string {
        return Yii::t('appMenu', 'Workunits');
    }
    const WORKUNITS_ICON = 'fa fa-wrench';
    const WORKUNITS_ICON_MENU = 'wrench';

  
    /** -- */

    public static function RiskAssessment_text(): string {
        return Yii::t('appMenu', 'Risk assessment');
    }
    const RISKASSESSMENT_ICON = 'fa fa-heartbeat';
    const RISKASSESSMENT_ICON_MENU = 'heartbeat';

    /** -- */

    public static function ActivityReport_text(): string {
        return Yii::t('appMenu', 'Activity report');
    }
    const ACTIVITY_REPORT_ICON = 'fa fa-history';
    const ACTIVITY_REPORT_ICON_MENU = 'history';
    
    /** -- */

    public static function SafetyRegister_text(): string {
        return Yii::t('appMenu', 'Safety Register');
    }
    const SAFETYREGISTER_ICON = 'fa fa-book';
    const SAFETYREGISTER_ICON_MENU = 'book';

    /** -- */

    public static function TechnicalInstallations_text(): string {
        return Yii::t('appMenu', 'Technical installations');
    }
    const TECHNICALINSTALLATIONS_ICON = 'fa fa-building';
    const TECHNICALINSTALLATIONS_ICON_MENU = 'building';

    /** -- */

    public static function WorkEquipment_text(): string {
        return Yii::t('appMenu', 'Work equipment');
    }
    const WORK_EQUIPMENT_ICON = 'fa fa-cog';
    const WORK_EQUIPMENT_ICON_MENU = 'cog';

    /** -- */

    public static function HandlingEquipment_text(): string {
        return Yii::t('appMenu', 'Handling equipment');
    }
    const HANDLING_EQUIPMENT_ICON = 'fa fa-thumbs-up';
    const HANDLING_EQUIPMENT_ICON_MENU = 'thumbs-up';

    /** -- */

    public static function Transport_text(): string {
        return Yii::t('appMenu', 'Transport');
    }
    const TRANSPORT_ICON = 'fa fa-truck';
    const TRANSPORT_ICON_MENU = 'truck';

    /** -- */

    public static function FireEquipment_text(): string {
        return Yii::t('appMenu', 'Fire equipment');
    }
    const FIRE_EQUIPMENT_ICON = 'fa fa-fire';
    const FIRE_EQUIPMENT_ICON_MENU = 'fire';

    /** -- */

    public static function FirstAid_text(): string {
        return Yii::t('appMenu', 'First aid');
    }
    const FIRSTAID_ICON = 'fa fa-medkit';
    const FIRSTAID_ICON_MENU = 'medkit';

    /** -- */

    public static function getChemicalsText(): string {
        return Yii::t('appMenu', 'Chemicals');
    }
    const CHEMICALS_ICON = 'fa fa-flask';
    const CHEMICALS_ICON_MENU = 'flask';

    /** -- */

    public static function Ppe_text(): string {
        return Yii::t('appMenu', 'Ppe');
    }
    const PPE_ICON = 'fa fa-shield';
    const PPE_ICON_MENU = 'shield';

    /** -- */

    public static function utilitiessText(): string {
        return Yii::t('appMenu', 'Configuration');
    }
    const UTILITIES_ICON = 'fa fa-desktop';
    const UTILITIES_ICON_MENU = 'desktop';

    /** -- */

    public static function translationsText(): string {
        return Yii::t('appMenu', 'Translations');
    }
    const TRANSLATIONS_ICON = 'fa fa-language';
    const TRANSLATIONS_ICON_MENU = 'language';

    /** -- */

    public static function usersText(): string {
        return Yii::t('appMenu', 'Users');
    }
    const USERS_ICON = 'fa fa-user';
    const USERS_ICON_MENU = 'user';

    /** -- */

    public static function textblocksText(): string {
        return Yii::t('appMenu', 'Textblocks');
    }
    const TEXTBLOCKS_ICON = 'fa fa-align-left';
    const TEXTBLOCKS_ICON_MENU = 'align-left';

    /** -- */

    public static function utilitiesText(): string {
        return Yii::t('appMenu', 'Utilities');
    }
    const IMPORT_ICON = 'fa fa-upload';
    const IMPORT_ICON_MENU = 'upload';

    /** -- */

    public static function Update_text(): string {
        return Yii::t('appMenu', 'Update');
    }
    const UPDATE_ICON = 'fa fa-pencil';
    const UPDATE_ICON_MENU = 'pencil';

    /** -- */

    public static function playdatesText(): string {
        return Yii::t('modelattr', 'Play Dates');
    }
    const PLAYDATES_ICON = 'fa fa-cogs';
    const PLAYDATES_ICON_MENU = 'cogs';

   
    /** -- */

    public static function Actual_text(): string {
        return Yii::t('appMenu', 'Actual status');
    }
    const ACTUAL_ICON = 'fa fa-clock-o';
    const ACTUAL_ICON_MENU = 'clock-o';

    /** -- */

    public static function History_text(): string {
        return Yii::t('appMenu', 'History');
    }
    const HISTORY_ICON = 'fa fa-history';
    const HISTORY_ICON_MENU = 'history';

    /** -- */

    public static function ToDo_text(): string {
        return Yii::t('appMenu', 'ToDo');
    }
    const TODO_ICON = 'fa fa-list-ol';
    const TODO_ICON_MENU = 'list-ol';

    /** -- */

    public static function adminText(): string {
        return Yii::t('appMenu', 'Administration');
    }
    const ADMIN_ICON = 'fa fa-lock';
    const ADMIN_ICON_MENU = 'lock';
    
     /** -- */

    public static function adminusersText(): string {
        return Yii::t('appMenu', 'Users');
    }
    const ADMINRBAC_ICON = 'fa fa-users';
    const ADMINRBAC_ICON_MENU = 'users';
    
    /** -- */
    
    public static function adminauthitemText(): string {
        return Yii::t('appMenu', 'Roles');
    }
    const ADMINAUTHITEM_ICON = 'fa fa-arrow-right';
    const ADMINAUTHITEM_ICON_MENU = 'arrow-right';
    
     public static function adminauthitempText(): string {
        return Yii::t('appMenu', 'Permissions');
    }
    const ADMINAUTHITEMP_ICON = 'fa fa-arrow-right';
    const ADMINAUTHITEMP_ICON_MENU = 'arrow-right';
    
    /** -- */
    
    public static function Adminauthitemchild_text(): string {
        return Yii::t('appMenu', 'Roles Parent/Child');
    }
    const ADMINAUTHITEMCHILD_ICON = 'fa fa-arrow-right';
    const ADMINAUTHITEMCHILD_ICON_MENU = 'arrow-right';
   
   
    
    public static function Legislation_text(): string {
        return Yii::t('appMenu', 'Legislation');
    }
    const LEGISLATION_ICON = 'fa fa-gavel';
    const LEGISLATION_ICON_MENU = 'gavel';

    /** -- */

    public static function newsText(): string {
        return Yii::t('appMenu', 'News');
    }
    const NEWS_ICON = 'fa fa-newspaper-o';
    const NEWS_ICON_MENU = 'newspaper-o';

    /** -- */

    public static function Documentation_text(): string {
        return Yii::t('appMenu', 'Documentation');
    }
    const DOCUMENTATION_ICON = 'fa fa-file-o';
    const DOCUMENTATION_ICON_MENU = 'file-o';
  
}

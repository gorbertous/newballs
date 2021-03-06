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

    public static function memrotaText(): string {
        return Yii::t('appMenu', 'Rota');
    }
    const MEMROTA_ICON = 'fas fa-dashboard';
    const MEMROTA_ICON_MENU = 'dashboard';

    /** -- */

    public static function logText(): string {
        return Yii::t('appMenu', 'Logs');
    }
    const LOGS_ICON = 'fas fa-bolt';
    const LOGS_ICON_MENU = 'bolt';

    /** -- */

    public static function rulesText(): string {
        return Yii::t('appMenu', 'Rules');
    }
    const RULES_ICON = 'fas fa-balance-scale';
    const RULES_ICON_MENU = 'balance-scale';

    /** -- */

    public static function Company_text(): string {
        if (!empty(Yii::$app->session) && Yii::$app->session->get('ispublic')) {
            return Yii::t('appMenu', 'Commune');
        } else {
            return Yii::t('appMenu', 'Company');
        }
    }
    const COMPANY_ICON = 'fas fa-industry';
    const COMPANY_ICON_MENU = 'industry';

    /** -- */

    public static function dataText(): string {
        return Yii::t('appMenu', 'Data');
    }
    const DATA_ICON = 'fas fa-file-text';
    const DATA_ICON_MENU = 'file-text';

    /** -- */

    public static function Documents_text(): string {
        return Yii::t('appMenu', 'Documents');
    }
    const DOCUMENTS_ICON = 'fas fa-certificate';
    const DOCUMENTS_ICON_MENU = 'certificate';

    /** -- */

    public static function Documenttypes_text(): string {
        return Yii::t('appMenu', 'Document types');
    }
    const DOCUMENTTYPES_ICON = 'fas fa-sitemap';
    const DOCUMENTTYPES_ICON_MENU = 'sitemap';

    /** -- */

    public static function userLogText(): string {
        return Yii::t('appMenu', 'User Log');
    }
    const USER_LOG_ICON = 'fas fa-user';
    const USER_LOG_ICON_MENU = 'user';

    /** -- */

    public static function membersText(): string {
        return Yii::t('appMenu', 'Membership');
    }
    const MEMBERS_ICON = 'fas fa-user-plus';
    const MEMBERS_ICON_MENU = 'user-plus';

    /** -- */

    public static function Workplaces_text(): string {
        return Yii::t('appMenu', 'Buildings');
    }
    const WORKPLACES_ICON = 'fas fa-map-marker';
    const WORKPLACES_ICON_MENU = 'map-marker';

    /** -- */

    public static function Suppliers_text(): string {
        return Yii::t('appMenu', 'Suppliers');
    }
    const SUPPLIERS_ICON = 'fas fa-truck';
    const SUPPLIRS_ICON_MENU = 'truck';

    /** -- */

    public static function clubsText(): string {
        return Yii::t('appMenu', 'Clubs');
    }
    const CLUBS_ICON = 'fas fa-industry';
    const CLUBS_ICON_MENU = 'industry';

    /** -- */
    
    public static function clubText(): string {
        return Yii::t('appMenu', 'Club');
    }
    const CLUB_ICON = 'fas fa-industry';
    const CLUB_ICON_MENU = 'industry';

    /** -- */

    public static function Workers_text(): string {
        return Yii::t('appMenu', 'Workers');
    }
    const WORKERS_ICON = 'fas fa-user';
    const WORKERS_ICON_MENU = 'user';

    /** -- */

    public static function Medicalcare_text(): string {
        return Yii::t('appMenu', 'Medical care');
    }
    const MEDICALCARE_ICON = 'fas fa-stethoscope';
    const MEDICALCARE_ICON_MENU = 'stethoscope';

    /** -- */

    public static function Postings_text(): string {
        return Yii::t('appMenu', 'Postings');
    }
    const POSTINGS_ICON = 'fas fa-briefcase';
    const POSTINGS_ICON_MENU = 'briefcase';

    /** -- */

    public static function Trainings_text(): string {
        return Yii::t('appMenu', 'Trainings');
    }
    const TRAININGS_ICON = 'fas fa-graduation-cap';
    const TRAININGS_ICON_MENU = 'graduation-cap';

    /** -- */

    public static function Authorizations_text(): string {
        return Yii::t('appMenu', 'Authorizations');
    }
    const AUTHORIZATIONS_ICON = 'fas fa-minus-circle';
    const AUTHORIZATIONS_ICON_MENU = 'minus-circle';

    /** -- */

    public static function Absences_text(): string {
        return Yii::t('appMenu', 'Leaves');
    }
    const ABSENCES_ICON = 'fas fa-medkit';
    const ABSENCES_ICON_MENU = 'medkit';

    /** -- */

    public static function SafetyAndHealth_text(): string {
        return Yii::t('appMenu', 'Safety and health');
    }
    const SAFETY_AND_HEALTH_ICON = 'fas fa-warning';
    const SAFETY_AND_HEALTH_ICON_MENU = 'warning';

    /** -- */

    public static function Workunits_text(): string {
        return Yii::t('appMenu', 'Workunits');
    }
    const WORKUNITS_ICON = 'fas fa-wrench';
    const WORKUNITS_ICON_MENU = 'wrench';

  
    /** -- */

    public static function RiskAssessment_text(): string {
        return Yii::t('appMenu', 'Risk assessment');
    }
    const RISKASSESSMENT_ICON = 'fas fa-heartbeat';
    const RISKASSESSMENT_ICON_MENU = 'heartbeat';

    /** -- */

    public static function ActivityReport_text(): string {
        return Yii::t('appMenu', 'Activity report');
    }
    const ACTIVITY_REPORT_ICON = 'fas fa-history';
    const ACTIVITY_REPORT_ICON_MENU = 'history';
    
    /** -- */

    public static function memtypeText(): string {
        return Yii::t('appMenu', 'Membership Types');
    }
    const MEMTYPE_ICON = 'fas fa-book';
    const MEMTYPE_ICON_MENU = 'book';

    /** -- */

    public static function locationsText(): string {
        return Yii::t('appMenu', 'Locations');
    }
    const LOCATIONS_ICON = 'fas fa-building';
    const LOCATIONS_ICON_MENU = 'building';

    /** -- */

    public static function photosText(): string {
        return Yii::t('appMenu', 'Photos');
    }
    const PHOTOS_ICON = 'far fa-image';
    const PHOTOS_ICON_MENU = 'image';

    /** -- */

    public static function yourGamesText(): string {
        return Yii::t('appMenu', 'Your Games');
    }
    const Y_GAMES_ICON = 'fas fa-thumbs-up';
    const Y_GAMES_ICON_MENU = 'thumbs-up';

    /** -- */

    public static function Transport_text(): string {
        return Yii::t('appMenu', 'Transport');
    }
    const TRANSPORT_ICON = 'fas fa-truck';
    const TRANSPORT_ICON_MENU = 'truck';

    /** -- */

    public static function FireEquipment_text(): string {
        return Yii::t('appMenu', 'Fire equipment');
    }
    const FIRE_EQUIPMENT_ICON = 'fas fa-fire';
    const FIRE_EQUIPMENT_ICON_MENU = 'fire';

    /** -- */

    public static function FirstAid_text(): string {
        return Yii::t('appMenu', 'First aid');
    }
    const FIRSTAID_ICON = 'fas fa-medkit';
    const FIRSTAID_ICON_MENU = 'medkit';

    /** -- */

    public static function getChemicalsText(): string {
        return Yii::t('appMenu', 'Chemicals');
    }
    const CHEMICALS_ICON = 'fas fa-flask';
    const CHEMICALS_ICON_MENU = 'flask';

    /** -- */

    public static function feesText(): string {
        return Yii::t('appMenu', 'Fees');
    }
    const FEES_ICON = 'fas fa-shield';
    const FEES_ICON_MENU = 'shield';

    /** -- */

    public static function utilitiessText(): string {
        return Yii::t('appMenu', 'Configuration');
    }
    const UTILITIES_ICON = 'fas fa-desktop';
    const UTILITIES_ICON_MENU = 'desktop';

    /** -- */

    public static function translationsText(): string {
        return Yii::t('appMenu', 'Translations');
    }
    const TRANSLATIONS_ICON = 'fas fa-language';
    const TRANSLATIONS_ICON_MENU = 'language';

    /** -- */

    public static function usersText(): string {
        return Yii::t('appMenu', 'Users');
    }
    const USERS_ICON = 'fas fa-user';
    const USERS_ICON_MENU = 'user';

    /** -- */

    public static function textblocksText(): string {
        return Yii::t('appMenu', 'Textblocks');
    }
    const TEXTBLOCKS_ICON = 'fas fa-align-left';
    const TEXTBLOCKS_ICON_MENU = 'align-left';

    /** -- */

    public static function utilitiesText(): string {
        return Yii::t('appMenu', 'Utilities');
    }
    const IMPORT_ICON = 'fas fa-upload';
    const IMPORT_ICON_MENU = 'upload';

    /** -- */

    public static function rotaText(): string {
        return Yii::t('appMenu', 'Rota');
    }
    const ROTA_ICON = 'fas fa-tachometer-alt';
    const ROTA_ICON_MENU = 'tachometer-alt';

    /** -- */

    public static function playdatesText(): string {
        return Yii::t('appMenu', 'Manage Rota');
    }
    const PLAYDATES_ICON = 'fas fa-cogs';
    const PLAYDATES_ICON_MENU = 'cogs';
    
     /** -- */

    public static function playdatesText2(): string {
        return Yii::t('appMenu', 'Publish Dates');
    }
    const PLAYDATES2_ICON = 'fas fa-cogs';
    const PLAYDATES2_ICON_MENU = 'cogs';

   
    /** -- */

    public static function reservesText(): string {
        return Yii::t('appMenu', 'Reserves');
    }
    const RESERVES_ICON = 'fas fa-clock-o';
    const RESERVES_ICON_MENU = 'clock-o';

    /** -- */

    public static function scoresText(): string {
        return Yii::t('appMenu', 'Scores');
    }
    const SCORES_ICON = 'fas fa-history';
    const SCORES_ICON_MENU = 'history';

    /** -- */

    public static function ToDo_text(): string {
        return Yii::t('appMenu', 'ToDo');
    }
    const TODO_ICON = 'fas fa-list-ol';
    const TODO_ICON_MENU = 'list-ol';

    /** -- */

    public static function adminText(): string {
        return Yii::t('appMenu', 'Administration');
    }
    const ADMIN_ICON = 'fas fa-lock';
    const ADMIN_ICON_MENU = 'lock';
    
     /** -- */

    public static function adminusersText(): string {
        return Yii::t('appMenu', 'Users');
    }
    const ADMINRBAC_ICON = 'fas fa-users';
    const ADMINRBAC_ICON_MENU = 'users';
    
    /** -- */
    
    public static function adminauthitemText(): string {
        return Yii::t('appMenu', 'Roles');
    }
    const ADMINAUTHITEM_ICON = 'fas fa-arrow-right';
    const ADMINAUTHITEM_ICON_MENU = 'arrow-right';
    
     public static function adminauthitempText(): string {
        return Yii::t('appMenu', 'Permissions');
    }
    const ADMINAUTHITEMP_ICON = 'fas fa-arrow-right';
    const ADMINAUTHITEMP_ICON_MENU = 'arrow-right';
    
    /** -- */
    
    public static function Adminauthitemchild_text(): string {
        return Yii::t('appMenu', 'Roles Parent/Child');
    }
    const ADMINAUTHITEMCHILD_ICON = 'fas fa-arrow-right';
    const ADMINAUTHITEMCHILD_ICON_MENU = 'arrow-right';
   
   
    
    public static function Legislation_text(): string {
        return Yii::t('appMenu', 'Legislation');
    }
    const LEGISLATION_ICON = 'fas fa-gavel';
    const LEGISLATION_ICON_MENU = 'gavel';

    /** -- */

    public static function newsText(): string {
        return Yii::t('appMenu', 'News');
    }
    const NEWS_ICON = 'far fa-newspaper';
    const NEWS_ICON_MENU = 'newspaper';

    /** -- */

    public static function Documentation_text(): string {
        return Yii::t('appMenu', 'Documentation');
    }
    const DOCUMENTATION_ICON = 'fas fa-file-o';
    const DOCUMENTATION_ICON_MENU = 'file-o';
  
}

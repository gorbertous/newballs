<?php

namespace common\dictionaries;

use Yii;

/**
 * Class ContactTypes
 * @package common\dictionaries
 */
abstract class ContactTypes
{

    use TraitDictionaries;
    
    const Contact = 'C';
    const Worker = 'W';
    const Workplace = 'P';
    const Supplier = 'S';
    const Employer = 'E';
            
    const ShowInContactsList = 1;
    const IsaManager = 2;
    const IsNotaEmployee = 4;
    const IsactSupervisor = 8;
    
    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::Contact => Yii::t('appMenu', 'Contacts'),
            self::Worker => Menu::Workers_text(),
            self::Workplace => Menu::Workplaces_text(),
            self::Supplier => Yii::t('appMenu', 'Supplier'),
            self::Employer => Menu::Employers_text()
        ];
    }

}
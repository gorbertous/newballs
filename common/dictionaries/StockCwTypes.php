<?php

namespace common\dictionaries;

use ReflectionClass;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class StockCwTypes
 * @package common\dictionaries
 */
abstract class StockCwTypes
{
    use TraitDictionaries;
    
    const TYPE_TECHINST  = 'B';
    const TYPE_WORKEQ    = 'Q';
    const TYPE_HANDEQ    = 'M';
    const TYPE_TRANSPORT = 'T';
    const TYPE_FIREEQ    = 'F';
    const TYPE_FIRSTAID  = 'S';
    const TYPE_PPE       = 'I';
            
    /**
     * @return array
     */
    public static function all(): array
    {
        return [
            self::TYPE_TECHINST  => [Menu::TechnicalInstallations_text(), Menu::TECHNICALINSTALLATIONS_ICON],
            self::TYPE_WORKEQ    => [Menu::WorkEquipment_text(), Menu::WORK_EQUIPMENT_ICON],
            self::TYPE_HANDEQ    => [Menu::HandlingEquipment_text(), Menu::HANDLING_EQUIPMENT_ICON],
            self::TYPE_TRANSPORT => [Menu::Transport_text(), Menu::TRANSPORT_ICON],
            self::TYPE_FIREEQ    => [Menu::FireEquipment_text(), Menu::FIRE_EQUIPMENT_ICON],
            self::TYPE_FIRSTAID  => [Menu::FirstAid_text(), Menu::FIRSTAID_ICON],
            self::TYPE_PPE       => [Menu::Ppe_text(), Menu::PPE_ICON],
        ];
    }

    /**
     * Returns all the constants defined on the class
     *
     * @see http://php.net/manual/en/reflectionclass.getconstants.php
     * @return array
     * @throws \ReflectionException
     */
    public static function getConstants()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
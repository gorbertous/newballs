<?php

namespace common\dictionaries;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Trait TraitDictionaries
 * @package common\dictionaries
 */
trait TraitDictionaries
{
    /**
     * This method checks if $type is defined as a key in the array and 
     * returns its value if defined
     * returns '-' if not defined
     *
     * @param $type
     * @return string
     */
    public static function get($type)
    {
        $all = self::all();

        if (isset($all[$type])) {
            return $all[$type];
        }

        return '-';
    }

    /**
     * This method returns all the elements defined in $types array 
     *
     * @param string $table
     * @param string $field
     * @param int $mandant_id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getUsed($table, $field, $mandant_id)
    {
        $all = self::all();

        $selected = [];

        $sql = <<<SQL
        SELECT DISTINCT {$field} FROM {$table} WHERE ID_Mandant = :mandant_id;
SQL;

        $used = ArrayHelper::getColumn(Yii::$app->db->createCommand($sql, [
            ':mandant_id' => $mandant_id
        ])->queryAll(), $field);

        foreach ($used as $type) {
            if (isset($all[$type])) {
                $selected[$type] = $all[$type];
            } else {
                $selected[$type] = $type;
            }
        }

        return $selected;
    }
}

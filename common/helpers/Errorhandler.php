<?php

namespace common\helpers;

use yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\dictionaries\MenuTypes as Menu;

/**
 * Class Errorhandler ( Language helper )
 *
 * Inserts the necessary JavaScripts for client side multilingual support into the content.
 *
 * @package common\helpers
 */
class Errorhandler
{
    /**
     * @param $model
     *
     * @return string
     * @throws yii\db\Exception
     */
    public static function getRelatedData($model)
    {
        $schema = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $text = Yii::t('app', 'You can not delete this item, other items are referring to it.') . '<br>';

        $fks = Yii::$app->getDb()->createCommand("
            SELECT DISTINCT
                    kcu.referenced_table_name AS rtn, referenced_column_name AS rcn, 
                    kcu.TABLE_NAME AS tn, kcu.COLUMN_NAME AS cn, col.COLUMN_NAME AS pk,
                    rc.DELETE_RULE AS dr
            FROM
                    information_schema.key_column_usage kcu
                            JOIN information_schema.columns col ON 
                                    (kcu.TABLE_SCHEMA = col.TABLE_SCHEMA AND
                                      kcu.TABLE_NAME = col.TABLE_NAME AND
                                      col.COLUMN_KEY = 'PRI')
                            JOIN information_schema.referential_constraints rc ON
                                    (kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME)
            WHERE
                    kcu.TABLE_SCHEMA = '" . $schema . "' AND 
                    kcu.referenced_table_name = '" . $model->tableName() . "' AND
                    kcu.referenced_table_name IS NOT NULL")
            ->queryAll();

        // prepare the unlinkrelateddata button
        $modeltablename = strtolower($fks[0]['rtn']);
        switch ($modeltablename) {
            case 'trainings':
                $unlinkrelateddatatext = $model->levelDescription;
                $unlinkrelateddatabutton =
                    Html::a('!&nbsp;<i class="fa fa-chain-broken"></i>&nbsp;!',
                        Url::to('/' . $modeltablename . '/unlinkrelateddata/' . $model[$fks[0]['rcn']]),
                        [
                            'class' => 'btn-outline-secondary btn-md',
                            'title' => 'unlink ' . $model->levelDescription
                        ]);
                break;
            default:
                $unlinkrelateddatatext = $unlinkrelateddatabutton = '';
        }
        $text .= '<ul>';
        foreach ($fks as $row) {
            $reldata = Yii::$app->getDb()->createCommand("
                SELECT " . $row['pk'] . " FROM " . $row['tn'] . " WHERE " . $row['cn'] . "=" . $model[$row['rcn']])
                ->queryAll();
            $cascade_delete = ($row['dr'] == 'CASCADE' ? ' -> <i>Cascade delete</i>' : '');
            foreach ($reldata as $relrow) {
                switch (strtolower($row['tn'] . '.' . $row['pk'])) {
                   
                    case 'contacts.id_contact':
                        $reltabinfo = '<b>' . Menu::HSEcontacts_text() . ' / ' .
                            Menu::Employers_text() . ' / ' .
                            Menu::Workplaces_text() . ' / ' .
                            Menu::Suppliers_text() . ' ' .
                            Yii::$app->db->createCommand("
                            SELECT CONCAT(Lastname, ' ', Firstname, ' (', ID_Contact, ')')
                            FROM Contacts
                            WHERE ID_Contact = " . $relrow[$row['pk']] . "
                            ")->queryScalar() . '</b><br>';
                        break;
                    default:
                        $reltabinfo = '';
                }
                $text .= '<li>' . $reltabinfo . $row['rtn'] . '.' . $row['rcn'] . ' (' . $model[$row['rcn']] . ') -> ';
                $text .= $row['tn'] . '.' . $row['pk'] . ' (' . $relrow[$row['pk']] . ')' . $cascade_delete . '</li>';
            }
        }
        $text .= '</ul>';

        if (!empty($unlinkrelateddatabutton) && Yii::$app->user->can('team_member')) {
            $text .= '<hr></hr><table><tr>';
            $text .= '<th>' . $unlinkrelateddatabutton . '&nbsp;&nbsp;</th>';
            $text .= '<th><b>DANGER ZONE!</b><br>';
            $text .= Yii::t('app', 'Unlink all items referring to') . ':<br>' .
                $unlinkrelateddatatext . '<br>' .
                Yii::t('app', 'Data will be lost') . '!</th>';
            $text .= '</tr></table>';
        }
        return $text;
    }
}

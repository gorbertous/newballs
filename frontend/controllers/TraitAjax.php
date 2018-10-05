<?php

namespace frontend\controllers;

use backend\models\Interventiontypes;
use backend\models\JStockInterventiontypes;
use backend\models\JStockLegislation;
use backend\models\Legislation;
use backend\models\Stocktypes;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

use backend\models\Empcontracts;
use backend\models\Workunits;
use backend\models\Actions;
use backend\models\Registerinspect;
use backend\models\Registerplus;
use backend\models\Accidents;
use backend\models\Stock;
use backend\models\ActionsSearch;
use backend\models\Riskseval;
use backend\models\ActionsprogressSearch;

use common\context\ContextStock;
use common\dictionaries\AbsenceTypes;

/**
 * Trait TraitAjax
 * @package frontend\controllers
 */
trait TraitAjax
{
    /**
     * Gets back the standard iMonths value for a given Workunit
     *
     * @return int if $wu->iMonths is set we return it if not return 0
     */
//    public function actionWorkunitimonths()
//    {
//        if (isset($_POST)) {
//            extract($_POST);
//
//            /** @var int $workunit_id */
//
//            if (filter_var($workunit_id, FILTER_VALIDATE_INT) ||
//                filter_var($workunit_id, FILTER_VALIDATE_FLOAT)) {
//                $wu = Workunits::findOne($workunit_id);
//            }
//        }
//        return $wu->iMonths ?? 0;
//    }

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionActionitems()
    {
        $msg = '<div class="alert alert-danger">No data found</div>';

        if (isset($_POST['expandRowKey'])) {

            switch ($_POST['page']) {
                case 'index':
                    $id = Yii::$app->request->post('expandRowKey');
                    $search = Yii::$app->request->post('key');

                    $searchModel = new ActionsSearch();
                    $searchModel->$search = $id;
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                    if ($dataProvider->getTotalCount() > 0) {
                        return Yii::$app->controller->renderPartial('@backend/views/actions/includes/_items.php', [
                            'dataProvider' => $dataProvider
                        ]);
                    }
                    break;

                case 'indexwu':
                    $ID_Risk = Riskseval::findOne($_POST['expandRowKey'])->ID_Risk;

                    $searchModel = new ActionsSearch();
                    $searchModel->ID_Risk = $ID_Risk;
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                    if ($dataProvider->getTotalCount() > 0) {
                        return Yii::$app->controller->renderPartial('@backend/views/actions/includes/_items.php', [
                            'dataProvider' => $dataProvider
                        ]);
                    }
                    break;

                default:
                    return $msg;
            }

        }

        return $msg;
    }

    /**
     * @return string
     */
    public function actionActionprogress()
    {
        if (isset($_POST['expandRowKey'])) {
            $searchModel = new ActionsprogressSearch();
            $searchModel->ID_Action = $_POST['expandRowKey'];
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            if ($dataProvider->getTotalCount() > 0) {
                return Yii::$app->controller->renderPartial('_items', [
                    'dataProvider' => $dataProvider
                ]);
            }
        }

        return '<div class="alert alert-danger">No data found</div>';
    }


    /**
     * Gets back the standard iMonths value for a given Empcontract/Workunit
     *
     * @return int if $wu->iMonths is set return it if not return 0
     */
    public function actionEmpcontractworkunitsimonths()
    {
        if (isset($_POST)) {
            extract($_POST);

            /** @var int $empcontract_id */

            if (filter_var($empcontract_id, FILTER_VALIDATE_INT) ||
                filter_var($empcontract_id, FILTER_VALIDATE_FLOAT)) {
                $ec = Empcontracts::findOne($empcontract_id);

                if (isset($ec)) {
                    $wu = Workunits::findOne($ec->ID_Workunit);
                }
            }
        }

        return $wu->iMonths ?? 0;
    }

    /**
     * Gets back the Workunits for a given Contact via Empcontract
     * if $Old_ID_Workunit exists, return it as selected item
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionContactworkunits()
    {
        $data = $this->getDataOutput();

        if (isset($_POST)) {
            extract($_POST['depdrop_all_params']);

            if (!empty($contact_id) && $this->checkInt($contact_id)) {
                $fns = Workunits::ContLangFieldValueFBsql('WU.Name');
                $sql = <<<SQL
    SELECT DISTINCT WU.ID_Workunit AS id, $fns
    FROM Workunits WU
    INNER JOIN Empcontracts EC ON EC.ID_Workunit = WU.ID_Workunit
    WHERE EC.ID_Contact = :contact_id
    ORDER BY EC.ID_Empcontract DESC
SQL;
                $output = Yii::$app->db->createCommand($sql, [
                    ':contact_id' => $contact_id,
                ])->queryAll();

                $selected = empty($Old_ID_Workunit) ? ((count($output) > 0) ? $output[0]['id'] : '') : $Old_ID_Workunit;

                $data['output'] = $output;
                $data['selected'] = $selected;
            }
        }

        return Json::encode($data);
    }

    /**
     * Gets back the Empcontracts for a given Contact via Empcontract
     *
     * @return string
     * @throws \yii\db\Exception
     * @throws \yii\db\Exception
     */
    public function actionContactempcontracts()
    {
        $data = $this->getDataOutput();

        if (isset($_POST)) {
            extract($_POST['depdrop_all_params']);

            /** @var $contact_id int */
            /** @var $Old_ID_Empcontract int */

            if (!empty($contact_id) && $this->checkInt($contact_id)) {
                $fns = Workunits::ContLangFieldValueFBsql('WU.Name', true);

                $sql = <<<SQL
                SELECT EC.ID_Empcontract AS id,
                    CONCAT_WS(' - ',
                        CASE WHEN EC.ID_Workunit IS NULL THEN NULL ELSE $fns END,
                        CASE WHEN EC.ID_Workplace IS NULL THEN NULL ELSE CWPL.Name END,
                        CASE WHEN EC.ID_Employer IS NULL THEN NULL ELSE CEMP.Name END,
                        EC.Start
                    ) AS name
            
                FROM Empcontracts EC
            
                LEFT JOIN Workunits WU ON EC.ID_Workunit = WU.ID_Workunit
                LEFT JOIN Contacts EMP ON EC.ID_Employer = EMP.ID_Contact
                LEFT JOIN Company CEMP ON EMP.ID_Company = CEMP.ID_Company
                LEFT JOIN Contacts WPL ON EC.ID_Workplace = WPL.ID_Contact
                LEFT JOIN Company CWPL ON WPL.ID_Company = CWPL.ID_Company
            
                WHERE EC.ID_Contact = :contact_id
            
                GROUP BY Name
                ORDER BY EC.Start DESC
SQL;
                $output = Yii::$app->db->createCommand($sql, [
                    ':contact_id' => $contact_id,
                ])->queryAll();

                $selected = empty($Old_ID_Empcontract) ? ((count($output) > 0) ? $output[0]['id'] : '') : $Old_ID_Empcontract;

                $data['output'] = $output;
                $data['selected'] = $selected;
            }
        }

        return Json::encode($data);
    }

    /**
     * Gets back the 10 last Absences for a given Contact
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionContactabsences()
    {
        $data = $this->getDataOutput();

        if (isset($_POST)) {
            extract($_POST['depdrop_all_params']);

            /** @var $contact_id int */
            /** @var $Old_ID_Absence int */

            if (!empty($contact_id) && $this->checkInt($contact_id)) {
                // enrich & format data with absencetype and number of hours

                $output = $this->getFormatedAbsence($contact_id);

                $selected = empty($Old_ID_Absence) ? ((count($output) > 0) ? $output[0]['id'] : '') : $Old_ID_Absence;

                $data['output'] = $output;
                $data['selected'] = $selected;
            }
        }

        return Json::encode($data);
    }

    /**
     * @param $contact_id
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getFormatedAbsence($contact_id)
    {
        $sql = <<<SQL
        SELECT ID_Absence AS id, CONCAT(Start, '$', Hours, '$', Absencetype) AS name
        FROM Absences
        WHERE ID_Contact = :contact_id
        ORDER BY Start DESC
        LIMIT 10
SQL;
        $output = Yii::$app->db->createCommand($sql, [
            ':contact_id' => $contact_id
        ])->queryAll();

        foreach ($output as $k => $v) {
            $a = explode('$', $v['name']);

            $a0 = $a[0] ?? '';
            $a1 = $a[1] ?? '';
            $a2 = $a[2] ?? '';

            $output[$k]['name'] = date("d-m-Y", strtotime($a0)) . ' 
                                       ' . $a1 . ' ' . Yii::t('modelattr', 'Hours') . ' 
                                       ' . AbsenceTypes::get($a2);
        }

        return $output;
    }

    /**
     * Gets back the Healthcenter for a given Contact via Empcontract
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionHealthcenter()
    {
        $data = $this->getDataOutput();

        if (isset($_POST)) {
            extract($_POST['depdrop_all_params']);

            /** @var $empcontract int */
            /** @var $Old_ID_Healthcenter int */

            if (!empty($empcontract) && $this->checkInt($empcontract)) {
                $sql = <<<SQL
    SELECT Company.ID_Company AS id, CONCAT(Company.Name) AS name
    FROM Empcontracts
    INNER JOIN Contacts ON Empcontracts.ID_Employer = Contacts.ID_Contact
    INNER JOIN j_Company_Healthcenter ON Contacts.ID_Company = j_Company_Healthcenter.ID_Company
    INNER JOIN Company ON j_Company_Healthcenter.ID_Company_Healthcenter = Company.ID_Company
    WHERE Empcontracts.ID_Empcontract = :empcontract
    GROUP BY Company.Name 
    ORDER BY Empcontracts.Start DESC
SQL;
                $output = Yii::$app->db->createCommand($sql, [
                    ':empcontract' => $empcontract
                ])->queryAll();

                $selected = empty($Old_ID_Healthcenter) ? ((count($output) > 0) ? $output[0]['id'] : '') : $Old_ID_Healthcenter;

                $data['output'] = $output;
                $data['selected'] = $selected;
            }
        }

        return Json::encode($data);
    }

    /**
     * Gets back context for a given action
     *
     * @return string
     */
    public function actionActioncontext()
    {
        $html = [];

        $idregisterinspect = Yii::$app->request->post('registerinspect_id');
        $idrisk = Yii::$app->request->post('risk_id');
        $idaccident = Yii::$app->request->post('accident_id');

        if (!empty($idregisterinspect) && $this->checkInt($idregisterinspect)) {

            // Registerinspect actions

            $registerinspect = Registerinspect::findOne($idregisterinspect);

            $cwtype = $registerinspect->registeritem->registerplus->stock->stocktype->CW_Type;

            $on = ContextStock::getContextArray()[$cwtype];

            $content = $this->renderPartial('@backend/views/actions/includes/render_registerinspect.php', [
                'registerinspect' => $registerinspect
            ]);

            $html['action_on'] = Yii::t('modelattr', 'Action on') . ' ' . $on['title2'];
            $html['content'] = $content;

        } elseif (!empty($idrisk) && $this->checkInt($idrisk)) {

            // Risks actions

            $actionrisk = Actions::findOne(['ID_Risk' => $idrisk]);

            $providerWorkunits = new ActiveDataProvider([
                'query'      => Workunits::find()
                    ->joinWith('risks')
                    ->where(['Risks.ID_Mandant' => $actionrisk->ID_Mandant])
                    ->andWhere(['Risks.ID_Risk' => $actionrisk->ID_Risk])
                    ->andWhere(['(StartDate <= DATE(NOW()) OR EndDate IS NOT NULL) AND (EndDate>=DATE(NOW()) OR EndDate IS NULL)' => 1]),
                'pagination' => false
            ]);

            $content = $this->renderPartial('@backend/views/actions/includes/render_risks.php', [
                'action'            => $actionrisk,
                'providerWorkunits' => $providerWorkunits,
            ]);

            $html['action_on'] = Yii::t('modelattr', 'Action on') . ' ' . Yii::t('modelattr', 'Risk');
            $html['content'] = $content;

        } elseif (!empty($idaccident) && $this->checkInt($idaccident)) {

            // Accidents actions

            $accident = Accidents::findOne(['ID_Accident' => $idaccident]);

            $content = $this->renderPartial('@backend/views/actions/includes/render_accident.php', [
                'accident' => $accident
            ]);

            $html['action_on'] = Yii::t('modelattr', 'Action on') . ' ' . Yii::t('modelattr', 'Accident');
            $html['content'] = $content;

        }

        return Json::encode($html);
    }

    /**
     * Gets list of the tasks
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionTasks()
    {
        $data = $this->getDataOutput();

        if (isset($_POST)) {
            $ID_Risktype = -1;
            $ID_Workunit = -1;
            $ID_Riskeval = -1;

            foreach ($_POST['depdrop_all_params'] as $key => $value) {
                $parts = explode('-', $key);

                if (count($parts) == 3 && $parts[2] == 'id_workunit') {
                    if (!empty($value)) {
                        $ID_Workunit = $value;
                    }
                }

                if (count($parts) == 3 && $parts[2] == 'id') {
                    if (!empty($value)) {
                        $ID_Riskeval = $value;
                    }
                }

                if ($key == 'risktype') {
                    if (!empty($value)) {
                        $ID_Risktype = $value;
                    }
                }
            }

            if ($this->checkInt($ID_Risktype) || $this->checkInt($ID_Workunit)) {
                $fns = Workunits::ContLangFieldValueFBsql('WorkunitsTasks.Name', true);

                $sql = <<<SQL
    SELECT WorkunitsTasks.ID_WorkunitsTask AS id, $fns AS name
    FROM WorkunitsTasks
    INNER JOIN j_Workunitstasks_Risktypes on j_Workunitstasks_Risktypes.ID_WorkunitsTask = WorkunitsTasks.ID_WorkunitsTask
    INNER JOIN Workunits on Workunits.ID_Workunit = WorkunitsTasks.ID_Workunit
    WHERE j_Workunitstasks_Risktypes.ID_Risktype = :id_risktype
    AND WorkunitsTasks.ID_Workunit = :id_workunit
SQL;
                $output = Yii::$app->db->createCommand($sql, [
                    ':id_risktype' => $ID_Risktype,
                    ':id_workunit' => $ID_Workunit

                ])->queryAll();

                if ($this->checkInt($ID_Riskeval)) {
                    $sql = <<<SQL
    SELECT ID_WorkunitsTask AS id
    FROM  j_Riskeval_Tasks
    WHERE ID_Riskeval = :id_riskeval
SQL;
                    $selected = Yii::$app->db->createCommand($sql, [
                        ':id_riskeval' => $ID_Riskeval
                    ])->queryAll();
                }

                $data['output'] = $output;
                $data['selected'] = empty($selected) ? '' : $selected;
            }
        }

        return Json::encode($data);
    }

    /**
     * Gets back the list of Stock for a given Stocktype
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionStocklist()
    {
        $data = $this->getDataOutput();

        if (isset($_POST)) {
            extract($_POST['depdrop_all_params']);

            /** @var $stocktype_id int */
            /** @var $Old_ID_Stock int */

            if (!empty($stocktype_id) && $this->checkInt($stocktype_id)) {
                $fns = Stock::ContLangFieldValueFBsql('Name', true);
                $sql = <<<SQL
    SELECT ID_Stock AS id, $fns AS name
    FROM Stock 
    WHERE ID_Stocktype = :stocktype_id
    ORDER BY name
SQL;
                $output = Yii::$app->db->createCommand($sql, [
                    ':stocktype_id' => $stocktype_id
                ])->queryAll();

                $selected = empty($Old_ID_Stock) ? '' : $Old_ID_Stock;

                $data['output'] = $output;
                $data['selected'] = $selected;
            }
        }

        return Json::encode($data);
    }

    /**
     * Gets back the list of Brands+Models for a given Stock
     * or if no Stock given, a list for that Mandant/CW_Type
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionBrandmodellist()
    {
        $data = $this->getDataOutput();

        if (isset($_POST)) {
            extract($_POST['depdrop_all_params']);

            /** @var int $stock_id */
            /** @var int $mandant_id */
            /** @var int $Old_ID_Registerplus */
            /** @var string $context */

            if (empty($stock_id) || $stock_id == '...') {

                $sql = <<<SQL
    SELECT Registerplus.ID_Registerplus AS id, 
        CONCAT(Registerplus.Brand_name, ' - ', Registerplus.Product_name) AS name
    FROM Registerplus
    INNER JOIN Stock ON Stock.ID_Stock = Registerplus.ID_Stock
    WHERE Registerplus.ID_Mandant = :mandant_id AND Stock.CW_Type = :context
    ORDER BY Registerplus.Brand_name, Registerplus.Product_name
SQL;
                $output = Yii::$app->db->createCommand($sql, [
                    ':mandant_id' => $mandant_id,
                    ':context'    => $context
                ])->queryAll();

            } else {

                $sql = <<<SQL
    SELECT Registerplus.ID_Registerplus AS id,
        CONCAT(Registerplus.Brand_name, ' - ', Registerplus.Product_name) AS name
    FROM Registerplus
    INNER JOIN Stock ON Stock.ID_Stock = Registerplus.ID_Stock
    WHERE Stock.ID_Stock = :stock_id AND Stock.CW_Type = :context
    ORDER BY Registerplus.Brand_name, Registerplus.Product_name
SQL;
                $output = Yii::$app->db->createCommand($sql, [
                    ':stock_id' => $stock_id,
                    ':context'  => $context
                ])->queryAll();

            }

            $selected = empty($Old_ID_Registerplus) ? '' : $Old_ID_Registerplus;

            $data['output'] = $output;
            $data['selected'] = $selected;
        }

        return Json::encode($data);
    }

    /**
     * Gets back the list of Items for a given Registerplus
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionItemslist()
    {
        $data = $this->getDataOutput();

        if (isset($_POST)) {
            extract($_POST['depdrop_all_params']);

            /** @var $registerplus_id int */
            /** @var $Old_ID_Registeritem int */
            /** @var $Old_Qty_assign int */

            if (!empty($registerplus_id) && $registerplus_id !== '...') {

                $Old_ID_Registeritem = (empty($Old_ID_Registeritem) ? -1 : $Old_ID_Registeritem);
                $Old_Qty_assign = (empty($Old_Qty_assign) ? 1 : $Old_Qty_assign);

                if (Yii::$app->controller->action->id == 'registerminus') {
                    $sql = <<<SQL
    SELECT ID_Registeritem AS id,
        CONCAT_WS(' ', 
            CASE WHEN (Serial_number IS NOT NULL AND TRIM(Serial_number) <> '') THEN Serial_number ELSE 'n/a' END,
            CASE WHEN (Size IS NOT NULL AND TRIM(Size) <> '') THEN CONCAT(' size ', Size) ELSE '' END,
            CONCAT(' (max ', 
                CASE WHEN ID_Registeritem = :old_id_registeritem
                THEN COALESCE(Qty_stock, 0) + :old_qty_assign
                ELSE COALESCE(Qty_stock, 0) END
            , ')')
        ) AS name
    FROM Registeritems 
    WHERE (ID_Registerplus = :registerplus_id AND 
        CASE WHEN ID_Registeritem = :old_id_registeritem
        THEN COALESCE(Qty_stock, 0) + :old_qty_assign
        ELSE COALESCE(Qty_stock, 0) END >0)
    ORDER BY Serial_number
SQL;
                    $output = Yii::$app->db->createCommand($sql, [
                        ':old_id_registeritem' => $Old_ID_Registeritem,
                        ':old_qty_assign'      => $Old_Qty_assign,
                        ':registerplus_id'     => $registerplus_id
                    ])->queryAll();

                } else {
                    $sql = <<<SQL
    SELECT ID_Registeritem AS id,
        CONCAT_WS(' ', 
            CASE WHEN (Serial_number IS NOT NULL AND TRIM(Serial_number) <> '') THEN Serial_number ELSE 'n/a' END,
            CASE WHEN (Size IS NOT NULL AND TRIM(Size) <> '') THEN CONCAT(' size ', Size) ELSE '' END
        ) AS name
    FROM Registeritems 
    WHERE ID_Registerplus = :registerplus_id
    ORDER BY Serial_number
SQL;
                    $output = Yii::$app->db->createCommand($sql, [
                        ':registerplus_id' => $registerplus_id
                    ])->queryAll();
                }

                $selected = empty($Old_ID_Registeritem) ? ((count($output) > 0) ? $output[0]['id'] : '-1') : $Old_ID_Registeritem;

                $data['output'] = $output;
                $data['selected'] = $selected;
            }
        }

        return Json::encode($data);
    }

    /**
     * Gets back the list of Inspectiontypes for a given Registerplus
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionInspecttypeslist()
    {
        $data = $this->getDataOutput();

        if (isset($_POST)) {
            extract($_POST['depdrop_all_params']);

            /** @var $registerplus_id int */
            /** @var $Old_ID_Stockinspecttype int */

            if (!empty($registerplus_id) && $registerplus_id !== '...') {
                $stock = Registerplus::findOne($registerplus_id)->ID_Stock;
                $fns = Interventiontypes::ContLangFieldValueFBsql('Interventiontypes.Name', true);

                $sql = <<<SQL
    SELECT j_Stock_Interventiontypes.ID_Interventiontype AS id, $fns AS name 
    FROM j_Stock_Interventiontypes 
    LEFT JOIN Interventiontypes ON j_Stock_Interventiontypes.ID_Interventiontype = Interventiontypes.ID_Interventiontype 
    WHERE ID_Stock = :stock_id
SQL;
                $output = Yii::$app->db->createCommand($sql, [
                    ':stock_id' => $stock
                ])->queryAll();

                $selected = empty($Old_ID_Stockinspecttype) ? ((count($output) > 0) ? $output[0]['id'] : '') : $Old_ID_Stockinspecttype;

                $data['output'] = $output;
                //print_r($output);
                $data['selected'] = $selected;
            }
        }

        return Json::encode($data);
    }

    /**
     * Gets back the standard iMonths value for a given Registerplus
     * and a given Inspecttype
     *
     * @return int
     */
    public function actionRegisteritemimonths()
    {
        $imonths = 0;

        if (isset($_POST)) {
            extract($_POST);

            /** @var $registerplus_id int */
            /** @var $inspecttype_id int */

            $ri = Registerplus::find()
                ->where(['ID_Registerplus' => $registerplus_id])
                ->one();

            if (isset($ri)) {
                $item = JStockInterventiontypes::find()
                    ->where(['ID_Stock' => $ri->ID_Stock])
                    ->andWhere(['ID_Interventiontype' => $inspecttype_id])
                    ->one();
                if (isset($item->interval_type)) {
                    switch ($item->interval_type) {
                        case 3:
                            // Yearly
                            $imonths = isset($item->interval_value) ? $item->interval_value * 12 : 0;
                            break;
                        case 2:
                            // Monthly
                            $imonths = isset($item->interval_value) ? $item->interval_value : 0;
                            break;
                        case 1:
                            // Daily
                            $imonths = 0;
                            break;
                    }
                }
            }
        }

        return $imonths;
    }

    /**
     * @return array
     */
    private function getDataOutput()
    {
        return ['output' => '', 'selected' => ''];
    }

    /**
     * @param int $value
     *
     * @return bool
     */
    public function checkInt($value)
    {
        return (int)$value > 0;
    }

    /**
     * returns list of legislations for particular stock type
     *
     * @return string
     */
    public function actionLegislationtext()
    {
        $links = [];

        $stocktype_id = Yii::$app->request->post('stocktype_id');

        if (!empty($stocktype_id) && $this->checkInt($stocktype_id)) {

            if (Yii::$app->session->get('mandant_id') == 0) {
                //j_stock_legislation keys belong to mandant 0
                $legisations = JStockLegislation::find()
                    ->where(['ID_Stocktype' => $stocktype_id])
                    ->all();
            } else {
                $parent = Stocktypes::findOne($stocktype_id);

                $legisations = JStockLegislation::find()
                    ->joinWith('stocktype')
                    ->where(['Stocktypes.ID_Library' => empty($parent->ID_Source) ? -1 : $parent->ID_Source])
                    ->all();
            }

            if (!empty($legisations)) {
                foreach ($legisations as $link) {
                    $leg_model = Legislation::findOne($link->ID_Legislation);
                    $item = Yii::$app->controller->renderPartial('//legislation/formview', [
                        'model' => $leg_model
                    ]);
                    if (!in_array($item, $links)) {
                        array_push($links, $item);
                    }
                }
            }

        }

        return Json::encode($links);
    }


    /**
     * Gets back the list of Items for a given Registerplus
     * if Old_ID_Registerplus exists, return it as selected item
     *
     * @param integer $registerplus_id
     * @param integer $Old_Qty_assign
     * @param integer $Old_ID_Registeritem
     *
     * @return mixed
     *
     * public function actionItemslist()
     * {
     * $out = [];
     * extract($_POST['depdrop_all_params']);
     * if (empty($registerplus_id) || $registerplus_id == '...') { // && empty($mandant_id) && empty($context)
     * $out = '';
     *
     * //} elseif (empty($registerplus_id)) {
     * //    $out = Yii::$app->db->createCommand("
     * //        SELECT ID_Registeritem AS id,
     * //            CONCAT_WS(' ',
     * //                Serial_number,
     * //                CASE WHEN (Size IS NOT NULL AND TRIM(Size) <> '') THEN CONCAT(' size ', Size) ELSE '' END
     * //            ) AS name
     * //        FROM Registeritems
     * //        LEFT JOIN Registerplus ON Registeritems.ID_Registerplus = Registerplus.ID_Registerplus
     * //        INNER JOIN Stock ON Stock.ID_Stock = Registerplus.ID_Stock
     * //        WHERE Registerplus.ID_Mandant =" . $mandant_id . " AND
     * //            Stock.CW_Type = '" . $context . "'
     * //                    ORDER BY Registeritems.Serial_number
     * //    ")->queryAll();
     *
     * } else {
     * $Old_ID_Registeritem = (empty($Old_ID_Registeritem) ? -1 : $Old_ID_Registeritem);
     * $Old_Qty_assign = (empty($Old_Qty_assign) ? 1 : $Old_Qty_assign);
     * if (Yii::$app->controller->action->id == 'registerminus') {
     * $out = Yii::$app->db->createCommand("
     * SELECT ID_Registeritem AS id,
     * CONCAT_WS(' ',
     * Serial_number,
     * CASE WHEN (Size IS NOT NULL AND TRIM(Size) <> '') THEN CONCAT(' size ', Size) ELSE '' END,
     * CONCAT(' (max ',
     * CASE WHEN ID_Registeritem = " . $Old_ID_Registeritem . "
     * THEN COALESCE(Qty_stock, 0)+" . $Old_Qty_assign . "
     * ELSE COALESCE(Qty_stock, 0) END
     * , ')')
     * ) AS name
     * FROM Registeritems
     * WHERE (ID_Registerplus=" . $registerplus_id . " AND
     * CASE WHEN ID_Registeritem = " . $Old_ID_Registeritem . "
     * THEN COALESCE(Qty_stock, 0)+" . $Old_Qty_assign . "
     * ELSE COALESCE(Qty_stock, 0) END >0)
     * ORDER BY Serial_number
     * ")->queryAll();
     * } else {
     * $out = Yii::$app->db->createCommand("
     * SELECT ID_Registeritem AS id,
     * CONCAT_WS(' ',
     * Serial_number,
     * CASE WHEN (Size IS NOT NULL AND TRIM(Size) <> '') THEN CONCAT(' size ', Size) ELSE '' END
     * ) AS name
     * FROM Registeritems
     * WHERE ID_Registerplus=" . $registerplus_id . "
     * ORDER BY Serial_number
     * ")->queryAll();
     * }
     * }
     * $selected = (empty($Old_ID_Registeritem) ? '' : $Old_ID_Registeritem );
     * return Json::encode(['output' => $out, 'selected' => $selected]);
     *
     * //if (isset($_POST['depdrop_all_params'])) {
     * //    $parents = $_POST['depdrop_all_params'];
     * //    if ($parents != null) {
     * //        $cat_id = $parents['registerplus'];
     * //        if ($cat_id != '...') {
     * //            $ID_Registeritem = (empty($parents['Old_ID_Registeritem']) ? -1 :
     *     $parents['Old_ID_Registeritem']);
     * //            $Old_Qty_assign = (empty($parents['Old_Qty_assign']) ? 1 : $parents['Old_Qty_assign']);
     * //            if (!empty($cat_id)) {
     * //                $out = Yii::$app->db->createCommand("
     * //                    SELECT ID_Registeritem AS id,
     * //                        CONCAT_WS(' ',
     * //                            Serial_number,
     * //                            CASE WHEN (Size IS NOT NULL AND TRIM(Size) <> '') THEN CONCAT(' size ', Size) ELSE
     *     '' END,
     * //                            CONCAT(' (max ',
     * //                                CASE WHEN ID_Registeritem = " . $ID_Registeritem . "
     * //                                THEN COALESCE(Qty_stock, 0)+" . $Old_Qty_assign . "
     * //                                ELSE COALESCE(Qty_stock, 0) END
     * //                            , ')')
     * //                        ) AS name
     * //                    FROM Registeritems
     * //                    WHERE (ID_Registerplus=" . $cat_id . " AND
     * //                        CASE WHEN ID_Registeritem = " . $ID_Registeritem . "
     * //                        THEN COALESCE(Qty_stock, 0)+" . $Old_Qty_assign . "
     * //                        ELSE COALESCE(Qty_stock, 0) END >0)
     * //                    ORDER BY Serial_number
     * //                ")->queryAll();
     * //                echo \yii\helpers\Json::encode(['output' => $out, 'selected' => $ID_Registeritem]);
     * //                return;
     * //            }
     * //        }
     * //    }
     * //}
     * //echo Json::encode(['output' => '', 'selected' => '']);
     * }
     */
}

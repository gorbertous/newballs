<div class="form-group" id="add-jclub-mem-roles">
<?php
use kartik\grid\GridView;
use kartik\builder\TabularForm;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\Pjax;

$dataProvider = new ArrayDataProvider([
    'allModels' => $row,
    'pagination' => [
        'pageSize' => -1
    ]
]);
echo TabularForm::widget([
    'dataProvider' => $dataProvider,
    'formName' => 'JClubMemRoles',
    'checkboxColumn' => false,
    'actionColumn' => false,
    'attributeDefaults' => [
        'type' => TabularForm::INPUT_TEXT,
    ],
    'attributes' => [
        'id' => ['type' => TabularForm::INPUT_HIDDEN],
        'member_id' => [
            'label' => 'Members',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Members::find()->orderBy('member_id')->asArray()->all(), 'member_id', 'member_id'),
                'options' => ['placeholder' => Yii::t('modelattr', 'Choose Members')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'del' => [
            'type' => 'raw',
            'label' => '',
            'value' => function($model, $key) {
                return
                    Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                    Html::a('<i class="glyphicon glyphicon-trash"></i>', '#', ['title' =>  Yii::t('modelattr', 'Delete'), 'onClick' => 'delRowJClubMemRoles(' . $key . '); return false;', 'id' => 'jclub-mem-roles-del-btn']);
            },
        ],
    ],
    'gridSettings' => [
        'panel' => [
            'heading' => false,
            'type' => Gridview::TYPE_PRIMARY,
            'before' => false,
            'footer' => false,
            'after' => Html::button('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('modelattr', 'Add J Club Mem Roles'), ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRowJClubMemRoles()']),
        ]
    ]
]);
echo  "    </div>\n\n";
?>


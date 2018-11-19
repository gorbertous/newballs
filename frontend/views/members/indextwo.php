<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

use yii\helpers\Url;

use common\dictionaries\MenuTypes as Menu;


$this->title = Menu::getChemicalsText() . '-' . Yii::t('appMenu', 'Risk situations');
$header = '<span class="fa fa-warning"></span> ' . Menu::getChemicalsText() . '&nbsp;';
$header .= '<span class="fa fa-exclamation"></span> ' . Yii::t('appMenu', 'Risk situations');

?>

<div class="risksitu-index">

    <?php 
    $gridColumn = [
          ['class' => 'yii\grid\SerialColumn'],

            'member_id',
            'user_id',
            'c_id',
            'mem_type_id',
            'grade_id',
//            'title', 
//            'firstname', 
//            'lastname', 
//            'gender', 
//            'email:email', 
//            'photo', 
//            'orig_photo', 
//            'phone', 
//            'phone_office', 
//            'phone_mobile', 
//            'address', 
//            'zip', 
//            'city', 
//            'co_code', 
//            'country_id', 
//            'nationality', 
//            ['attribute' => 'dob','format' => ['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']], 
//            'is_admin', 
//            'is_organiser', 
//            'is_active', 
//            'has_paid', 
//            'is_visible', 
//            'ban_scoreupload', 
//            'coaching', 
//            'created_by', 
//            'updated_by', 
//            'created_at', 
//            'updated_at', 
//            'username', 
//            'password', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            Yii::$app->urlManager->createUrl(['members/view', 'id' => $model->member_id, 'edit' => 't']),
                            ['title' => Yii::t('yii', 'Edit'),]
                        );
                    }
                ],
            ]
        ];

    $panelBeforeTemplate = '
        {lefttoolbar}
        <div class="pull-right">
            <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
                {toolbar}
            </div>    
        </div>
        {before}
        <div class="clearfix"></div>';

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => $header,
        ],
        'panelBeforeTemplate' => $panelBeforeTemplate,
         // don't show old export button
       'export' => false,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            ['content' =>
                Html::button('<i class="fa fa-plus"></i>', ['value' => Url::toRoute('risksitu/create'),
                    'class' => 'showModalButton btn btn-success',
                    'title' => Yii::t('appMenu', 'New risk situation')]) . ' ' .
                Html::button('<i class="fa fa-upload fa-rotate-180"></i>', ['value' => Url::toRoute('risksitu/fromlibrary'),
                    'class' => 'showModalButton btn btn-success',
                    'title' => Yii::t('app', 'Get from library')]) . ' ' .
                Html::a('<i class="fa fa-repeat"></i>', ['index'], [
                    'data-pjax' => 0,
                    'class' => 'btn btn-default',
                    'title' => Yii::t('diag', 'Reset Grid')])
            ],
            '{export}',
            ExportMenu::widget([ 
                'dataProvider' => $dataProvider, 
                'columns' => $gridColumn, 
                'target' => ExportMenu::TARGET_BLANK, 
                'fontAwesome' => true, 
                'dropdownOptions' => [ 
                    'label' => Yii::t('yii', 'Export'),
                    'class' => 'btn btn-default', 
                    'itemsBefore' => [ 
                        '<li class="dropdown-header">Export All Data</li>', 
                    ], 
                ], 
                
            ]),   
        ],
        'replaceTags' => [
            '{lefttoolbar}' => function() {
                return
                    Html::a('<span class="fa fa-exclamation"></span> ' . Yii::t('appMenu', 'Risk situations'),
                        ['/risksitu'], 
                        ['class' => 'btn btn-primary disabled']) . ' ' .
                    Html::a('<span class="fa fa-sitemap"></span> ' . Yii::t('appMenu', 'Risktypes'), 
                        ['/risktypes'],
                        ['class' => 'btn btn-default']);
            }
        ]
    ]);
    ?>
</div>


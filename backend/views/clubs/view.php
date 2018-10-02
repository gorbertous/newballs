<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\models\Clubs $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Clubs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clubs-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title,
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'c_id',
            'season_id',
            'session_id',
            'css_id',
            'type_id',
            'lang',
            'name',
            'logo',
            'logo_orig',
            'home_page:ntext',
            'rules_page:ntext',
            'members_page:ntext',
            'rota_page:ntext',
            'tournament_page:ntext',
            'subscription_page:ntext',
            'school_page:ntext',
            'email_header:email',
            'site_url:url',
            'site_currency',
            'coach_stats',
            'token_stats',
            'play_stats',
            'scores',
            'match_instigation',
            'court_booking',
            'money_stats',
            [
                'attribute' => 'activation_date',
                'format' => [
                    'datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime']))
                        ? Yii::$app->modules['datecontrol']['displaySettings']['datetime']
                        : 'd-m-Y H:i:s A'
                ],
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => DateControl::classname(),
                    'type' => DateControl::FORMAT_DATETIME
                ]
            ],
            'admin_id',
            'chair_id',
            'sport_id',
            'location_id',
            'is_active',
            'subscription_id',
            'payment',
            'with_customheader',
            'rota_removal',
            'rota_block',
            'photo_one',
            'photo_two',
            'photo_three',
            'photo_four',
            'custom_header',
            'custom_footer',
            'rota_style',
            'client_url:url',
            'created_by',
            'updated_by',
            [
                'attribute' => 'created_at',
                'format' => [
                    'datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime']))
                        ? Yii::$app->modules['datecontrol']['displaySettings']['datetime']
                        : 'd-m-Y H:i:s A'
                ],
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => DateControl::classname(),
                    'type' => DateControl::FORMAT_DATETIME
                ]
            ],
            [
                'attribute' => 'updated_at',
                'format' => [
                    'datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime']))
                        ? Yii::$app->modules['datecontrol']['displaySettings']['datetime']
                        : 'd-m-Y H:i:s A'
                ],
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => DateControl::classname(),
                    'type' => DateControl::FORMAT_DATETIME
                ]
            ],
        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model->c_id],
        ],
        'enableEditMode' => true,
    ]) ?>

</div>

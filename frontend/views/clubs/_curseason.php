
<?php

use kartik\grid\GridView;
use common\helpers\ViewsHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\dictionaries\Somenumbers;
use common\dictionaries\Years;
?>


<div class="card-body">
    <?php if (Yii::$app->user->can('team_member')): ?>
        <div class="row"> 
            <div class="col-2">
                <?= Html::beginForm(['playerstats/generatecoachingplayerstats'], 'post'); ?>


                <?= Html::hiddenInput('generatecoachingstats', true) ?>
                <?= Html::submitButton(Yii::t('app', 'Generate Coaching Stats'), ['class' => 'btn btn-primary',]); ?>


                <?= Html::endForm(); ?> 
            </div>
            <div class="col-2">
                <?= Html::beginForm(['playerstats/generateplayerstats'], 'post'); ?>


                <?= Html::hiddenInput('generatestats', true) ?>
                <?= Html::submitButton(Yii::t('app', 'Generate Player Stats'), ['class' => 'btn btn-primary',]); ?>


                <?= Html::endForm(); ?> 
            </div>
            <div class="col-2">
                <?= Html::beginForm(['playerstats/generatealltimeplayerstats'], 'post'); ?>


                <?= Html::hiddenInput('generatealltimestats', true) ?>
                <?= Html::submitButton(Yii::t('app', 'Generate All Time Stats'), ['class' => 'btn btn-primary',]); ?>


                <?= Html::endForm(); ?> 
            </div>
            <div class="col-3">
                <?= Html::beginForm(['playerstats/updateplayerstats'], 'post'); ?>


                <?= Html::hiddenInput('updatestats', true) ?>
                <?= Html::submitButton(Yii::t('app', 'Update Player Stats'), ['class' => 'btn btn-primary',]); ?>


                <?= Html::endForm(); ?> 
            </div>
            <div class="col-3">
                <?= Html::beginForm(['playerstats/updatealltimeplayerstats'], 'post'); ?>


                <?= Html::hiddenInput('updatealltimestats', true) ?>
                <?= Html::submitButton(Yii::t('app', 'Update All Time Stats'), ['class' => 'btn btn-primary',]); ?>


                <?= Html::endForm(); ?> 
            </div>
        </div>
    <?php endif ?>
</div>

<?php
$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'member_id',
        'label'     => Yii::t('modelattr', 'Name'),
        'format'    => 'raw',
        'value'     => function($model) {
            return $model->member->name;           
        },
       
        'filterType'          => GridView::FILTER_SELECT2,
        'filter'              => ViewsHelper::getMembersList(),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true]
        ],
        'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-members-stats-search-member_id'],
    ],
    [
        'attribute'      => 'season_id',
        'contentOptions' => ['style' => 'width:100px;'],
        'value'     => function($model) {
            return Yii::$app->session->get('c_id') == 1 ? Years::getyear($model->season_id): $model->season_id;
            
        },
        'filterType'          => GridView::FILTER_SELECT2,
        'filter'              => Somenumbers::all(),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true]
        ],
        'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-members-search-season_id'],
    ],
    'played_stats',
    'scheduled_stats',
    'token_stats',
    'cancelled_stats',
    'coaching_stats',
    'noshow_stats',
    'nonscheduled_stats',
];

echo GridView::widget([
    'dataProvider'   => $dataProvider,
    'filterModel'    => $searchModel,
    'columns'        => $gridColumn,
    'id'             => 'gridview-members_stats-id',
    'tableOptions'   => ['class' => 'table table-responsive'],
    'responsive'     => true,
    'responsiveWrap' => false,
    'condensed'      => false,
    'panel'          => [
        'type'    => Gridview::TYPE_PRIMARY,
        'heading' => Yii::t('modelattr', 'Player Stats'),
    ],
]);

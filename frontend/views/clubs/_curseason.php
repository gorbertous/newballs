
 
<?php

use kartik\grid\GridView;
use common\helpers\ViewsHelper;
use yii\helpers\Html;

?>


<div class="card-body">
    <?php if (Yii::$app->user->can('team_member')): ?>
        <?=Html::beginForm(['playerstats/generateplayerstats'],'post');?>
        <div class="row">     
            <div class="col-6">
                <?= Html::hiddenInput('generatestats', true)?>
                <?= Html::submitButton(Yii::t('app', 'Generate Player Stats'), ['class' => 'btn btn-primary',]);?>
            </div>
        </div>
        <?= Html::endForm();?> 
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
            return $model->name;
        },
        'filterType'          => GridView::FILTER_SELECT2,
        'filter'              => ViewsHelper::getMembersList(null, '', ['is_active' => true, 'has_paid' => true]),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true]
        ],
        'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-members-search-member_id'],
    ],
    [
        'attribute' => 'player_stats_scheduled',
        'value'     => function($model) {
//                    $model->getCoachingCourts();
            return $model->getMemberStats();
        },
    ],
    [
        'attribute' => 'player_stats_played',
        'value'     => function($model) {
            return $model->getMemberStats(['status_id' => 1]);
        },
    ],
    [
        'attribute' => 'token_stats',
        'value'     => function($model) {
            return $model->getMemberStats(['tokens' => true]);
        },
    ],
    [
        'attribute' => 'coaching_stats',
        'value'     => function($model) {
            return $model->getCoachingStats();
        },
    ],
    [
        'attribute' => 'player_stats_cancelled',
        'value'     => function($model) {
            return $model->getMemberStats(['status_id' => 5]);
        },
    ],
    [
        'attribute' => 'status_stats',
        'format' => 'raw',
        'value'     => function($model) {
            return $model->getMemberStats(['status_id' => [3, 7]]) > 0 ? '<div class="text-danger">' . $model->getMemberStats(['status_id' => [3, 7]]) . '</div>' : $model->getMemberStats(['status_id' => [3, 7]]);
        },
    ],
];

echo GridView::widget([
    'dataProvider'   => $dataProvider,
    'filterModel'    => $searchModel,
    'columns'        => $gridColumn,
    'id'             => 'gridview-members-id',
    'tableOptions' => ['class' => 'table table-responsive'],
    'responsive'     => true,
    'responsiveWrap' => false,
    'condensed'      => false,
    'panel'          => [
        'type'    => Gridview::TYPE_PRIMARY,
        'heading' => Yii::t('modelattr', 'Player Stats - Current Season'),
    ],
]);

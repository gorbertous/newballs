<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlayerStatsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('modelattr', 'Player Stats');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-stats-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('modelattr', 'Create Player Stats'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'member_id',
            'season_id',
            'token_stats',
            'player_stats_scheduled',
            'player_stats_played',
            'player_stats_cancelled',
            'coaching_stats',
            'status_stats',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

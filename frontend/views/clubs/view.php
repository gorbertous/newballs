<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Clubs */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Clubs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clubs-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('modelattr', 'Update'), ['update', 'id' => $model->c_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('modelattr', 'Delete'), ['delete', 'id' => $model->c_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('modelattr', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'c_id',
            'css_id',
            'sport_id',
            'season_id',
            'session_id',
            'type_id',
            'name',
            'logo',
            'logo_orig',
            'home_page:ntext',
            'rules_page:ntext',
            'members_page:ntext',
            'rota_page:ntext',
            'tournament_page:ntext',
            'subscription_page:ntext',
            'summary_page:ntext',
            'coach_stats',
            'token_stats',
            'play_stats',
            'scores',
            'match_instigation',
            'court_booking',
            'money_stats',
            'chair_id',
            'location_id',
            'is_active',
            'payment',
            'rota_removal',
            'rota_block',
            'photo_one',
            'photo_two',
            'photo_three',
            'photo_four',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
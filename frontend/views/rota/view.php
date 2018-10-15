<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\GamesBoard */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Games Boards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="games-board-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Games Board').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
<?=             
             Html::a('<i class="fa glyphicon glyphicon-hand-up"></i> ' . Yii::t('app', 'PDF'), 
                ['pdf', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => Yii::t('app', 'Will open the generated PDF file in a new window')
                ]
            )?>
            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'c.name',
            'label' => Yii::t('app', 'C'),
        ],
        [
            'attribute' => 'termin.termin_id',
            'label' => Yii::t('app', 'Termin'),
        ],
        [
            'attribute' => 'member.title',
            'label' => Yii::t('app', 'Member'),
        ],
        'court_id',
        'slot_id',
        'status_id',
        'fines',
        'tokens',
        'late',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    <div class="row">
        <h4>Clubs<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnClubs = [
        'css_id',
        'sport_id',
        'season_id',
        'session_id',
        'type_id',
        'name',
        'logo',
        'logo_orig',
        'home_page',
        'rules_page',
        'members_page',
        'rota_page',
        'tournament_page',
        'subscription_page',
        'summary_page',
        'coach_stats',
        'token_stats',
        'play_stats',
        'scores',
        'match_instigation',
        'court_booking',
        'money_stats',
        'admin_id',
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
    ];
    echo DetailView::widget([
        'model' => $model->c,
        'attributes' => $gridColumnClubs    ]);
    ?>
    <div class="row">
        <h4>PlayDates<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnPlayDates = [
        [
            'attribute' => 'c.name',
            'label' => Yii::t('app', 'C'),
        ],
        'location_id',
        'termin_date',
        'active',
        'season_id',
        'session_id',
        'courts_no',
        'slots_no',
        'recurr_no',
    ];
    echo DetailView::widget([
        'model' => $model->termin,
        'attributes' => $gridColumnPlayDates    ]);
    ?>
    <div class="row">
        <h4>Members<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnMembers = [
        'user_id',
        [
            'attribute' => 'c.name',
            'label' => Yii::t('app', 'C'),
        ],
        'mem_type_id',
        'grade_id',
        'title',
        'firstname',
        'lastname',
        'gender',
        'email',
        'photo',
        'orig_photo',
        'phone',
        'phone_office',
        'phone_mobile',
        'address',
        'zip',
        'city',
        'co_code',
        'country_id',
        'nationality',
        'dob',
        'is_admin',
        'is_organiser',
        'is_active',
        'has_paid',
        'is_visible',
        'ban_scoreupload',
        'coaching',
        'username',
            ];
    echo DetailView::widget([
        'model' => $model->member,
        'attributes' => $gridColumnMembers    ]);
    ?>
</div>

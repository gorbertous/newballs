<?php

use yii\db\Expression;

$historic_membership = $model->getMembership()->count();
$active_membership = $model->getMembership(['is_active' => true])->count();
$active_membership_and_payed = $model->getMembership(['is_active' => true, 'has_paid' => true])->count();
$games_played = $model->getGamesStats(['>', 'member_id', 0])->count();
$future_slots = $model->getGamesStats(['>', 'termin_date', new Expression('NOW()')], 'termin')->count();
?>

<div class="info-box">
    <span class="info-box-icon bg-info"><i class="fas fa-star"></i></span>
    <div class="info-box-content">
        <span class="info-box-text"><?= Yii::t('modelattr', 'Current Season') ?></span>
        <span class="info-box-number"><?= Yii::$app->session->get('club_season') ?></span>
    </div>
</div>

<div class="info-box">
    <span class="info-box-icon bg-green"><i class="fas fa-user"></i></span>
    <div class="info-box-content">
        <span class="info-box-text"><?= Yii::t('modelattr', 'Historic Membership') ?> </span>
        <span class="info-box-number"><?= $historic_membership ?></span>
    </div><!-- /.info-box-content -->
</div><!-- /.info-box -->

<div class="info-box">
    <span class="info-box-icon bg-warning"><i class="fas fa-user"></i></span>
    <div class="info-box-content">
        <span class="info-box-text"><?= Yii::t('modelattr', 'Current Membership') ?></span>
        <span class="info-box-number"><?= $active_membership ?></span>
    </div><!-- /.info-box-content -->
</div><!-- /.info-box -->


<div class="info-box">
    <span class="info-box-icon bg-danger"><i class="fas fa-euro-sign"></i></span>
    <div class="info-box-content">
        <span class="info-box-text"><?= Yii::t('modelattr', 'Have Paid') ?></span>
        <span class="info-box-number"><?= $active_membership_and_payed ?></span>
    </div><!-- /.info-box-content -->
</div><!-- /.info-box -->

<div class="info-box">
    <span class="info-box-icon bg-green"><i class="fas fa-flag"></i></span>
    <div class="info-box-content">
        <span class="info-box-text"><?= Yii::t('modelattr', 'Future Slots Available') ?></span>
        <span class="info-box-number"><?= $future_slots ?></span>
    </div><!-- /.info-box-content -->
</div><!-- /.info-box -->


<div class="info-box">
    <span class="info-box-icon info-box-icon bg-yellow"><i class="fas fa-flag"></i></span>
    <div class="info-box-content">
        <span class="info-box-text"><?= Yii::t('modelattr', 'Slots Taken') ?></span>
        <span class="info-box-number"><?= $games_played ?></span>
    </div><!-- /.info-box-content -->
</div><!-- /.info-box -->

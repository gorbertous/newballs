<?php
    use yii\db\Expression;
    
    $historic_memebrship = $model->getMembership()->count();
    $active_memebrship = $model->getMembership(['is_active' => true])->count();
    $active_memebrship_and_payed = $model->getMembership(['is_active' => true, 'has_paid' => true])->count();
    $games_played = $model->getGamesStats(['>', 'member_id', 0])->count();
    $future_slots = $model->getGamesStats(['>', 'termin_date', new Expression('NOW()')],'termin')->count();
?>

<div class="panel panel-default">
    <div class="panel-heading">    

        <h3 class="panel-title"><span class="fa fa-desktop"></span> Club&nbsp;<span class="fa fa-balance-scale"></span> Stats </h3>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('app', 'Current Season')?></span>
                    <span class="info-box-number"><?= Yii::$app->session->get('club_season') ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-user-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('app', 'Historic Membership')?> </span>
                    <span class="info-box-number"><?= $historic_memebrship ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-user-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('app', 'Current Membership')?></span>
                    <span class="info-box-number"><?= $active_memebrship ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('app', 'Membership Paid')?></span>
                    <span class="info-box-number"><?= $active_memebrship_and_payed ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('app', 'Future Slots Available')?></span>
                    <span class="info-box-number"><?= $future_slots ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon info-box-icon bg-yellow"><i class="fa fa-flag-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('app', 'Historically Slots Taken')?></span>
                    <span class="info-box-number"><?= $games_played ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        
    </div>

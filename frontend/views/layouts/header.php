<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

if (!empty(Yii::$app->session->get('member_photo'))) {
    $profileThumb90 = '@uploadsURL/profile-thumbs/90x90-' . Yii::$app->session->get('member_photo');
    $profileThumb25 = '@uploadsURL/profile-thumbs/25x25-' . Yii::$app->session->get('member_photo');
} else {
    $profileThumb90 = '/img/profile-default90x90.png';
    $profileThumb25 = '/img/profile-default25x25.png';
}
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->session->get('club_name') . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <?php if (!Yii::$app->user->isGuest) { ?>

        <nav class="navbar navbar-static-top" id="main-header-navbar">

            <div class="navbar-custom-menu" style="width: 100%;">
                <ul class="nav navbar-nav pull-left">
                    <li>
                        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                            <span class="sr-only">Open</span>
                        </a>
                    </li>
                </ul>

                <ul class="nav navbar-nav pull-right">


                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="<?= Yii::t('app', 'Open profile menu'); ?>">
                            <?= Html::img($profileThumb25, ['class' => 'user-image', 'alt' => StringHelper::truncate(Html::encode(Yii::$app->user->identity->username), 2)]); ?>
                            <span class="hidden-xs">
                                <strong><?= StringHelper::truncate(Html::encode(Yii::$app->user->identity->username), 20); ?></strong> 
                                &nbsp;&nbsp;
                                <span class="caret" style="border-width: 5px;"></span>
                            </span>
                        </a>

                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                               
                                <?= Html::img($profileThumb90, ['class' => 'img-circle', 'alt' => StringHelper::truncate(Html::encode(Yii::$app->user->identity->username), 2)]); ?>

                                <p>
                                     <?= Yii::$app->session->get('member_name')?>
                                    <small>Member since <?= Yii::$app->session->get('member_since')?></small>
                                </p>
                            </li>
                            
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a data-toggle="tooltip" title="Light theme" href="#" data-change-theme-color="skin-blue-light"><span style="width: 100%; height: 30px; border: 1px solid #ccc; background-color: #f9fafc; display: block;"></span></a>
                                    </div>

                                    <div class="col-xs-4 text-center">
                                        <a data-toggle="tooltip" title="Dark theme" href="#" data-change-theme-color="skin-blue"><span style="width: 100%; height: 30px; border: 1px solid #222d32; background-color: #222d32; display: block;"></span></a>
                                    </div>
                                </div>
                                
                            </li>

                            <li class="user-footer">
                                <div class="pull-left">
                                    <button title="<?= Yii::t('app', 'Update'); ?>" value="<?= Url::toRoute(['user/update', 'id' => Yii::$app->user->identity->id]); ?>" class="btn btn-default btn-flat showModalButton">
                                        <?= Yii::t('app', 'Account'); ?>
                                    </button>
                                    <button title="<?= Yii::t('app', 'Profile'); ?>" value="<?= Url::toRoute(['members/update', 'id' => Yii::$app->user->member->member_id]); ?>" class="btn btn-default btn-flat showModalButton">
                                        <?= Yii::t('app', 'Profile'); ?>
                                    </button>
                                </div>

                                <div class="pull-right">
                                    <a href="<?= Url::toRoute(['site/logout']); ?>" class="btn btn-default btn-flat"><i class="fa fa-sign-out-alt"></i> <?= Yii::t('app', 'Logout'); ?></a>
                                </div>
                            </li>
                        </ul>
                    </li>
                  
                </ul>

            </div>
        </nav>
    <?php } ?>
</header>


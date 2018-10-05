<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

if (!empty(Yii::$app->session->get('member_photo'))) {
    $profileThumb90 = '@uploadsURL/profile-thumbs/90x90-' . Yii::$app->session->get('member_photo');
    $profileThumb25 = '@uploadsURL/profile-thumbs/25x25-' . Yii::$app->session->get('member_photo');
} else {
    $profileThumb90 = '/static/images/profile-default90x90.png';
    $profileThumb25 = '/static/images/profile-default25x25.png';
}
?>

<!-- top navigation -->
<div class="top_nav">

    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <?= Html::img($profileThumb25, ['class' => 'user-image', 'alt' => StringHelper::truncate(Html::encode(Yii::$app->user->identity->username), 2)]) ; ?>
                        <span class="hidden-xs">
                            <strong><?= StringHelper::truncate(Html::encode(Yii::$app->user->identity->username), 20); ?></strong>
                            &nbsp;&nbsp;
                            <span class="caret" style="border-width: 5px;"></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li>
                            <a href="<?= Url::toRoute(['members/update', 'id' => Yii::$app->user->member->member_id]); ?>">
                            <span class="badge bg-red pull-right">50%</span>
                            <?= Yii::t('app', 'Profile'); ?></a>
                            
                        </li>
                        <li>
                            <a href="<?= Url::toRoute(['user/update', 'id' => Yii::$app->user->identity->id]); ?>">
                           <?= Yii::t('app', 'Account'); ?></a>
                          
                        </li>
                        <li>
                            <a href="javascript:;">Help</a>
                        </li>
                        <li>
                            <a href="<?= Url::toRoute(['site/logout']); ?>"><i class="fa fa-sign-out pull-right"></i> <?= Yii::t('app', 'Logout'); ?></a>
                        </li>
                    </ul>
                </li>

                <li role="presentation" class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-green">6</span>
                    </a>
                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                        <li>
                            <a>
                                <span class="image">
                                    <?= Html::img($profileThumb25, ['class' => 'img-circle', 'alt' => Html::encode(Yii::$app->user->identity->username)]); ?>
                                </span>
                                <span>
                                    <span>John Smith</span>
                                    <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                    Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image">
                                    <img src="http://placehold.it/128x128" alt="Profile Image" />
                                </span>
                                <span>
                                    <span>John Smith</span>
                                    <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                    Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image">
                                    <img src="http://placehold.it/128x128" alt="Profile Image" />
                                </span>
                                <span>
                                    <span>John Smith</span>
                                    <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                    Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image">
                                    <img src="http://placehold.it/128x128" alt="Profile Image" />
                                </span>
                                <span>
                                    <span>John Smith</span>
                                    <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                    Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                        </li>
                        <li>
                            <div class="text-center">
                                <a href="/">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>

</div>
<!-- /top navigation -->
<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/activate-account', 
    'token' => $user->account_activation_token]);
?>


<div class="password-reset">
    <p><?= Yii::t('app', 'Hello')?> &nbsp; <?= Html::encode($user->username) ?>,</p>

    <p><?= Yii::t('app', 'Follow the link below to activate your account:')?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>

<?php
use dmstr\adminlte\widgets\Alert;
use yii\helpers\Url;
?>
<div class="content-wrapper">
    <section class="content">
        <?= Alert::widget() ?>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->session->get('member_profile_complete') < 100) : ?>
            <?php $percentage_no = Yii::$app->session->get('member_profile_complete') ?>
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?= $percentage_no ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $percentage_no ?>%">
                    Profile <?= $percentage_no ?>%
                </div>
            </div>
            <div class="alert alert-info">
                <strong>Warning!</strong> In order to book your games you must complete your &nbsp;&nbsp;  
                <button title="<?= Yii::t('app', 'Profile'); ?>" value="<?= Url::toRoute(['members/update', 'id' => Yii::$app->session->get('member_id')]); ?>" class="btn btn-outline-secondary  showModalButton">
                    <?= Yii::t('app', 'Profile'); ?>
                </button>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </section>
</div>

<!-- footer content -->
<footer class="main-footer">
    <div class="float-left">
        <?= common\widgets\LanguageChooser::widget(); ?>
    </div>

    <div class="float-right">
        <?php if (!Yii::$app->user->isGuest) : ?>
            Version <strong><?= Yii::$app->version; ?></strong> -
        <?php endif; ?>
        <strong>Copyright © 2007-<?= date('Y') ?> <a href="https://balls-tennis.com" target="_blank">Balls Tennis</a>.</strong>
    </div>

    <div class="clear"></div>
</footer>
<!-- /footer content -->


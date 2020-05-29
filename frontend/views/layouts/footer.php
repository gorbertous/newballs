
<!-- footer content -->
<footer class="main-footer">
    <div class="float-left">
        <?= common\widgets\LanguageChooser::widget(); ?>
    </div>

    <div class="float-right hidden-xs text-right">
        <?php if (!Yii::$app->user->isGuest) { ?>
        Version <strong><?= Yii::$app->version; ?></strong> -
        <?php } ?>
        <strong>Copyright © 2007-<?= date('Y') ?> <a href="http://balls-tennis.com" target="_blank">Balls Tennis</a>.</strong>
    </div>

    <div class="clear"></div>
</footer>
<!-- /footer content -->
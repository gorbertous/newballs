<?php

use yii\helpers\Html;
use backend\assets\AdmAsset;
use backend\assets\AdminLtePluginAsset;

AdmAsset::register($this);
AdminLtePluginAsset::register($this);

?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>

        <meta charset="<?= Yii::$app->charset ?>" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />

        <?= Html::csrfMetaTags() ?>

        <title><?= Html::encode(strip_tags($this->title)) ?></title>

        <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2" />

        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" rel="stylesheet" />

        <?php $this->head() ?>

    </head>
    <body class="skin-blue-light hold-transition sidebar-mini fixed">

        <?php $this->beginBody(); ?>

        <div class="wrapper">
            <div id="modal-ajax"></div>

            <?= $this->render('header.php'); ?>
            <?= $this->render('left.php'); ?>
            <?= $this->render('content.php', ['content' => $content]); ?>
           
        </div>

        <!--        <div id="custom_notifications" class="custom-notifications dsp_none">
                    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
                    </ul>
                    <div class="clearfix"></div>
                    <div id="notif-group" class="tabbed_notifications"></div>
                </div>-->

        <script>
            var settings = {
                languages: ['<?= join(array_diff(Yii::$app->urlManager->languages, ['en']), '\',\''); ?>'],
                ajax_path   : '/admin<?= (Yii::$app->language == 'en' ? '' : '/'.Yii::$app->language); ?>',
                environment: '<?= (YII_ENV_DEV ? 'dev' : 'prod'); ?>'
            };

            var messages = {
                close_window_warning: "<?= Yii::t('app', 'Data will be lost if not saved, are you sure you want to quit?'); ?>"
            };
        </script>

        <?php $this->endBody(); ?>

    </body>
</html>
<?php $this->endPage(); ?>
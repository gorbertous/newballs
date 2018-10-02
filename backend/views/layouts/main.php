<?php

use yii\helpers\Html;
use backend\assets\AdmAsset;

AdmAsset::register($this);

?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta charset="<?= Yii::$app->charset ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>" >

        <?php $this->beginBody(); ?>
        <div class="container body">

            <div class="main_container">

                <div id="modal"></div>

                <?= $this->render('left.php'); ?>
                <?= $this->render('header.php'); ?>
                <?= $this->render('content.php', ['content' => $content]); ?>
                <?= $this->render('footer.php'); ?>

            </div>
            
        </div>
        
        <div id="custom_notifications" class="custom-notifications dsp_none">
            <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
            </ul>
            <div class="clearfix"></div>
            <div id="notif-group" class="tabbed_notifications"></div>
        </div>

        <script>
            var settings = {
                languages   : ['<?= join(array_diff(Yii::$app->urlManager->languages, ['en']), '\',\''); ?>'],
                ajax_path   : '/admin<?= (Yii::$app->language == 'en' ? '' : '/'.Yii::$app->language); ?>',
                environment : '<?= (YII_ENV_DEV ? 'dev' : 'prod'); ?>'
            };

            var messages = {
              close_window_warning: "<?= Yii::t('app', 'Data will be lost if not saved, are you sure you want to quit?'); ?>"
            };
        </script>
      
        <?php $this->endBody(); ?>

    </body>
</html>
<?php $this->endPage(); ?>


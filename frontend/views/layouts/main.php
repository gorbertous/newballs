<?php

use yii\helpers\Html;
use frontend\assets\AdmAsset;
use frontend\assets\AdminLtePluginAsset;
//use kartik\icons\FontAwesomeAsset;

AdmAsset::register($this);
AdminLtePluginAsset::register($this);
//FontAwesomeAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>

        <meta charset="<?= Yii::$app->charset ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="google-site-verification" content="JI6gDOmLIr7vV1xvbXNDzOysLEz6iQy3iDqHrQbRA2E" />

        <?= Html::csrfMetaTags() ?>

        <title><?= Html::encode(strip_tags($this->title)) ?></title>
       
        <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2" />


        <?php $this->head() ?>

    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
            
           

        <?php $this->beginBody(); ?>

        <div class="wrapper">
            <div id="modal-ajax" class="iziModal"></div>
        

            <?= $this->render('header.php'); ?>
            <?= $this->render('left.php'); ?>
            <?= $this->render('content.php', ['content' => $content]); ?>
           
       
            <script>
                var settings = {
                    languages: ['<?= join(array_diff(Yii::$app->urlManager->languages, ['en']), '\',\''); ?>'],
                    ajax_path: '/<?= (Yii::$app->language == 'en' ? '' : '/' . Yii::$app->language); ?>',
                    environment: '<?= (YII_ENV_DEV ? 'dev' : 'prod'); ?>'
                };

                var messages = {
                    close_window_warning: "<?= Yii::t('app', 'Data will be lost if not saved, are you sure you want to quit?'); ?>"
                };
            </script>
        </div>
        <?php $this->endBody(); ?>

    </body>
</html>
<?php $this->endPage(); ?>

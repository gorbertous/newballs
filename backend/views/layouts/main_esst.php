<?php

use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);

?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>

        <meta charset="<?= Yii::$app->charset ?>" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />

        <?= Html::csrfMetaTags() ?>

        <title><?= Html::encode(strip_tags($this->title)) ?></title>

       
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" rel="stylesheet" />

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
        
        <?php $this->head() ?>

    </head>
    <body class="skin-blue-light hold-transition sidebar-mini fixed">

        <?php $this->beginBody(); ?>

        <div class="wrapper">
            <div id="modal"></div>

            <?= $this->render('header.php'); ?>
            <?= $this->render('left.php'); ?>
            <?= $this->render('content.php', ['content' => $content]); ?>
            <?= $this->render('footer.php'); ?>
        </div>

        <script>
            var settings = {
                languages   : ['<?= join(array_diff(Yii::$app->urlManager->languages, ['fr']), '\',\''); ?>'],
                ajax_path   : '/backend<?= (Yii::$app->language == 'fr' ? '' : '/'.Yii::$app->language); ?>',
                environment : '<?= (YII_ENV_DEV ? 'dev' : 'prod'); ?>'
            };

            var messages = {
              close_window_warning: "<?= Yii::t('app', 'Data will be lost if not saved, are you sure you want to quit?'); ?>"
            };
        </script>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <?php $this->endBody(); ?>

    </body>
</html>
<?php $this->endPage(); ?>


<?php

use yii\helpers\Html;

\backend\assets\ClientAsset::register($this);

?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>

    <meta charset="<?= Yii::$app->charset ?>" />

    <meta name="robots" content="noindex, nofollow" />

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="description" content="<?= Yii::t('index', 'eSST Monitoring - Client area') ?>" />

    <?= Html::csrfMetaTags(); ?>

    <title><?= \yii\helpers\HtmlPurifier::process($this->title); ?></title>

    <link rel="shortcut icon" type="image/x-icon"  href="/img/favicon.ico" />

    <?php $this->head(); ?>

</head>
    <body>
    <?php $this->beginBody(); ?>

    <main id="main-content">

        <section id="login">
            <a class="logo" href="<?= \yii\helpers\Url::to(['site/login']); ?>"></a>

            <?= $content ?>

            <?php if (!strpos(Yii::$app->request->url, 'select')) { ?>
            <div id="settings">
                <?= backend\widgets\LanguageChooser::widget(); ?>
            </div>
            <?php } ?>

        </section>

    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>

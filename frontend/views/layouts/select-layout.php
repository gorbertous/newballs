<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>

        <meta charset="<?= Yii::$app->charset ?>" />

        <meta name="robots" content="noindex, nofollow" />

        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
        <meta name="description" content="<?= Yii::t('index', 'Club area') ?>" />

        <?= Html::csrfMetaTags(); ?>

        <title><?= \yii\helpers\HtmlPurifier::process($this->title); ?></title>

        <link rel="shortcut icon" type="image/x-icon"  href="/img/favicon.ico" />

        <?php $this->head(); ?>

    </head>
    <body>
        <?php $this->beginBody(); ?>

        <main id="main-content">

            <section id="login">

                <?= $content ?>

            </section>

        </main>

        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>

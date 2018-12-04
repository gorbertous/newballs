<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="JI6gDOmLIr7vV1xvbXNDzOysLEz6iQy3iDqHrQbRA2E" />
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2" />
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <nav class="navbar-default navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>                        
                        </button>
                        <a class="navbar-brand" href="/"><?= Html::img('/img/tennis-ball.png', ['alt' => Yii::$app->name, 'style' => 'height : 35px; width : 35px;']) ?></a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">

                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="/index"><?= Yii::t('app', 'Home') ?></a></li>
                            <li><a href="/about"><?= Yii::t('app', 'About') ?></a></li>
                            <li><a href="/signup"><span class="glyphicon glyphicon-user"></span> <?= Yii::t('app', 'Join') ?></a></li>
                            <li><a href="/login"><span class="glyphicon glyphicon-log-in"></span> <?= Yii::t('app', 'Login') ?></a></li>
                        </ul>
                    </div>
                </div>
            </nav>


            <div class="container">

                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::t('app', 'Tell your friends and collegues about the club, the more the merrier!') ?></p>
            </div>
        </footer>


        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
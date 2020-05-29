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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="google-site-verification" content="JI6gDOmLIr7vV1xvbXNDzOysLEz6iQy3iDqHrQbRA2E" />
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
          
            
            <!-- Navigation -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
              <div class="container">
                  <a class="navbar-brand" href="/"><?= Html::img('/img/tennis-ball.png', ['alt' => Yii::$app->name, 'style' => 'height : 35px; width : 35px;']) ?> <span>BALLS TENNIS</span></a>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                  <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="/index""><?= Yii::t('app', 'Home') ?>
                        <span class="sr-only">(current)</span>
                      </a>
                    </li>
                    <li id="link-about" class="nav-item">
                      <a class="nav-link" href="/about"><?= Yii::t('app', 'About') ?></a>
                    </li>
                    <li id="link-signup" class="nav-item">
                      <a class="nav-link" href="/signup"><i class="fas fa-user"></i> <?= Yii::t('app', 'Join') ?></a>
                    </li>
                    <li id="link-login" class="nav-item">
                      <a class="nav-link" href="/login"><i class="fas fa-sign-in-alt"></i> <?= Yii::t('app', 'Login') ?></a>
                    </li>
                  </ul>
                </div>
              </div>
            </nav>


            <div class="container">

                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="py-5 bg-dark">
          <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
            <p class="m-0 text-center text-white"><?= Yii::t('app', 'Tell your friends and collegues about the club, the more the merrier!') ?></p>
          </div>
          <!-- /.container -->
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
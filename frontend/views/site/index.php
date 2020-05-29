<?php

use yii\helpers\Html;

//$script = <<< JS
//    $('.carousel').carousel({
//        interval: 10000 //changes the speed
//    })
//JS;
//$this->registerJs($script, yii\web\View::POS_END);
/* @var $model backend\models\Clubs */
$club_logo = '/img/uploads/' . $model->c_id . '/clubs/' . $model->logo;
?>



<!-- Heading Row -->
<div class="row align-items-center my-2">
    <div class="col-lg-12">
        <img class="img-fluid rounded mb-4 mb-lg-0" src="<?= $club_logo ?>" alt="">
    </div>
    <!-- /.col-lg-8 -->

    <!-- /.col-md-4 -->
</div>
<!-- Heading Row -->
<div class="row align-items-center my-3">
    <!-- /.col-lg-8 -->
    <div class="col-lg-10">

        <?= $model->summary_page ?>
        <div class="modal-footer">
                <a href="/about" class="btn btn-primary btn-lg"><?= Yii::t('app', 'More Info') ?></a>
            </div>
        
    </div>
    <div class="col-lg-2">
        <img class="img-fluid rounded mb-4 mb-lg-0" src="/img/racket.png" alt="">
        
        
    </div>

    <!-- /.col-md-4 -->
</div>


<!-- /.row -->
<!--<div id="myCarousel" class="carousel slide" data-ride="carousel">
   Indicators 
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

    Wrapper for slides 
  <div class="carousel-inner">
    <div class="item active">
      <img src="/img/white_bubble_1900x1080.jpg" alt="Los Angeles">
    </div>

    <div class="item">
      <img src="/img/Tennis-Wallpapers-01222.jpg" alt="Chicago">
    </div>

    <div class="item">
      <img src="/img/tennis-ball-1900-1080.jpg" alt="New York">
    </div>
  </div>

    Left and right controls 
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>-->

<!-- Call to Action card card-body bg-light -->
<div class="card text-white bg-secondary my-5 py-4 text-center">
    <div class="card-body">
        <p class="text-white m-0"><?= $model->members_page ?></p>
    </div>
</div>

<!-- Content Row -->
<!-- /.col-md-8 -->
<div class="row">
    <div class="col-md-8 mb-5">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="card-title"><?= Yii::t('app', 'Where do we play') ?></h2>
                <p class="card-text">
                    <?=
                    yii2mod\google\maps\markers\GoogleMaps::widget([
                        'userLocations' => [
                            [
                                'location'    => [
                                    'address' => $model->shortAddress,
                                    'country' => $model->location->co_code,
                                ],
                                'htmlContent' => '<h1>' . $model->name . '</h1>',
                            ],
                        ],
                    ]);
                    ?>

                </p>

            </div>
            <div class="card-footer">
                <?= $model->shortAddress ?>
            </div>
        </div>
    </div>
    <!-- /.col-md-4 -->
    <div class="col-md-4 mb-5">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="card-title"><?= Yii::t('app', 'Commitment') ?></h2>
                <p class="card-text">The club is set up to ensure that regular commitment, while preferred, is not essential. We generally play doubles tennis. Each week the pairings will be mixed up to promote balanced and fun matches. Depending on interest, limited coaching by a Professional (on a rotated membership basis) may be provided on one of our reserved courts.</p>
                <h2 class="card-title"><?= Yii::t('app', 'Booking') ?></h2>
                <p class="card-text"><?= Yii::t('app', 'The games are managed in the members only area of this site. In case you are not yet member, you can join us following the button bellow!') ?></p>
            </div>
            <div class="card-footer">
                <a href="/signup" class="btn btn-primary btn-lg"><?= Yii::t('app', 'Join') ?></a>
            </div>
        </div>
    </div>


</div>
<!-- /.row -->

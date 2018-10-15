<?php

use yii\helpers\Html;

//$script = <<< JS
//    $('.carousel').carousel({
//        interval: 10000 //changes the speed
//    })
//JS;
//$this->registerJs($script, yii\web\View::POS_END);
/* @var $model backend\models\Clubs */
?>

<!-- Heading Row -->
<div class="row">
    <div class="col-md-8">
        <img class="img-responsive img-rounded" src="/img/tennis-ball-1900-1080.jpg" alt="">
    </div>
    <!-- /.col-md-8 -->
    <div class="col-md-4">
        <h1><?= $model->name ?></h1>
        <p><?= $model->summary_page ?></p>
<!--        <a class="btn btn-primary btn-lg" href="#">Call to Action!</a>-->
    </div>
    <!-- /.col-md-4 -->
</div>
<!-- /.row -->

<hr>

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

<hr>

<!-- Call to Action Well -->
<div class="row">
    <div class="col-lg-12">
        <div class="well text-center">
            <?= $model->members_page ?>
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<!-- Content Row -->
<div class="row">
    <div class="col-md-4">
        <h2>Where do we play</h2>
        <p><?= $model->shortAddress ?></p>
        <a class="btn btn-default" href="/about">More Info</a>
    </div>
    <!-- /.col-md-4 -->
    <div class="col-md-4">
        <h2>Commitment</h2>
        <p>The club is set up to ensure that regular commitment, while preferred, is not essential. We generally play doubles tennis. Each week the pairings will be mixed up to promote balanced and fun matches. Depending on interest, limited coaching by a Professional (on a rotated membership basis) may be provided on one of our reserved courts.</p>
        <a class="btn btn-default" href="/about">More Info</a>
    </div>
    <!-- /.col-md-4 -->
    <div class="col-md-4">
        <h2>Booking</h2>
        <p>The games are managed in the members only area of this site. In case you are not yet member, you can sign up following the button bellow!</p>
        <a class="btn btn-default" href="/about">More Info</a>
    </div>
    <!-- /.col-md-4 -->
</div>
<!-- /.row -->
<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'About the Club');

$setactive = "$(document).ready(function(){
	$('.active').removeClass('active');
	$('#link-about').addClass('active');
});";

$this->registerJs($setactive);
?>
<!-- Main content -->

<div class="row">
    <div class="col-md-12 mb-5">

        <div class="card-body">
            <h2 class="card-title"><?= Html::encode($this->title) ?></h2>
            <?= $model->home_page ?>
        </div>

    </div>
</div>


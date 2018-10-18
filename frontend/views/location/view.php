<?php

//use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Location */

?>

<div class="row">
    <div class="col-md-12">

        <div class="location-view">

            <?=
            DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    'name',
                    'address',
                    'phone',
                    'co_code',
                    'google_par_one',
                    'google_par_two',
                ],
            ])
            ?>
        </div>

        <div class="clearfix"></div> <br />
        <?php
        echo \common\helpers\Helpers::getModalFooter($model, $model->location_id, 'view', [
            'buttons' => ['cancel']
        ]);
        ?>
    </div>
</div>



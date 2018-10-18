<?php

//use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Scores */
?>

<div class="row">
    <div class="col-md-12">
        <div class="scores-view">
            <?=
            DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    'termin.termin_date',
                    'court_id',
                    'set_one',
                    'set_two',
                    'set_three',
                    'set_four',
                    'set_five',
                    'createUserName',
                    'created_at:datetime',
                ],
            ])
            ?>
        </div>
        <div class="clearfix"></div> <br />
        <?php
        echo \common\helpers\Helpers::getModalFooter($model, $model->score_id, 'view', [
            'buttons' => ['cancel']
        ]);
        ?>
    </div>
</div>



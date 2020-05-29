<?php

use yii\widgets\DetailView;
use common\helpers\Helpers;

/* @var $this yii\web\View */
/* @var $model backend\models\Message */

?>

<div class="message-view">

    <div class="row">
        <div class="col-md-12">
            <?php
            $gridColumn = [
                'id',
                'language',
                //'hash',
                'sourceMessage.message',
                'translation:ntext',
            ];
            /** @noinspection PhpUnhandledExceptionInspection */
            echo DetailView::widget([
                'model' => $model,
                'attributes' => $gridColumn
            ]); ?>
        </div>
    </div>

    <div class="form-group no-print float-right">
       <?php echo Helpers::getModalFooter($model, $model->id, 'view', [
            'buttons' => ['cancel']
        ]); ?>
        
        <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>
</div>

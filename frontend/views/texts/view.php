<?php

use common\helpers\Helpers;
//use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Language as Lx;
?>

<div class="texts-view">

    <div class="row">
        <div class="col-md-12">
            <?php
                $gridColumn = [
                    [
                            'label' => Lx::t('app', 'Code'),
                            'value' => $model->code,
                    ],
                    [
                            'label' => Lx::t('app', 'Text EN'),
                            'format'=>'raw',
                            'value' => $model->text_EN,
                    ],
                    [
                            'label' => Lx::t('app', 'Text FR'),
                            'format'=>'raw',
                            'value' => $model->text_FR,
                    ],
                    [
                            'label' => Lx::t('app', 'Text DE'),
                            'format'=>'raw',
                            'value' => $model->text_DE,
                    ]
                ];

                echo DetailView::widget([
                    'model'      => $model,
                    'attributes' => $gridColumn
                ]);
            ?>
        </div>
    </div>

    <?php echo Helpers::getModalFooter($model,  null, null, [
        'buttons' => ['cancel']
    ]); ?>

    <div class="clearfix"></div>
</div>

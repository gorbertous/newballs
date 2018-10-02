<?php

use common\helpers\Helpers;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Language as Lx;
?>

<div class="texts-view">

    <div class="row">
        <div class="col-md-12">
            <?php
                $gridColumn = [
                    [
                        'label'  => Lx::t('appMenu', 'Company'),
                        'format' => 'html',
                        'value'  => Html::a($model->mandant->Name, Yii::$app->urlManager->baseUrl . '/mandants/'. $model->ID_Mandant, ['title' => 'Go']),
                    ],
                    [
                            'label' => Lx::t('app', 'Code'),
                            'value' => $model->Code,
                    ],
                    [
                            'label' => Lx::t('app', 'Text'),
                            'format'=>'raw',
                            'value' => $model->Text_EN,
                    ],
                    [
                            'label' => Lx::t('app', 'Text'),
                            'format'=>'raw',
                            'value' => $model->Text_FR,
                    ],
                    [
                            'label' => Lx::t('app', 'Text'),
                            'format'=>'raw',
                            'value' => $model->Text_DE,
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

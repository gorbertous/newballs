
<?php
use yii\helpers\Html;
use kartik\tabs\TabsX;


$items = [
    [
        'label'   => '<i class="fas fa-list-ol"></i> ' . Html::encode(Yii::t('modelattr', 'Players')),
        'content' => $this->render('_curseason', [
            'model' => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]),
    ],
    [
        'label'   => '<i class="fas fa-industry"></i> ' . Html::encode(Yii::t('appMenu', 'Club')),
        'content' => $this->render('_hisdata', [
            'model' => $model,
        ]),
    ],
    [
        'label'   => '<i class="fas fa-trophy"></i> ' . Html::encode(Yii::t('modelattr', 'Tournaments')),
        'content' => $this->render('_tournaments', [
            'model' => $model,
        ]),
    ],
];
echo TabsX::widget([
    'items'         => $items,
    'position'      => TabsX::POS_ABOVE,
    'bordered'=>true,
    'encodeLabels'  => false,
    
    'pluginOptions' => [
        'bordered'    => true,
        'sideways'    => true,
        'enableCache' => false
    ],
]);

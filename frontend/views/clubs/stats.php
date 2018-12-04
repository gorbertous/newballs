
<?php
//use yii\helpers\Html;
//use kartik\tabs\TabsX;
//use yii\helpers\Url;
//
//$items = [
//    [
//        'label'   => '<i class="fa fa-balance-scale"></i> ' . Html::encode(Yii::t('app', 'Data')),
//        'content' => $this->render('_hisdata', [
//            'model' => $model,
//        ]),
//    ],
//    [
//        'label'   => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode(Yii::t('app', 'Current Season Stats')),
//        'content' => $this->render('_curseason', [
//            'model' => $model,
//            'searchModel'  => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]),
//    ],
//];
//echo TabsX::widget([
//    'items'         => $items,
//    'position'      => TabsX::POS_ABOVE,
//    'bordered'=>true,
//    'encodeLabels'  => false,
//    'class'         => 'tes',
//    'pluginOptions' => [
//        'bordered'    => true,
//        'sideways'    => true,
//        'enableCache' => false
//    ],
//]);
?>

<div class="tabscus">
    <input type="radio" name="tabs" id="tabone" checked="checked">
    <label for="tabone"><h3 class="panel-title"><span class="fa fa-dashboard"></span> <?= Yii::t('modelattr', 'Players') ?> </h3></label>
    <div class="tabcus">

        <?=
        $this->render('_curseason', [
            'model'        => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider])
        ?>
    </div>

    <input type="radio" name="tabs" id="tabtwo">
    <label for="tabtwo"><h3 class="panel-title"><span class="fa fa-history"></span> <?= Yii::t('appMenu', 'Club') ?> </h3></label>
    <div class="tabcus">
        <?=
        $this->render('_hisdata', [
            'model' => $model])
        ?>

    </div>

    <input type="radio" name="tabs" id="tabthree">
    <label for="tabthree"><h3 class="panel-title"><span class="fa fa-desktop"></span> <?= Yii::t('modelattr', 'Tournaments') ?> </h3></label>
    <div class="tabcus">
         <?= $model->tournament_page?>
    </div>
</div>
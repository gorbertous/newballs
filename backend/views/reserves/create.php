<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Reserves */

$this->title = Yii::t('modelattr', 'Create Reserves');
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Reserves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reserves-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Fees */

$this->title = Yii::t('modelattr', 'Create Fees');
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Fees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fees-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

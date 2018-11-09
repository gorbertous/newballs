<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>Rota Reminder</h2>

<h4>Hi <?= $model->firstname ?> </h4>
<h4>There are still free slots (<?= $count ?>) available for this week games</h4>
<h4>Date : <?= $date->termin_date ?> </h4>
<h4>Location : <?= $date->location->shortAddress ?> </h4>
<h4>We hope you can join us</h4>

<p>For further details please check the  <?= Html::a(' website', Url::home('http')) ?></p>

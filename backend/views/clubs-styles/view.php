<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\models\ClubStyles $model
 */

$this->title = $model->c_css_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Club Styles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="club-styles-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title,
            'type' => DetailView::TYPE_PRIMARY,
        ],
        'attributes' => [
            'c_css_id',
            'c_css',
            'c_menu_image',
            'c_top_image',
            'c_top',
            'c_left',
            'c_menu',
            'c_right',
            'c_footer',
            'c_main_colour_EN',
            'c_main_colour_FR',
            'c_colour_sample',
            'is_active',
        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model->c_css_id],
        ],
        'enableEditMode' => true,
    ]) ?>

</div>

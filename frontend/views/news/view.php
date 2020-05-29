<?php

//use yii\helpers\Html;
use common\helpers\Helpers;
use common\dictionaries\NewsCategories;


/* @var $this yii\web\View */
/* @var $model backend\models\News */

?>

<h3><?= NewsCategories::get($model->category) ?></h3>
<div class="news-view">
    <div class="row">
        <div class="col-md-6">
            <?= $model->titleFB .' ('. Yii::t('diag', 'by'). ' '. $model->createUserName. ')'?><br>
        </div>

        <div class="col-md-2 col-md-offset-3">
            <?=  $model->getIconPreviewAsHtml('ajaxfilefeatured', 90); ?><br>
           
        </div>
    </div>
    <br>

    <br>
    <div class="row">
        <div class="col-md-12">
            <?= $model->contentFB ?><br>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-12">
            <?php if (Yii::$app->controller->action->id == 'view') { ?>
                <div class="clearfix"></div>
                <?php echo Helpers::getModalFooter($model, $model->id, 'view', [
                    'buttons' => ['cancel']
                ]); ?>
            <?php } ?>
        </div>
    </div>

    <div class="clearfix"></div>
</div>

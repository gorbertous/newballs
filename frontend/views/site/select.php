<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('index', Yii::$app->name);

$css = <<< CSS
    .select-button{
        border: none;
        background-color: transparent;
    }
CSS;
Yii::$app->view->registerCss($css);

?>

<div class="text-info">
    <h2>
        <?= Yii::t('app', 'Welcome'); ?> <strong><?= Yii::$app->user->member->name; ?></strong>,
        <?= Yii::t('app', 'choose a club below or'); ?>
        <a href="/logout"><?= Yii::t('app', 'logout'); ?></a>.
    </h2>
</div>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'select-form'
    ]
]); ?>

    
    <?php if(Yii::$app->user->can('team_member')) : ?>
        <input type="text" class="text-input" id="filter" autocomplete="off" placeholder="<?= Yii::t('app', 'Search for a club name');?>..." />

        <span class="filter-count"></span>
        
        <nav>
        <ul>
        <?php foreach ($model as $m) { ?>
            <li>
                <h4><?= $m->name ?></h4>
                <?= Html::submitButton('<img src="'. $m->getThumbnailUrl($m->logo, [100, 100]) .
                    '" alt="'. $m->name .'" class="img-thumbnail center-block" style="padding: 5px 5px 5px 5px;">',
                    ['class' => '',
                    'name' => 'club',
                    'value' => $m->c_id,
                    'id' => 'select-'.$m->c_id]) ?>

                <span class="hidden-text"><?= $m->name; ?></span>
            </li>
        <?php } ?>
        </ul>
    </nav>
        
    <?php else : ?>
        <nav>
            <ul>
            <?php foreach ($model as $m) { ?>
                <li>
                    <h4><?= $m->club->name ?></h4>
                    <?= Html::submitButton('<img src="'. $m->club->getThumbnailUrl($m->club->logo, [100, 100]) .
                        '" alt="'. $m->name .'" class="img-thumbnail center-block" style="padding: 5px 5px 5px 5px;">',
                        ['class' => '',
                        'name' => 'club',
                        'value' => $m->c_id,
                        'id' => 'select-'.$m->c_id]) ?>

                    <span class="hidden-text"><?= $m->name; ?></span>
                </li>
            <?php } ?>
            </ul>
        </nav>
    <?php endif; ?>

<?php ActiveForm::end(); ?>


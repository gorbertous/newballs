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
        <?= Yii::t('app', 'Welcome'); ?> <strong><?= Yii::$app->user->contact->Fullname; ?></strong>,
        <?= Yii::t('app', 'choose a club below or'); ?>
        <a href="/backend/logout"><?= Yii::t('app', 'logout'); ?></a>.
    </h2>
</div>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'select-form'
    ]
]); ?>

    <?php if (Yii::$app->user->can('team_member')) { ?>
        <input type="text" class="text-input" id="filter" autocomplete="off" placeholder="<?= Yii::t('app', 'Search for a company name');?>..." />

        <span class="filter-count"></span>
    <?php } ?>

    <nav>
        <ul>
        <?php foreach ($model as $m) { ?>
            <li>
                <?= Html::submitButton('<img src="'. $m->getThumbnailUrl($m->JPG_Logo, [100, 100]) .
                    '" alt="'. $m->Name .'" class="img-thumbnail center-block" style="padding: 5px 5px 5px 5px;">',
                    ['class' => '',
                    'name' => 'mandant',
                    'value' => $m->ID_Mandant,
                    'id' => 'select-'.$m->ID_Mandant]) ?>

                <span class="hidden-text"><?= $m->Name; ?></span>
            </li>
        <?php } ?>
        </ul>
    </nav>

<?php ActiveForm::end(); ?>


<?php

use yii\widgets\DetailView;

use common\helpers\Helpers;
use kartik\grid\GridView;

/** @var $modelMedvisits \backend\models\Medvisits */
/** @var $modelTrainings \backend\models\Trainingshistory */
/** @var $modelAuthorizations \backend\models\Authorizations */
/** @var $modelEmpContracts \backend\models\Empcontracts */
/** @var $model \backend\models\Qr */

if ( empty($model->authorizations->contact->Languagepref) ) {
    Yii::$app->language = 'en';
} else {
    Yii::$app->language = strtolower($model->authorizations->contact->Languagepref);
}

Yii::$app->session->set('multiemp', Helpers::getConfig('Mandant', 'Multiemployer', $model->authorizations->ID_Mandant));

if ( empty($model->authorizations->contact->mandant->ContLanguages) ) {
    Yii::$app->session->set('club_languages', Yii::$app->contLang->defaultClubLanguages);
} else {
    Yii::$app->session->set('club_languages', explode('.', $model->authorizations->contact->mandant->ContLanguages));
}

$UIlanguage = strtoupper(Yii::$app->language);
$manlan     = Yii::$app->session->get('club_languages');

if (in_array($UIlanguage, $manlan)) {
    // content language is UI language
    Yii::$app->session->set('_content_language', '_' . $UIlanguage);
} else {
    // content language us primary language
    Yii::$app->session->set('_content_language', '_' . $manlan[0]);
}

// fallback to primary language and then to FR
Yii::$app->session->set('_fallback1_language', '_' . $manlan[0]);
Yii::$app->session->set('_fallback2_language', '_FR');

$gridColumn = [
    [
        'attribute' => 'Nationality',
        'label' => Yii::t('modelattr', 'Nationality'),
        'value' => $model->authorizations->contact->countrytranslated
    ],
    [
        'attribute' => 'Position',
        'label' => Yii::t('modelattr', 'Position')
    ],

    [
        'attribute' => 'EmailPrivate',
        'label' => Yii::t('modelattr', 'Email (private)')
    ],
    [
        'attribute' => 'PhonePrivate',
        'label' => Yii::t('modelattr', 'Phone (private)')
    ],
    [
        'attribute' => 'Address',
        'label' => Yii::t('modelattr', 'Address'),
        'format'=>'html',
        'value' => $model->authorizations->contact->fullAddress
    ],
    [
        'attribute' => 'StartDate',
        'label' => Yii::t('modelattr', 'Contract start'),
        'format' => ['date', 'dd/MM/yyyy']
    ],
    [
        'attribute' => 'EndDate',
        'label' => Yii::t('modelattr', 'Contract end'),
        'format' => ['date', 'dd/MM/yyyy']
    ],
    [
        'attribute' => 'Seniority',
        'label' => Yii::t('modelattr', 'Seniority')
    ],
    [
        'attribute' => 'DistanceKm',
        'label' => Yii::t('modelattr', 'Distance home/work (km)')
    ],
    [
        'attribute' => 'DistanceMin',
        'label' => Yii::t('modelattr', 'Distance home/work (min)')
    ],

    [
        'attribute' => 'Worktime',
        'label' => Yii::t('modelattr', 'Worktime'),
        'value' => $model->authorizations->contact->Worktime.'%',
    ],
    [
        'attribute' => 'Studies',
        'label' => Yii::t('modelattr', 'Studies')
    ],
    [
        'attribute' => 'Diploma',
        'label' => Yii::t('modelattr', 'Diploma')
    ]
];


?>

<div id="header">
    <img class="mandant-img" src="<?= $model->authorizations->contact->mandant->getThumbnailUrl($model->authorizations->contact->mandant->JPG_Logo, [200, 200]); ?>" >

    <img class="user-img" src="<?= $model->authorizations->contact->getThumbnailUrl($model->authorizations->contact->Photo, [200, 200]); ?>" >

    <br /><br />
    <h1><?= $model->authorizations->contact->Fullname; ?></h1>
</div>

<div class="height"></div>
<div class="height"></div>
<br /><br /><br /><br />

<div class="text">
    <?= DetailView::widget([
        'model' => $model->authorizations->contact,
        'attributes' => $gridColumn
    ]); ?>
</div>


<pagebreak />


<div id="header">
    <h1>• <?= Yii::t('appMenu', 'Medical visits'); ?></h1>
    <span class="header"><?= $model->authorizations->contact->mandant->Name; ?> ~ <?= $model->authorizations->contact->Fullname; ?></span>
</div>

<div class="height"></div>

<?php

$medvisitsGrid = [
    [
        'class' => 'yii\grid\SerialColumn',
        'contentOptions' => ['style' => 'width: 5px;']
    ],

    [
        'attribute'      => 'Requestdate',
        'encodeLabel'    => false,
        'format'         => ['date', 'dd/MM/yyyy'],
        'contentOptions' => ['style' =>'width: 100px;']
    ],

    [
        'attribute' => 'ID_Empcontract',
        'value'     => function ($model) {
            return $model->empcontract->Position;
        },
        'contentOptions' => ['style' =>'width: 100px;']
    ],

    [
        'attribute' => 'ID_Doctor',
        'value'     => function ($model) {
            return !empty($model->doctor->Name) ? $model->doctor->Name : '';
        },
        'contentOptions' => ['style' =>'width: 100px;']
    ],

    [
        'attribute' => 'ID_Healthcenter',
        'value'     => function ($model) {
            return $model->healthcenter->Name;
        },
        'contentOptions' => ['style' =>'width: 150px;']
    ],

    [
        'attribute' => 'Examreason',
        'value'     => function ($model) {
            return $model->Examreason;
        },
        'contentOptions' => ['style' =>'width: 90px;']
    ],

    [
        'attribute'   => 'Appointdate',
        'encodeLabel' => false,
        'format'      => 'raw',
        'value'       => function ($model) {
            return Yii::$app->formatter->asDate($model->Appointdate, 'dd/MM/yyyy') . ($model->Appointtime > 0 ? ' ' . Yii::$app->formatter->asTime($model->Appointtime, 'HH:mm') : '');
        },
        'contentOptions' => ['style' =>'width: 100px;']
    ],

    [
        'attribute'   => 'Nextdate',
        'encodeLabel' => false,
        'format'      => ['date', 'dd/MM/yyyy'],
        'contentOptions' => ['style' =>'width: 100px;']
    ],

    [
        'attribute'   => 'Passed',
        'encodeLabel' => false,
        'format'      => 'raw',
        'value'       => function ($model) {
            if ($model->Passed === 1) {
                return Yii::t('app', 'Yes');
            } else {
                return Yii::t('app', 'No');
            }
        },
        'contentOptions' => ['style' =>'width: 80px;']
    ]
];

echo GridView::widget([
    'dataProvider'    => $modelMedvisits,
    'layout'          => '{items}',
    'columns'         => $medvisitsGrid,
    'showPageSummary' => false,
    'options' => ['style' =>'width: 100%; font-size: 30px;']
]);

?>


<!-- ////////################################///////// -->

<pagebreak />


<div id="header">
    <h1>• <?= Yii::t('appMenu', 'Trainings'); ?></h1>
    <span class="header"><?= $model->authorizations->contact->mandant->Name; ?> ~ <?= $model->authorizations->contact->Fullname; ?></span>
</div>

<div class="height"></div>

<?php

$trainingsHistGrid = [
    [
        'class' => 'yii\grid\SerialColumn'
    ],

    [
        'attribute' => 'ID_Training',
        'label'     => Yii::t('modelattr', 'Training'),
        'value'     => function ($model) {
            return $model->training->levelDescription;
        }
    ],

    [
        'attribute' => 'Trainingdate',
        'label'     => Yii::t('modelattr', 'Date'),
        'format'    => ['date', 'dd/MM/yyyy']
    ],


    [
        'attribute' => 'Nextdate',
        'format'    => ['date', 'dd/MM/yyyy']
    ],

    [
        'attribute' => 'Passed',
        'label'     => Yii::t('modelattr', 'Passed'),
        'hAlign'    => GridView::ALIGN_CENTER,
        'format'    => 'raw',
        'value' => function ($model) {
            if ($model->Passed == 1) {
                return 'Oui';
            } else {
                return 'Non';
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter'     => [
            -1 => Yii::t('modelattr', 'All'),
            0  => Yii::t('modelattr', 'No'),
            1  => Yii::t('modelattr', 'Yes')
        ],
        'width'      => '100px;',
    ]
];

echo GridView::widget([
    'dataProvider'    => $modelTrainings,
    'layout'          => '{items}',
    'columns'         => $trainingsHistGrid,
    'showPageSummary' => false
]);

?>

<!-- ////////################################///////// -->

<pagebreak />

<div id="header">
    <h1>• <?= Yii::t('appMenu', 'Authorizations'); ?></h1>
    <span class="header"><?= $model->authorizations->contact->mandant->Name; ?> ~ <?= $model->authorizations->contact->Fullname; ?></span>
</div>

<div class="height"></div>

<?php

$authorizationsGrid = [
    [
        'class' => 'yii\grid\SerialColumn'
    ],
    'Temporary',
    'Nextdate'
];

echo GridView::widget([
    'dataProvider'    => $modelAuthorizations,
    'layout'          => '{items}',
    'columns'         => $authorizationsGrid,
    'showPageSummary' => false
]);

?>

<!-- ////////################################///////// -->

<pagebreak />

<div id="header">
    <h1>• <?= Yii::t('appMenu', 'Postings'); ?></h1>
    <span class="header"><?= $model->authorizations->contact->mandant->Name; ?> ~ <?= $model->authorizations->contact->Fullname; ?></span>
</div>

<div class="height"></div>

<?php

$empContractsGrid = [
    [
        'class' => 'yii\grid\SerialColumn'
    ],

    [
        'attribute' => 'ID_Workunit',

        'value' => function ($model) {
            return isset($model->workunit->nameFB) ? $model->workunit->nameFB : null;
        },

        'label'       => Yii::t('modelattr', 'Workunit'),
        'encodeLabel' => false
    ],

    [
        'attribute'   => 'ID_Workplace',
        'value'       => 'workplace.levelDescription',
        'label'       => Yii::t('modelattr', 'Workplace'),
        'encodeLabel' => false
    ],

    [
        'attribute'   => 'ID_Employer',
        'visible'     => Yii::$app->session->get('multiemp'),
        'label'       => Yii::t('modelattr', 'Employer'),
        'encodeLabel' => false,
        'value'       => 'employer.Name',
    ],

    [
        'attribute'      => 'Start',
        'label'          => Yii::t('modelattr', 'Start'),
        'encodeLabel'    => false,
        'format'         => ['date', 'dd/MM/yyyy'],
        'contentOptions' => ['style' => 'width:30px;'],
    ],

    [
        'attribute'      => 'Stop',
        'label'          => Yii::t('modelattr', 'Stop'),
        'encodeLabel'    => false,
        'format'         => ['date', 'dd/MM/yyyy'],
        'contentOptions' => ['style' => 'width:30px;'],
    ],

    [
        'label'          => Yii::t('modelattr', '%time'),
        'encodeLabel'    => false,
        'attribute'      => 'Worktime',
        'contentOptions' => ['style' => 'width:20px;'],
    ]
];

echo GridView::widget([
    'dataProvider'    => $modelEmpContracts,
    'layout'          => '{items}',
    'columns'         => $empContractsGrid,
    'showPageSummary' => false
]);

?>

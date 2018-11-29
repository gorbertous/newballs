<?php
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RotaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';

$gridColumn = [
    [
        'format'    => 'raw',
        'label'          => 'Date',
        'value'     => function($model) {
            $dispdate = Yii::$app->formatter->asDate($model->termin->termin_date);
            $disptime = Yii::$app->formatter->asTime($model->termin->termin_date, 'short');

            $url = Url::toRoute(['reserves/insert', 'id' => $model->termin_id]);
            $link = Html::a('click here to put your name on the reserves list!', $url, [
                        'title' => Yii::t('app', 'add your name on the reserves list'),
                        'class' => 'text-success',
                        'data'  => [
                            'confirm' => Yii::t('app', 'Warning, the reserve list operates on the first comes first served basis, in case a slot becomes available, the club admin will put your name on the rota '),
                            'method'  => 'post',
                        ],
            ]);
            if (isset($model->termin->reserves)) {
                $list = [];
                foreach ($model->termin->reserves as $reserves) {
                    $name = $reserves->member->name;
                    if (!in_array($name, $list)) {
                        array_push($list, $name);
                    }
                }
                $reserves_list = join('<br>', $list);
            } else {
                $reserves_list = '';
            }
            $final_list = !empty($reserves_list) ? 'Current Reserves List:<br>' . $reserves_list : '';

            $slots_notification = $model->getSlotsLeft($model->termin_id) == 0 ? 'All the slots are taken - ' . $link : '<small> (' . $model->getSlotsLeft($model->termin_id) . ' Slots Left) </small>';
            return $dispdate . ' at ' . $disptime . '   <small>- Location: ' . $model->termin->location->address . '</small>' . $slots_notification . $final_list;
        },
        'group'             => true,
        'groupedRow'        => true,
        'groupOddCssClass'  => 'kv-grouped-row',
        'groupEvenCssClass' => 'kv-grouped-row'
    ],
    [
        'label'          => 'Court',
        'value'     => function($model) {
            $url = Url::toRoute(['rota/bookcourt', 'id' => $model->termin_id, 'id2' => $model->court_id]);
            $link = Html::a('Court not yet booked!', $url, [
                        'title' => Yii::t('app', 'book a court'),
                        'class' => 'text-success',
                        'data'  => [
                            'confirm' => Yii::t('app', 'You are confirming that you have booked this court!'),
                            'method'  => 'post',
                        ],
            ]);
            $booked = $model->isCourtBooked($model->termin_id, $model->court_id);
            $booked_by = !empty($booked) ? 'Court booked by ' . $booked->bookedBy->name : $link;
            //show court booking link
            $show_booking_link = Yii::$app->session->get('club_court_booking') ? $booked_by : '';
            return '<strong>Court No : ' . $model->court_id . '</strong>' . $show_booking_link;
        },
        'format'            => 'raw',
        //'label' => Yii::t('app', 'Court No'),
        'group'             => true,
        'subGroupOf'        => 0,
        'groupedRow'        => true,
        'groupOddCssClass'  => 'kv-group-even',
        'groupEvenCssClass' => 'kv-group-even'
    ],
    [
        'attribute'      => 'slot_id',
        'label'          => Yii::t('app', 'Slot No'),
        'encodeLabel'    => false,
        'format'         => 'raw',
        'headerOptions'  => ['style' => 'text-align:center'],
        'contentOptions' => function ($model, $key, $index, $column) {
            switch ($model->slot_id) {
                case 1:
                    $bg_color = '#B3C7DC';
                    break;
                case 2:
                    $bg_color = '#668EB9';
                    break;
                case 3:
                    $bg_color = '#FFC2BB';
                    break;
                case 4:
                    $bg_color = '#FF8883';
                    break;
            }
            return ['style' => 'background-color:'
                . $bg_color];
        },
        'value' => function($model) {

            return '<strong>' . $model->slot_id . '</strong>';
        },
        'enableSorting' => false,
        'width'         => '50px;',
    ],
    [
        'attribute' => 'member_id',
        'label'     => Yii::t('app', 'Member'),
        'format'    => 'raw',
        'value'     => function($model) {
            if ($model->member_id == 1) {
                $url = Url::toRoute(['rota/insert', 'id' => $model->id]);
                $link = Html::a($model->member->name, $url, [
                            'title' => Yii::t('app', 'Click the link to put your name on the rota'),
                            'class' => 'text-success',
                            'data'  => [
                                'confirm' => Yii::t('app', 'Warning, clicking on the link you are commiting yourself to play on ' . $model->termin->termin_date),
                                'method'  => 'post',
                            ],
                ]);
                return Yii::$app->session->get('member_has_paid') ? '<strong>' . $link . '</strong>' : '<strong>' . $model->member->name . '</strong>';
            } else {
                $class = $model->tokens ? 'text-danger' : 'text-primary';
                $iscoach = isset($model->member->memType) && ($model->member->memType->mem_type_id == 5) ? ' <span class="badge bg-red pull-right">Coach</span>' : '';
                return Html::tag('strong', $model->member->name, ['class' => $class]) . $iscoach;
            }
        },
    ],
    [
        'attribute' => 'tokens',
        'hAlign'    => GridView::ALIGN_CENTER,
        'format'    => 'raw',
        'value'     => function($model)use ($redcross, $greencheck) {
            if ($model->tokens == 1) {
                return $greencheck;
            } else {
                return $redcross;
            }
        },
    ],
    [
        'attribute' => 'late',
        'hAlign'    => GridView::ALIGN_CENTER,
        'format'    => 'raw',
        'value'     => function($model)use ($redcross, $greencheck) {
            if ($model->late == 1) {
                return $greencheck;
            } else {
                return $redcross;
            }
        },
    ],
];



echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'      => $gridColumn,
    'id'           => 'gridview-club-id',
        ]
);
?>

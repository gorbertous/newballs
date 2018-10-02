<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model backend\models\Members */

$this->title = Yii::t('modelattr', 'View {modelClass}', [
    'modelClass' => 'Members',
]) . ' #' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="row">
    <div class="col-md-12">
        
        <?php         Panel::begin(
        [
        'header' => Html::encode($this->title),
        'icon' => 'users',
        ]
        )
         ?> 

        <div class="members-view">
            
            <?= \cebe\gravatar\Gravatar::widget([
                'email' => 'gorbertous@gmail.com',
                'options' => [
                    'alt' => 'gorbertous'
                ],
                'size' => 32
            ]) ?>


            <?= Html::a(Yii::t('modelattr', 'Manage'), ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Create'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Update'), ['update', 'id' => $model->member_id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Delete'), ['delete', 'id' => $model->member_id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
            'confirm' => Yii::t('modelattr', 'Are you sure you want to delete this item?'),
            'method' => 'post',
            ],
            ]) ?>
            
            
            
            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'member_id',
            'user_id',
            'c_id',
            'mem_type_id',
            'grade_id',
            'title',
            'firstname',
            'lastname',
            'gender',
            'email:email',
            'photo',
            'orig_photo',
            'phone',
            'phone_office',
            'phone_mobile',
            'address',
            'zip',
            'city',
            'co_code',
            'country_id',
            'nationality',
            'dob',
            'is_admin',
            'is_organiser',
            'is_active',
            'has_paid',
            'is_visible',
            'ban_scoreupload',
            'coaching',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            ],
            ]) ?>
        </div>

        <?php Panel::end() ?> 
    </div>
</div>



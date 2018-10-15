<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Members */

$this->title = Yii::t('modelattr', 'View {modelClass}', [
    'modelClass' => 'Members',
]) . ' #' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';

?>



<div class="row">
    <div class="col-md-12">
       
        <div class="members-view">
          
            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
            'club.name',
            
            'grade_id',
             [
                'label'          => Yii::t('modelattr', 'Name'),
                'value'          => Yii::t('modelattr', 'Name'),
            ],
            
            'email:email',
            [
                'label'          => Yii::t('modelattr', 'Photo'),
                'format'         => 'raw',
                'value'          => function ($model) {
                    $gravatar = isset($model->user->email) ? $model->getGravatar($model->user->email) : null;
                    return !empty($model->photo) ? $model->getIconPreviewAsHtml('ajaxfileinputPhoto', 60) : $gravatar;
                }
            ],
            [
                'label'          => Yii::t('modelattr', 'Membership'),
                'value'          => 'memType.nameFB',
            ],
            [
                'label'          => Yii::t('modelattr', 'Level'),
                'format'         => 'raw',
                'value'          => function ($model) {
                    return isset($model->grade_id) ? common\dictionaries\Grades::get($model->grade_id) : null;
                }
            ],
           
            'phone',
            'phone_mobile',
            [
                'label'          => Yii::t('modelattr', 'Address'),
                'format'         => 'raw',
                'value'          => function ($model) {
                    $address = !empty($model->address) ? $model->user->fullAddress : null;
                    return $address;
                }
            ],
           'fullAddress',
            
            'nationality',
           
            'is_admin',
            'is_organiser',
            'is_active',
            'has_paid',
            'is_visible',
            'ban_scoreupload',
            'coaching',
            
            ],
            ]) ?>
        </div>

         
    </div>
</div>



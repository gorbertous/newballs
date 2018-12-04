<div class="row">
    <div class="col-md-4">                        
        <?php
            foreach ($game as $value) {
                if ($value->slot_id <= 2 && $value->member_id > 1) {
                    echo '<span class="custom-margin">' . $value->member->name . '</span>';
                    echo '<br>';
                }
            }
        ?>
    </div>
    <div class="col-md-4">
        <?php if (!empty($score->set_one)): ?>
        <div class="row">
            <div class="col-md-12"> 
                <label class="control-label"><?= Yii::t('modelattr', 'Set') ?> 1</label>
                <span class="custom-margin">
                <?= str_replace(',',' : ',$score->set_one) ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($score->set_two)): ?>
        <div class="row">
            <div class="col-md-12"> 
                <label class="control-label"><?= Yii::t('modelattr', 'Set') ?> 2</label>
                <span class="custom-margin">
                <?= str_replace(',',' : ',$score->set_two) ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($score->set_three)): ?>
        <div class="row">
            <div class="col-md-12"> 
                <label class="control-label"><?= Yii::t('modelattr', 'Set') ?> 3</label>
                <span class="custom-margin">
                <?= str_replace(',',' : ',$score->set_three) ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($score->set_four)): ?>
        <div class="row">
            <div class="col-md-12"> 
                <label class="control-label"><?= Yii::t('modelattr', 'Set') ?> 4</label>
                <span class="custom-margin">
                <?= str_replace(',',' : ',$score->set_four) ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($score->set_five)): ?>
        <div class="row">
            <div class="col-md-12">
                <label class="control-label"><?= Yii::t('modelattr', 'Set') ?> 5</label>
                <span class="custom-margin">
                <?= str_replace(',',' : ',$score->set_five) ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="col-md-4">                        
        <?php
            foreach ($game as $value) {
                if ($value->slot_id > 2 && $value->member_id > 1) {
                    echo '<span class="custom-margin">' . $value->member->name . '</span>';
                    echo '<br>';
                }
            }
        ?>
    </div>
</div>


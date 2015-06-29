<div class="container">
  <?php
  $this->renderPartial('/template/navbar');
  $form = $this->beginWidget('CActiveForm', array(
      'enableClientValidation' => true,
      'clientOptions' => array(
          'validateOnSubmit' => true,
      ),
      'htmlOptions' => array(
          'class' => 'permission-add assign-role',
      )
  ));
  ?>

  <div class="control-group">
    <?php echo $form->labelEx($model, Yii::t('rbac', 'role'), array('class' => 'control-label')); ?>
    <div class="controls">
      <?php echo $form->textField($model, 'role',   array('placeholder' => Yii::t('rbac', 'Role name'), 'class' => 'custom-textbox', 'readonly' => 'readonly')); ?>
      <span class="help-inline">
        <span class="help-inline">
          <?php echo $form->error($model, 'role'); ?>
        </span>
    </div>
  </div>
  <div class="control-group">
    <?php echo $form->labelEx($model, Yii::t('rbac', 'Select Permission'), array('class' => 'control-label')); ?>
    <div class="controls">
       <ul class="rbac-chechbox">
      <?php
      foreach ($permissions as $key => $permission) {
        $checkBoxValue = array();
        $checkBoxValue['class'] = 'permission-checkbox';
        $checkBoxValue['value'] = $key;
        if (!empty($selectedPermission)) {
          if (in_array($key, $selectedPermission)) {
            $checkBoxValue['checked'] = 'checked';
          }
        }
        ?><li>
          <?php echo $form->checkBox($model, 'permission[]', $checkBoxValue); ?> &nbsp;
          <?php echo $permission;
      } ?> </li> <?php
      ?>
         </ul>
      <span class="help-inline">
        <?php echo $form->error($model, 'permission'); ?>
      </span>
    </div>
  </div>
  <div class="control-group">
    <div class="controls btn-container">
      <?php
      if (isset($model->role_id) && $model->role_id) {
        echo $form->hiddenField($model, 'role_id');
      }
      $buttonText = Yii::t('rbac', 'Assign');
      if (!empty($selectedPermission)) {
        $buttonText = Yii::t('rbac', 'Update');
      }
      ?>
      <?php echo CHtml::submitButton($buttonText, array('class' => 'btn submit-btn large')); ?>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
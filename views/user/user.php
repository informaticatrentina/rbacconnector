<div class="container">
  <?php
  $form = $this->beginWidget('CActiveForm', array(
      'enableClientValidation' => true,
      'clientOptions' => array(
          'validateOnSubmit' => true,
      ),
      'htmlOptions' => array(
          'class' => 'assign-role',
      )
  ));
  ?>

  <div class="control-group">
    <?php echo $form->labelEx($model, 'user_email', array('class' => 'control-label')); ?>
    <div class="controls">
      <?php echo $form->textField($model, 'user_email', array('placeholder' => 'user email', 'class' => 'custom-textbox')); ?>
      <span class="help-inline">
        <span class="help-inline">
          <?php echo $form->error($model, 'user_email'); ?>
        </span>
    </div>
  </div>
  <div class="control-group">
    <?php echo $form->labelEx($model, 'Assign Role', array('class' => 'control-label')); ?>
    <div class="controls">   
      <ul class="rbac-chechbox">
        <?php
        foreach ($roles as $role) {
          $checkBoxValue = array();
          $checkBoxValue['class'] = 'role-checkbox';
          $checkBoxValue['value'] = $role['id'];
          if (!empty($selRoleIds)) {
            if (in_array($role['id'], $selRoleIds)) {
              $checkBoxValue['checked'] = 'checked';
            }
          }
          ?> 
        <li>
          <?php echo $form->checkBox($model, 'role_id[]', $checkBoxValue); ?>  &nbsp;
          <?php echo $role['role']; } ?>
        </li>
      </ul>
    </div>
  </div>
  <div class="control-group">
    <div class="controls btn-container">
      <?php
      if (isset($model->role_id) && $model->role_id) {
        echo $form->hiddenField($model, 'role_id');
      }
      ?>
      <?php echo CHtml::submitButton('Assign', array('class' => 'btn submit-btn large')); ?>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
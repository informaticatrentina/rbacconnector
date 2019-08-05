<div class="container">
  <?php
  $this->renderPartial('/template/navbar');
  $form = $this->beginWidget('CActiveForm', array(
      'clientOptions' => array(
          'validateOnSubmit' => true,
      ),
      'htmlOptions' => array(
          'class' => 'assign-role',
      )
  ));
  $userEmailAttribute = array(
      'placeholder' => Yii::t('rbac', 'User email'),
      'class' => 'custom-textbox'
    );
  if ($model->check_user_status == 'EDIT') {
    $userEmailAttribute['readonly'] = 'readonly';
  }
  $errorAttribute = "style='display: none' ";
  if ($message != '') {
    $errorAttribute = "";
  }
  ?>
<div id="assign-role-error" class="alert-danger col-md-11" <?php echo $errorAttribute; ?> > <?php echo $message; ?> </div>
  <div class="control-group">
    <?php echo $form->labelEx($model, 'user_email', array('class' => 'control-label')); ?>
    <div class="controls"> 
      <?php echo $form->textField($model, 'user_email', $userEmailAttribute); ?>
      <?php echo $form->hiddenField($model, 'check_user_status') ?>
      <span class="help-inline">
        <span class="help-inline">
          <?php echo $form->error($model, 'user_email', array('class' => 'alert-danger col-md-11')); ?>
        </span>
    </div>
  </div>
  <div class="control-group">
    <?php echo $form->labelEx($model, Yii::t('rbac', 'Assign Role'), array('class' => 'control-label')); ?>    
    <div class="controls">
      <select id="user-role" class="form-control editUserRole" name="role_name">
      <option value="">Seleziona..</option>
      <?php
        foreach ($roles as $role) {
      ?>
        <option <?=(isset($role['role']) && isset($selRoleName) && $role['role']==$selRoleName)?('selected="selected"'):(''); ?> value="<?php echo $role['role'] ?>"><?php echo $role['role']; ?></option>        
        <?php }
        ?>
      </select>
    </div>
  </div>
  <div class="control-group">
    <div class="controls btn-container">
      <?php
      if (isset($model->role_id) && $model->role_id) {
        echo $form->hiddenField($model, 'role_id');
      }
      ?>
      <?php echo CHtml::submitButton(Yii::t('rbac', 'Assign'), array('id' => 'assign-roles', 'class' => 'btn submit-btn large')); ?>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
<script>
  var checkUserStatus = "<?php echo $model->check_user_status; ?>";
</script>
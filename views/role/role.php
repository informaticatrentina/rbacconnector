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

  $status = array(Yii::t('rbac', 'Inactive'), Yii::t('rbac', 'Active'));
  $roleAttribute = array(
      'placeholder' => Yii::t('rbac', 'Role name'),
      'class' => 'custom-textbox'
    );
  if ($checkRoleStatus == 'EDIT') {
    $roleAttribute['readonly'] = 'readonly';
  }
  ?>
  <div id="assign-role-error" class="alert-danger col-md-11" style="display: none"></div>
  <div class="control-group">
    <?php echo $form->labelEx($model, Yii::t('rbac', 'role'), array('class' => 'control-label')); ?>
    <div class="controls">
      <?php echo $form->textField($model, 'role', $roleAttribute); ?>
      <span class="help-inline">
        <?php echo $form->error($model, 'role', array('class' => 'alert-danger col-md-11')); ?>
      </span>
    </div>
  </div>
  <div class="control-group">
    <?php echo $form->labelEx($model, Yii::t('rbac', 'status'), array('class' => 'control-label')); ?>
    <div class="controls">
      <?php echo $form->dropDownList($model, 'status', $status, array('class' => 'custom-textbox')); ?>
      <span class="help-inline">
        <?php echo $form->error($model, 'status'); ?>
      </span>
    </div>
  </div>
  <div class="control-group">
    <div class="controls btn-container">
      <?php
      $buttonText = Yii::t('rbac', 'Save');
      if (isset($model->id) && $model->id) {
        echo $form->hiddenField($model, 'id');
        $buttonText = Yii::t('rbac', 'Update');
      }
      ?>
      <?php echo CHtml::submitButton($buttonText, array('class' => 'btn submit-btn large submitRole')); ?>
    </div>
 </div>
 <?php $this->endWidget(); ?>
</div>
<script>
  var checkRoleStatus = "<?php echo $checkRoleStatus; ?>";
</script>
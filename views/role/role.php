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

  $status = array('Inactive', 'Active');
  ?>
  <div class="control-group">
    <?php echo $form->labelEx($model, 'role', array('class' => 'control-label')); ?>
    <div class="controls">
      <?php echo $form->textField($model, 'role', array('placeholder' => 'Role name', 'class' => 'custom-textbox')); ?>
      <span class="help-inline">
        <?php echo $form->error($model, 'role'); ?>
      </span>
    </div>
  </div>
  <div class="control-group">
    <?php echo $form->labelEx($model, 'status', array('class' => 'control-label')); ?>
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
      $buttonText = 'Save';
      if (isset($model->id) && $model->id) {
        echo $form->hiddenField($model, 'id');
        $buttonText = 'Update';
      }
      ?>
      <?php echo CHtml::submitButton($buttonText, array('class' => 'btn submit-btn large')); ?>
    </div>
 </div>
 <?php $this->endWidget(); ?>
</div>
<div class="container">
  <?php $this->renderPartial('/template/navbar'); ?>
  <?php if (empty($users)) { ?>
  <div class="block-msg">
    <div class="row" style="margin-top:50px;">
      <div class="span10">
        <div style="padding:25px 25px;">
          <center>
            <?php echo Yii::t('rbac', 'No role has been defined yet. Click on <strong>add role</strong> button to create a role.'); ?>
          </center>
        </div>
      </div>
    </div>
  </div>
  <?php } else {
    $form = $this->beginWidget('CActiveForm', array(
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array(
            'class' => 'pull-left',
        )
     ));
  ?>
  <div class="inner-form">
    <div id = "error-message" style="float:right; margin-right: 100px;"></div>
    <div class="inner-form" id="table">
      <table class="container table table-bordered table-hover index-table table-responsive">
        <thead>
          <tr>
            <th><?php echo Yii::t('rbac', 'S.No.'); ?></th>
            <th><?php echo Yii::t('rbac', 'User email'); ?></th>
            <th><?php echo Yii::t('rbac', 'Role'); ?></th>
            <th><?php echo Yii::t('rbac', 'Action'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          foreach ($users as $user) {
            $roleAssigned = false;
            ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $user['email']; ?></td>
              <td>
                <ul>
                <?php
                  foreach ($user['role'] as $rol) {
                    ?><li><?php
                    echo $rol['role'];
                    ?></li><?php
                    $roleAssigned = true;
                  }
                ?>
                <ul>
              </td>
              <?php
              $actionText = Yii::t('rbac', 'Assign Role');
              if ($roleAssigned) {
                $actionText = Yii::t('rbac', 'Change Role');
              } ?>
              <td><?php echo CHtml::link($actionText, array('/rbacconnector/user/assign?id=' . $user['user_id'])); ?></td>
            </tr>
           <?php }?>
          </tbody>
        </table>
      </div>
    </div>
    <?php $this->endWidget(); } ?>
</div>
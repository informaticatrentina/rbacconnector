<div class="container">
  <?php $this->renderPartial('/template/navbar'); ?>
  <?php if (empty($roles)) { ?>
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
  <?php
  } else {
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
        <table class="container table table-bordered table-hover index-table">
          <thead>
            <tr>
              <th><?php echo Yii::t('rbac', 'S.No.'); ?></th>
              <th><?php echo Yii::t('rbac', 'Role'); ?></th>
              <th><?php echo Yii::t('rbac', 'Permission'); ?></th>
              <th><?php echo Yii::t('rbac', 'Action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            foreach ($roles as $key => $role) {
              $roleStatus = 'Active';
              if ($role['status'] == INACTIVE) {
                $roleStatus = 'Inactive';
              }
              ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $role['role']; ?></td>
                <td><?php echo Yii::t('rbac', $roleStatus); ?></td>
                <td><?php echo CHtml::link(Yii::t('rbac', 'Edit'), array('/rbacconnector/role/edit?id=' . $role['id'])); ?> |
                    <?php echo CHtml::link(Yii::t('rbac', 'Delete'), array('/rbacconnector/role/delete?id=' . $role['id']), array('class' => 'delete-product', 'confirm' => Yii::t('rbac', 'Are you sure you want to delete'))); ?>
                </td>
              </tr>
              <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php $this->endWidget(); } ?>
</div>
<div class="container">
  <div class="main">
    <?php echo CHtml::link('Add Role', array('/rbacconnector/role/add'), array('class' => 'btn pull-left out-L create-btn')); ?>
  </div>
  <?php if (empty($roles)) { ?>
    <div class="block-msg">
      <div class="row" style="margin-top:50px;">
        <div class="span10">
          <div style="padding:25px 25px;">    
            <center>
              Role is not added. Click on <b>add role</b> button for create role. 
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
              <th>S.No.</th>
              <th>Role</th>          
              <th>Status</th>
              <th>Action</th>         
            </tr>
          </thead>
          <tbody>
          <tbody>
            <?php
            $i = 1;
            foreach ($roles as $key => $role) {
              $roleStatus = 'Active';
              if ($role['status'] == 0) {
                $roleStatus = 'Incatve';
              }
              ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $role['role']; ?></td>
                <td><?php echo $roleStatus; ?></td>
                <td><?php echo CHtml::link('Edit', array('/rbacconnector/role/edit?id=' . $role['id'])); ?> |
                    <?php echo CHtml::link('Delete', array('/rbacconnector/role/delete?id=' . $role['id']), array('class' => 'delete-product', 'confirm' => 'Are you sure You want to Delete')); ?>
                </td>
              </tr>
              <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php $this->endWidget(); } ?>
</div>
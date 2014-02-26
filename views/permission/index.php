<div class="container">  
  <?php if (empty($roles)) { ?>
    <div class="main">
      <?php echo CHtml::link('Add Role', array('/rbac/role/add'), array('class' => 'btn pull-left out-L create-btn')); ?>
    </div>
    <div class="block-msg">
      <div class="row" style="margin-top:50px;">
        <div class="span10">
          <div style="padding:25px 25px;">    
            <center>
              Role is not added for assign permission. Click on <b>add role</b> button for create role. 
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
              <th>Permission</th>
              <th>Action</th>         
            </tr>
          </thead>
          <tbody>
          <tbody>
            <?php
            $i = 1;
            foreach ($roles as $role) {
              $permissionExist = false;
              ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $role['role']; ?></td>
                <td>
                  <ul>
                    <?php
                    if (array_key_exists($role['id'], $permissions)) {
                      foreach ($permissions[$role['id']]['permission'] as $permission) {
                        ?> <li> <?php echo $permission; ?></li><?php
                        $permissionExist = true;
                      }
                    }
                    ?>
                  </ul>
                </td>            
                <?php
                $actionText = 'Assign Permission';
                if ($permissionExist) {
                  $actionText = 'Change Pemission';
                }
                ?>
                <td><?php echo CHtml::link($actionText, array('/rbac/permission/assign?id=' . $role['id'])); ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php $this->endWidget(); } ?>
</div>
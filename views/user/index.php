<div class="container">
  <?php $this->renderPartial('/template/navbar'); ?>
  <?php if (empty($users)) { ?>
  <div class="block-msg">
    <div class="row" style="margin-top:50px;">
      <div class="span10">
        <div style="padding:25px 25px;">    
          <center>
            Role is not assigned to user. Click on <b>assign role</b> button for assign role. 
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
            <th>S.No.</th>
            <th>User email</th>          
            <th>Role</th>
            <th>Action</th>         
          </tr>
        </thead>
        <tbody>
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
              $actionText = 'Assign Role';
              if ($roleAssigned) {
                $actionText = 'Change Role';
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
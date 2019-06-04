<div class="container">
  <?php $this->renderPartial('/template/navbar'); ?>
  <?php if (empty($users)) { ?>
  <div class="block-msg">
    <div class="row" style="margin-top:50px;">
      <div class="span10">
        <div style="padding:25px 25px;">
          <center>
            <?php echo 'Attenzione, nessun utente registrato nel database. Contattare, l\'amministratore di sistema.'; ?>
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
  <a href="/rbacconnector/user/export" class="btn btn-primary" style="margin-bottom:10px;">          Esporta CSV            </a>
    <div id = "error-message" style="float:right; margin-right: 100px;"></div>
    <div class="inner-form" id="table">
      <table id="rbac_user_table" class="container table table-bordered table-hover index-table table-responsive">
        <thead>
          <tr>
            <th><?php echo Yii::t('rbac', 'S.No.'); ?></th>
            <th><?php echo Yii::t('rbac', 'User email'); ?></th>
            <th><?php echo Yii::t('rbac', 'Role'); ?></th>
            <th><?php echo Yii::t('rbac', 'Gdpr Policy'); ?></th>
            <th><?php echo Yii::t('rbac', 'Gdpr Date'); ?></th>
            <th><?php echo Yii::t('rbac', 'Gdpr Date Deleted'); ?></th>            
            <th><?php echo Yii::t('rbac', 'Action'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($users as $user) {         
            ?>
            <tr>
              <td><?php echo $user['id']; ?></td>
              <td><?php echo $user['email']; ?></td>              
              <td><?php echo $user['role']; ?></td> 
              <td><?php echo $user['gdpr']; ?></td>
              <td><?php echo $user['gdpr_date']; ?></td>
              <td><?php echo $user['gdpr_date_deleted']; ?></td>
              <?php
              $actionText = Yii::t('rbac', 'Assign Role');
              if ($user['role']!='N.D') {
                $actionText = Yii::t('rbac', 'Change Role');
              } ?>
              <td><?php echo CHtml::link($actionText, array('/rbacconnector/user/assign?id=' . $user['user_id'])); ?> | 
                <?php echo CHtml::link(Yii::t('rbac', 'Disabled User'), array('/rbacconnector/user/disable?id=' . $user['user_id']), array('class' => 'disable_rbac_users')); ?>
            </td>
            </tr>
           <?php }?>
          </tbody>
        </table>  
        <?php if(isset($totalPages) && $totalPages>1) { ?>
        <nav>
          <ul class="pagination" style="display: inline;">
            <li>  
              <a href="<?php echo Yii::app()->request->hostInfo.'/rbacconnector/user/index?page='.$previous; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <?php for($i=1;$i<$totalPages+1;$i++) { ?>
            <?=(isset($page_sel) && $page_sel==$i)?('<li class="active">'):('<li>'); ?>
              <a href="<?php echo Yii::app()->request->hostInfo.'/rbacconnector/user/index?page='.$i; ?>"><?php echo $i; ?></a></li>
            <?php } ?>
            <li>
            <a href="<?php echo Yii::app()->request->hostInfo.'/rbacconnector/user/index?page='.$next; ?>" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
            </li>
          </ul>
        </nav>
        <?php } ?>
      </div>
    </div>
    <?php $this->endWidget(); } ?>
</div>
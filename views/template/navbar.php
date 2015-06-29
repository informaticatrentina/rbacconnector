<div role="navigation" class="navbar navbar-default rbac-navbar">
  <ul class="nav nav-pills">
    <li class="dropdown">
      <a href="/rbacconnector/user/index" class="dropdown-toggle" data-toggle="dropdown">
        <?php echo Yii::t('rbac', 'User'); ?>
      </a>
      <ul class="dropdown-menu">
        <li><a href="/rbacconnector/user/index"><?php echo Yii::t('rbac', 'User List'); ?></a></li>
        <li><a href="/rbacconnector/user/assign"><?php echo Yii::t('rbac', 'Assign Role'); ?></a></li>
      </ul>
    </li>
    <li class="dropdown">
      <a href="/rbacconnector/user/index" class="dropdown-toggle" data-toggle="dropdown">
        <?php echo Yii::t('rbac', 'Role'); ?>
      </a>
      <ul class="dropdown-menu">
        <li><a href="/rbacconnector/role/index"><?php echo Yii::t('rbac', 'Role List'); ?></a></li>
        <li><a href="/rbacconnector/role/add"><?php echo Yii::t('rbac', 'Add Role'); ?></a></li>
      </ul>
    </li>
    <li><a href="/rbacconnector/permission/index"><?php echo Yii::t('rbac', 'Permission'); ?></a></li>
  </ul>
</div>

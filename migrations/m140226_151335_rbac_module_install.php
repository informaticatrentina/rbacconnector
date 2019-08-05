<?php

class m140226_151335_rbac_module_install extends CDbMigration {

  public function up() {
    $this->execute('CREATE TABLE IF NOT EXISTS `rbac_permission` (
                          `role_id` int(11) NOT NULL,
                          `permission` varchar(100) NOT NULL
                      )');

    $this->execute('CREATE TABLE IF NOT EXISTS `rbac_role` (
                          `id` int(10) NOT NULL AUTO_INCREMENT,
                          `role` varchar(100) NOT NULL,
                          `status` tinyint(2) NOT NULL,
                          PRIMARY KEY (`id`)
                     )');
    /*
    $this->execute('CREATE TABLE IF NOT EXISTS `rbac_user` (
                          `id` int(10) NOT NULL AUTO_INCREMENT,
                          `email` varchar(100) NOT NULL,
                          `status` tinyint(2) NOT NULL,
                          PRIMARY KEY (`id`)
                     )');
    
    $this->execute('CREATE TABLE IF NOT EXISTS `rbac_user_role` (
                          `user_id` int(10) NOT NULL,
                          `role_id` int(11) NOT NULL
                     )');*/
  }

  public function down() {
    echo "m140226_151335_rbac_module_install does not support migration down.\n";
    return false;
  }
}

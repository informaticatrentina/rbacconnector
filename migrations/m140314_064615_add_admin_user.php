<?php

class m140314_064615_add_admin_user extends CDbMigration {

  public function up() {
    $this->execute("INSERT INTO `rbac_user` (`id`, `email`, `status`) 
                    VALUES (1,'". RBAC_ADMIN_USER ."' , 1)");

    $this->execute("INSERT INTO `rbac_role` (`id`, `role`, `status` )
                    VALUES (1, 'admin', '1')");
    
    $this->execute("INSERT INTO `rbac_permission` (`role_id`, `permission`)
                    VALUES ('1', 'is_admin')");
    
    $this->execute("INSERT INTO `timu`.`rbac_user_role` (`user_id`, `role_id`)
                    VALUES ('1', '1')");
    
  }

  public function down() {
    $this->execute("DELETE FROM `rbac_user` WHERE `email` = '".RBAC_ADMIN_USER. "'");
    $this->execute("DELETE FROM `rbac_role` WHERE `id` = 'admin'");
    $this->execute("DELETE FROM `rbac_permission` WHERE `permission` = 'is_admin'");
    $this->execute("DELETE FROM `rbac_user_role` WHERE `user_id` = ". 1);
  }

}

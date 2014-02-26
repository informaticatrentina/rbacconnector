<?php

/**
 * Permission
 * 
 * class is used for manage permission
 * Copyright (c) 2014 <ahref Foundation -- All rights reserved.
 * Author: Pradeep Kumar <pradeep@incaendo.com>
 * This file is part of <RBAC>.
 * This file can not be copied and/or distributed without the express permission of
 *  <ahref Foundation.
 */
class Permission extends CFormModel {

  public $role_id;
  public $role;
  public $permission;

  public function rules() {
    return array(
      array('role_id, permission', 'required'),
    );
  }

  /**
   * Declares attribute labels.
   */
  public function attributeLabels() {
    return array(
        'role' => 'Role',
        'permission' => 'Permission'
    );
  }

  /**
   * save
   * function is used for save permission
   */
  public function save() {
    try {
      $assignPermission = 0;
      $connection = Yii::app()->db;
      $sql = "INSERT INTO rbac_permission (role_id, permission) VALUES (:role_id, :permission)";
      $query = $connection->createCommand($sql);
      $query->bindParam(":role_id", $this->role_id);
      $query->bindParam(":permission", $this->permission);
      $assignPermission = $query->execute();
    } catch (Exception $e) {
      Yii::log('RBAC', 'error', $e->getMessage());
    }
    return $assignPermission;
  }
  /**
   * get
   * function is used for getting permission
   */
  public function get() {
    $connection = Yii::app()->db;
    $where = array(1);
    $data = array();
    if (!empty($this->role_id)) {
      $where[] = 'role_id = :role_id';
      $data[':role_id'] = $this->role_id;
    }
    $sql = "SELECT * FROM rbac_permission pr INNER JOIN rbac_role rl ON rl.id = pr.role_id WHERE " 
            . implode(' AND ', $where);
    $query = $connection->createCommand($sql);
    foreach ($data as $key => &$val) {
      $query->bindParam($key, $val);
    }
    return $query->queryAll();
  }
  
  /**
   * delete
   * function is used for delete permission
   */
  public function delete() {
    try {
      $deletePermission = 0;
      if (empty($this->role_id)) {
        return $deletePermission;
      }     
      $connection = Yii::app()->db;
      $sql = "DELETE FROM rbac_permission WHERE role_id = :role_id";
      $query = $connection->createCommand($sql);
      $query->bindParam(":role_id", $this->role_id);
      $deletePermission = $query->execute();
    } catch (Exception $e) {
      Yii::log('RBAC', 'error', $e->getMessage());
    }
    return $deletePermission;
  }
}
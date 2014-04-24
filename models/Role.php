<?php

/**
 * Role
 * 
 * class is used for manage role
 * Copyright (c) 2014 <ahref Foundation -- All rights reserved.
 * Author: Pradeep Kumar <pradeep@incaendo.com>
 * This file is part of <RBAC>.
 * This file can not be copied and/or distributed without the express permission of
 *  <ahref Foundation.
 */
class Role extends CFormModel {

  public $id;
  public $role;
  public $status;

  public function rules() {
    return array(
      array('role, status', 'required'),
      array('role', 'isExistRole'),
    );
  }

  /**
   * Declares attribute labels.
   */
  public function attributeLabels() {
    return array(
        'role' => 'Role',
        'status' => 'Status',
    );
  }

  /**
   * save
   * function is used for save role
   */
  public function save() {
    try {
      $saveRole = 0;
      $connection = Yii::app()->db;
      $sql = "INSERT INTO rbac_role (role, status) VALUES (:role, :status)";
      $query = $connection->createCommand($sql);
      $query->bindParam(":role", $this->role);
      $query->bindParam(":status", $this->status);
      $saveRole = $query->execute();
    } catch (Exception $e) {
      Yii::log('RBAC', 'error', $e->getMessage());
    }
    return $saveRole;
  }
  /**
   * get
   * function is used for getting role
   */
  public function get() {
    $connection = Yii::app()->db;
    $where = array(1);
    $data = array();
    if (!empty($this->id)) {
      $where[] = 'id = :id';
      $data[':id'] = $this->id;
    }
    if (!empty($this->role)) {
      $where[] = 'role = :role';
      $data[':role'] = $this->role;
    }
    if (isset($this->status)) {
      $where[] = 'status = :status';
      $data[':status'] = $this->status;
    }
    $sql = "SELECT * FROM rbac_role WHERE " . implode(' AND ', $where);
    $query = $connection->createCommand($sql);
    foreach ($data as $key => &$val) {
      $query->bindParam($key, $val);
    }
    if (!empty($this->id) || !empty($this->role)) {
      return $query->queryRow();
    }
    return $query->queryAll();
  }
  
  /**
   * update
   * function is used for update role
   */
  public function update() {
    try {
      $updateRole = 0;
      if (empty($this->id)) {
        return $updateRole;
      }     
      $connection = Yii::app()->db;
      $sql = "UPDATE rbac_role SET role = :role, status = :status WHERE id = :id";
      $query = $connection->createCommand($sql);
      $query->bindParam(":id", $this->id);
      $query->bindParam(":role", $this->role);
      $query->bindParam(":status", $this->status);
      $updateRole = $query->execute();
    } catch (Exception $e) {
      Yii::log('RBAC', 'error', $e->getMessage());
    }
    return $updateRole;
  }
  
  /**
   * delete
   * function is used for delete role
   */
  public function delete() {
    try {     
      if (empty($this->id)) {
        return $deleteRole;
      }     
      $connection = Yii::app()->db;
      $sql = "DELETE FROM rbac_role WHERE id = :id";
      $query = $connection->createCommand($sql);
      $query->bindParam(":id", $this->id);
      $deleteRole = $query->execute();
    } catch (Exception $e) {
      $deleteRole = 0;
      Yii::log('RBAC', 'error', $e->getMessage());
    }
    return $deleteRole;
  }
  
  /**
   * isExistRole
   * check whether role is exist or not
   * @param string $roleName
   * @return boolean 
   */
  public function isExistRole($attribute) {
    $existRole = false;
    $roleName = $this->role;
    if (!empty($roleName)) {
      $role = new Role();
      $role->role = trim($roleName);
      $roles = $role->get();
      if (!empty($roles)) {
        $labels = $this->attributeLabels();
        $this->addError($attribute, "$labels[$attribute] already in used");
      } else {
        return true;
      }
    }
  }
}
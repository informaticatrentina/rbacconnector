<?php

/**
 * User
 * 
 * class is used for assign role for an user
 * Copyright (c) 2014 <ahref Foundation -- All rights reserved.
 * Author: Pradeep Kumar <pradeep@incaendo.com>
 * This file is part of <RBAC>.
 * This file can not be copied and/or distributed without the express permission of
 *  <ahref Foundation.
 */
class User extends CFormModel {

  public $id;
  public $user_id;
  public $role_id;
  public $user_email;
  public $user_status;

  public function rules() {
    return array(
      array('user_email', 'required'),
      array('user_email', 'email'),
    );
  }

  /**
   * Declares attribute labels.
   */
  public function attributeLabels() {
    return array(
      'user_email' => 'Email Id'
    );
  }

  /**
   * save
   * function is used for save role
   */
  public function save() {
    try {
      $connection = Yii::app()->db;
      $sql = "INSERT INTO rbac_user_role (user_id, role_id) VALUES (:user_id, :role_id)";
      $query = $connection->createCommand($sql);
      $query->bindParam(":user_id", $this->user_id);
      $query->bindParam(":role_id", $this->role_id);
      $assignRole = $query->execute();
    } catch (Exception $e) {
      $assignRole = 0;
      Yii::log('RBAC', 'error', $e->getMessage());
    }
    return $assignRole;
  }
  
  /**
   * get
   * function is used for g tting role
   */
  public function get() {
    $connection = Yii::app()->db;
    $where = array(1);
    $data = array();
    if (!empty($this->id)) {
      $where[] = 'u.id = :id';
      $data[':id'] = $this->id;
    }
    if (!empty($this->role_id)) {
      $where[] = 'ur.role_id = :role_id';
      $data[':role_id'] = $this->role_id;
    }
    if (!empty($this->user_id)) {
      $where[] = 'ur.user_id = :user_id';
      $data[':user_id'] = $this->user_id;
    }
    if (!empty($this->user_email)) {
      $where[] = 'ur.user_email = :user_email';
      $data[':user_email'] = $this->user_email;
    }
    $sql = "SELECT * FROM rbac_user u INNER JOIN rbac_user_role ur ON u.id  = ur.user_id 
      INNER JOIN rbac_role rl ON ur.role_id = rl.id WHERE " . implode(' AND ', $where);
    $query = $connection->createCommand($sql);
    foreach ($data as $key => &$val) {
      $query->bindParam($key, $val);
    }
    return $query->queryAll();
  }
  
  /**
   * delete
   * function is used for delete role
   */
  public function delete() {
    try {
      $deleteRole = 0;      
      if (!empty($this->role_id)) {
        $where[] = 'role_id = :role_id';
        $data[':role_id'] = $this->role_id;
      }
      if (!empty($this->user_id)) {
        $where[] = 'user_id = :user_id';
        $data[':user_id'] = $this->user_id;
      }
      if (empty($where)) {
        return 0;
      }
      $connection = Yii::app()->db;
      $sql = "DELETE FROM rbac_user_role WHERE ". implode(' AND ', $where);
      $query = $connection->createCommand($sql);
      foreach ($data as $key => &$val) {
        $query->bindParam($key, $val);
      }
      $deleteRole = $query->execute();
    } catch (Exception $e) {
      Yii::log('RBAC', 'error', $e->getMessage());
    }
    return $deleteRole;
  }
  
  /**
   * saveUser
   * function is used for save user
   */
  public function saveUser() {
    $connection = Yii::app()->db;
    if (empty($this->user_email)) {
      return array();
    }
    $sql = "INSERT INTO rbac_user (email, status) VALUES (:email, :status)";
    $query = $connection->createCommand($sql);
    $query->bindParam(":email", $this->user_email);
    $query->bindParam(":status", $this->user_status);
    $query->execute();
    return Yii::app()->db->getLastInsertId();
  }
  
  /**
   * getUserByEmail
   * function is used for getting user detail by email
   */
  public function getUserByEmail() {
    $connection = Yii::app()->db;
    if (empty($this->user_email)) {
      return array();
    }
    $sql = "SELECT * FROM rbac_user WHERE email = :email ";
    $query = $connection->createCommand($sql);
    $query->bindParam(":email", $this->user_email);    
    return $query->queryRow();
  }
 
 /**
  * getRoles
  * function is used for getting roles 
  * @param string $email
  * @return array $role
  */
 public static function getRoles($email) {
    $role = array();
    $connection = Yii::app()->db;
    if (empty($email)) {
      return $role;
    }
    $sql = "SELECT rl.role FROM rbac_user u INNER JOIN rbac_user_role ur ON u.id = ur.user_id
       INNER JOIN rbac_role rl on rl.id = ur.role_id WHERE email = :email ";
    $query = $connection->createCommand($sql);
    $query->bindParam(":email", $email);
    $roles = $query->queryAll();
    foreach ($roles as $rol) {
      $role[] = $rol['role'];
    }
    return $role;
  }
  
 /**
  * getPermission
  * function is used for getting permissions 
  * @param string $email
  * @param boolean $roleWisePermission (optional) - true if role wise permission is required
  * @return array $permission
  */
 public static function getPermission($email, $roleWisePermission = false) {
    $permission = array();
    $connection = Yii::app()->db;
    if (empty($email)) {
      return $permission;
    }
    if ($roleWisePermission) {
      $sql = "SELECT * FROM rbac_user u INNER JOIN rbac_user_role ur ON u.id  = ur.user_id 
      INNER JOIN rbac_role rl ON rl.id = ur.role_id INNER JOIN rbac_permission p ON 
      ur.role_id = p.role_id WHERE email = :email ";
      $query = $connection->createCommand($sql);
      $query->bindParam(":email", $email);
      $permissions = $query->queryAll();
      foreach ($permissions as $perm) {
        $permission[$perm['role']][] = $perm['permission'];
      }
      return $permission;
    }
    $sql = "SELECT * FROM rbac_user u INNER JOIN rbac_user_role ur ON u.id  = ur.user_id 
      INNER JOIN rbac_permission p  ON ur.role_id = p.role_id WHERE email = :email ";
    $query = $connection->createCommand($sql);
    $query->bindParam(":email", $email);
    $permissions = $query->queryAll();
    foreach ($permissions as $perm) {
      $permission[] = $perm['permission'];
    }
    return $permission;
  }
}
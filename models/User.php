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
  public $role_status;

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
      $where[] = 'u.email = :user_email';
      $data[':user_email'] = $this->user_email;
    }
    if (isset($this->role_status)) {
      $where[] = 'rl.status = :role_status';
      $data[':role_status'] = $this->role_status;
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
    $status = ACTIVE;
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
      INNER JOIN rbac_permission p  ON ur.role_id = p.role_id 
      INNER JOIN rbac_role rl  ON p.role_id = rl.id WHERE email = :email 
      AND rl.status = :status";
    $query = $connection->createCommand($sql);
    $query->bindParam(":email", $email);
    $query->bindParam(":status", $status);
    $permissions = $query->queryAll();
    foreach ($permissions as $perm) {
      $permission[] = $perm['permission'];
    }
    return $permission;
  }
  
  /**
   * checkPermission
   * function is used for check permission 
   * @param string $email
   * @param array or string $permission 
   * @return boolean true if user have permission else false
   */
  public static function checkPermission($email, $permission) { 
    $havePermission = false;
    if (empty($email)) {
      return $havePermission; 
    }
    $allowedPermission = self::getPermission($email);
    if (!is_array($permission)) {
        $permission = array($permission);
    }
    foreach($permission as $singlePermission) {
        $singlePermission = strtolower(preg_replace("/[^a-z0-9]+/i", "_", $singlePermission));
        if (is_array($allowedPermission) && in_array($singlePermission, $allowedPermission)) {
            $havePermission = true;
            break;
        }
    }
    return $havePermission;
  }
  
  /**
   * updateUser
   * This function is used to update User details.
   * @return int
   */
  public function updateUser() {
    try {
      $set = array();
      $data = array();
      if (!empty($this->user_id)) {
        $where[] = 'id = :id';
        $data[':id'] = $this->user_id;
      }
      if (isset($this->user_status)) {
        $set[] = 'status = :status';
        $data[':status'] = $this->user_status;
      }
      if (empty($where)) {
        throw('Where condition cannot be empty.');
      }
      $connection = Yii::app()->db;      
      $sql = "UPDATE rbac_user SET ". implode(', ',  $set) ." WHERE ". implode(' AND ', $where);
      $query = $connection->createCommand($sql);
      foreach ($data as $key => &$val) {
        $query->bindParam($key, $val);
      }
      $query->execute();
    } catch (Exception $e) {
        Yii::log('Error caused in updateUser method', 'error', $e->getMessage());
    }
  }
    
}

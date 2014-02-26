<?php

/**
 * RoleController
 * 
 * class is used for manage role
 * Copyright (c) 2014 <ahref Foundation -- All rights reserved.
 * Author: Pradeep Kumar <pradeep@incaendo.com>
 * This file is part of <RBAC>.
 * This file can not be copied and/or distributed without the express permission of
 *  <ahref Foundation.
 */

class RoleController extends Controller {
  
  /**
   * Add
   * function id used for create role
   */
  public function actionAdd() {
    $model = new Role();
    if (isset($_POST['Role'])) {
      $model->attributes = $_POST['Role'];
      if ($model->validate()) {        
        if ($this->isExistRole($model->role)) {
          $this->redirect(array('/rbac/role/index'));
        }
        if ($model->save()) {
          $this->redirect(array('/rbac/role/index'));
        }
      }
    }
    $this->render('role', array('model' => $model));
  }
  
  /**
   * edit
   * function id used for update existing role
   */
  public function actionEdit(){
    $model = new Role();
    $model->id = $_GET['id'];
    if (isset($_POST['Role'])) {
      $model->attributes = $_POST['Role'];
      if ($model->validate()) { 
        if (is_numeric($model->update())) {
          $this->redirect(array('/rbac/role/index'));
        }
      }
    } else {
      $model->attributes = $model->get();
    }    
    $this->render('role', array('model' => $model));
  }
  
  /**
   * action index
   * function id used for getting role (listing of role)
   */
  public function actionIndex() {
    $model = new Role();
    $role = $model->get();
    $this->render('index', array('roles' => $role));
  }
  
  /**
   * deleteRole
   * function id used for delete role
   */
  public function actionDelete(){    
    if (array_key_exists('id', $_GET) && is_numeric($_GET['id'])) {
      $model = new Role();
      $model->id = $_GET['id'];
      $isDeleteRole = $model->delete();
      if ($isDeleteRole) {
        $permission = new Permission();
        $permission->role_id = $_GET['id'];
        $permission->delete();
      }
    }    
    $this->redirect(array('/rbac/role/index'));
  }
  
  /**
   * isExistRole
   * check whether role is exist or not
   * @param string $roleName
   * @return boolean 
   */
  public function isExistRole($roleName) { 
    $existRole = false;
    if (!empty($roleName)) {
      $role = new Role();
      $role->role = $roleName;
      $roles = $role->get();
      if (!empty($roles)) {
        $existRole = true;
      }
    }
    return $existRole;
  }
}
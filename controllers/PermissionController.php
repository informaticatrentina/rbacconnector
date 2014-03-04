<?php

/**
 * PermissionController
 * 
 * class is used for manage permission of role
 * Copyright (c) 2014 <ahref Foundation -- All rights reserved.
 * Author: Pradeep Kumar <pradeep@incaendo.com>
 * This file is part of <RBAC>.
 * This file can not be copied and/or distributed without the express permission of
 *  <ahref Foundation.
 */
class PermissionController extends Controller {

  /**
   * actionAssign
   * function id used for change, assign permission to a role
   */
  public function actionAssign() {
    $haveAdminPrivilege = false;
    if (isset(Yii::app()->session['user'])) {
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) {
      $this->redirect(Yii::app()->homeUrl);
    }
    $model = new Permission();
    if (isset($_POST['Permission'])) {
      $post = $_POST['Permission'];
      $model->role_id = $post['role_id'];
      $model->delete();
      if (array_key_exists('permission', $post) && !empty($post['permission'])) {
        foreach ($post['permission'] as $perm) {
          if (!empty($perm)) {
            $model->permission = $perm;
            $model->save();
          }
        }
        $this->redirect('/rbac/permission/index');
      }
    }
    //get all permissions
    $permissions = array();
    if (defined('PERMISSION')) {
      $permission = json_decode(PERMISSION);
      foreach ($permission as $perm) {
        $key = strtolower(preg_replace("/[^a-z0-9]+/i", "_", $perm));
        $permissions[$key] = $perm;
      }
    }
    if (!array_key_exists('id', $_GET) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
      $this->redirect('/rbac/permission/index');
    }
    //get exusting permissions
    $selPermission = array();
    $model->role_id = $_GET['id'];
    $savePermissions = $model->get();
    foreach ($savePermissions as $permission) {
      $selPermission[] = $permission['permission'];
    }    
    $role = new Role();
    $role->id = $_GET['id'];
    $roles = $role->get();
    $model->role = $roles['role'];
    $this->render('permission', array('model' => $model, 'roles' => $roles,
        'permissions' => $permissions, 'selectedPermission' => $selPermission));
  }

  /**
   * actionIndex
   * function id used for showing permission for each role
   */
  public function actionIndex() {  
    $haveAdminPrivilege = false;
    if (isset(Yii::app()->session['user'])) {
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) { 
      $this->redirect(Yii::app()->homeUrl);
    }
    $model = new Permission();    
    //get all permission
    $allPermission = array();
    if (defined('PERMISSION')) {
      $definedPermission = json_decode(PERMISSION);
      foreach ($definedPermission as $perm) {
        $key = strtolower(preg_replace("/[^a-z0-9]+/i", "_", $perm));
        $allPermission[$key] = $perm;
      }
    }
    //get permission for role
    $permission = array();
    $permissions = $model->get();
    foreach ($permissions as $perm) {
      $permission[$perm['role_id']]['role_id'] = $perm['role_id'];
      $permission[$perm['role_id']]['role'] = $perm['role'];
      if (array_key_exists($perm['permission'], $allPermission)) {
        $permission[$perm['role_id']]['permission'][] = $allPermission[$perm['permission']];
      } else {
        $permission[$perm['role_id']]['permission'] = array();
      }      
      $permission[$perm['role_id']]['status'] = $perm['status'];
    }
    
    $role = new Role();
    $roles = $role->get();
    $this->render('index', array('permissions' => $permission, 'roles' => $roles));
  }
}
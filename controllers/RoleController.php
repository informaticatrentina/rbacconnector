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
    $haveAdminPrivilege = false;
    $checkRoleStatus = 'CREATE';
    if (isset(Yii::app()->session['user'])) {
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) {
      $this->redirect(Yii::app()->homeUrl);
    }
    $model = new Role();
    if (isset($_POST['Role'])) {
      $model->attributes = array_map('trim', $_POST['Role']);
      if ($model->validate()) {        
        if ($model->save()) {
          $this->redirect(array('/rbacconnector/role/index'));
        }
      }
    }
    //including the js files required for this view.
    Yii::app()->clientScript->registerScriptFile(
      Yii::app()->getAssetManager()->publish(
        Yii::getPathOfAlias('rbacconnector.assets.js') . '/role.js'
      ), CClientScript::POS_END
    );
    $this->render('role', array('model' => $model, 'checkRoleStatus' => $checkRoleStatus));
  }
  
  /**
   * edit
   * function id used for update existing role
   */
  public function actionEdit(){
    $haveAdminPrivilege = false;
    $checkRoleStatus = 'CREATE';
    if (isset(Yii::app()->session['user'])) {
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) {
      $this->redirect(Yii::app()->homeUrl);
    }
    $model = new Role();
    $model->id = $_GET['id'];
    if (isset($_POST['Role'])) {
      $model->attributes = array_map('trim', $_POST['Role']);
      if ($model->validate()) { 
        if (is_numeric($model->update())) {
          $this->redirect(array('/rbacconnector/role/index'));
        }
      }
    } else {
      $roleDetails = $model->get();
      if ($roleDetails != '') {
        $checkRoleStatus = 'EDIT';
        $model->attributes = $roleDetails;
      }
    }    
    //including the js files required for this view.
    Yii::app()->clientScript->registerScriptFile(
      Yii::app()->getAssetManager()->publish(
        Yii::getPathOfAlias('rbacconnector.assets.js') . '/role.js'
      ), CClientScript::POS_END
    );
    $this->render('role', array('model' => $model, 'checkRoleStatus' => $checkRoleStatus));
  }
  
  /**
   * action index
   * function id used for getting role (listing of role)
   */
  public function actionIndex() {
    $haveAdminPrivilege = false;
    if (isset(Yii::app()->session['user'])) {
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) {
      $this->redirect(Yii::app()->homeUrl);
    }
    $model = new Role();
    $role = $model->get();
    $this->render('index', array('roles' => $role));
  }
  
  /**
   * deleteRole
   * function id used for delete role
   */
  public function actionDelete(){ 
    $haveAdminPrivilege = false;
    if (isset(Yii::app()->session['user'])) {
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) {
      $this->redirect(Yii::app()->homeUrl);
    }
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
    $this->redirect(array('/rbacconnector/role/index'));
  }
    
}
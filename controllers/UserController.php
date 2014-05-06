<?php

/**
 * UserController
 * 
 * function is used for assign role to user
 * Copyright (c) 2014 <ahref Foundation -- All rights reserved.
 * Author: Pradeep Kumar <pradeep@incaendo.com>
 * This file is part of <RBAC>.
 * This file can not be copied and/or distributed without the express permission of
 *  <ahref Foundation.
 */

class UserController extends PageController {

  /**
   * beforeAction
   * This function is called before any action method set header bar
   * @param $action
   * @return boolean
   */
  public function beforeAction($action) {
    if (defined('ENABLE_NAVBAR_MODULE') && ENABLE_NAVBAR_MODULE == 1) {
      $this->setHeader('3.0');
    }
    return true;
  }
  /**
   * actionAssign
   * function id used fior change, assign permission to a role
   */
  public function actionAssign() {
    $message = '';
    $selectedRole = array();
    $user = array();
    $roles = array();
    $selRoleIds = array();
    $haveAdminPrivilege = false;
    if (isset(Yii::app()->session['user'])) {
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) {
      $this->redirect(Yii::app()->homeUrl);
    }
    $model = new User();    
    try {
      //get all roles
      $role = new Role();
      $role->status = ACTIVE;
      $roles = $role->get();
      if (isset($_POST['User'])) {
        $post = $_POST['User'];
        $model->attributes = $post;
        $model->check_user_status = $post['check_user_status'];
        $model->user_email = trim($post['user_email']);
        if ($model->validate()) {
          $userDetail = $model->getUserByEmail();
          if (empty($userDetail) && $model->check_user_status == 'CREATE') {
            $model->user_status = ACTIVE;
            $model->user_id = $model->saveUser();
          } else if (!empty($userDetail) && $model->check_user_status == 'CREATE') {
            if ($userDetail['status'] == ACTIVE) {
              throw new Exception('User already exists.');
            }
            $model->user_status = ACTIVE;
            $model->user_id = $userDetail['id'];
            $model->updateUser();
          } else if ($model->check_user_status == 'EDIT') {
            $model->user_status = ACTIVE;
            $model->user_id = $userDetail['id'];
            $model->updateUser();
          } else {
            $model->user_id = $userDetail['id'];
          }
          $model->delete();
          if (array_key_exists('role_id', $post) && !empty($post['role_id'])) {
            $isRoleAssigned = false;
            foreach ($post['role_id'] as $role) {
              if (!empty($role)) {
                $isRoleAssigned = true;
                $model->role_id = $role;
                $model->save();
              }
            }
            if ($isRoleAssigned === false) {
              $model->user_status = INACTIVE;
              $model->updateUser();
            }
            $this->redirect('/rbacconnector/user/index');
          }
        }
      }      
      $model->check_user_status = 'CREATE';
      if (array_key_exists('id', $_GET) && !empty($_GET['id'])) {   
        //get user   
        $model->id = $_GET['id'];
        $userDetail = $model->get();
        if (!empty($userDetail)) {
          $model->check_user_status = 'EDIT';
        }
        foreach ($userDetail as $usr) {
          $user['user_id'] = $usr['user_id'];
          $user['email'] = $usr['email'];
          $user['role'][] = array(
            'role_id' => $usr['role_id'], 'role' => $usr['role']
          );
          $model->user_email = $usr['email'];
          $selRoleIds[] = $usr['role_id']; 
        }
      }
    } catch (Exception $e) {
      $message = $e->getMessage();
    }    
    //including the js files required for this view.
    Yii::app()->clientScript->registerScriptFile(
      Yii::app()->getAssetManager()->publish(
        Yii::getPathOfAlias('rbacconnector.assets.js') . '/user.js'
      ), CClientScript::POS_END
    );
    $this->render('user', array('model' => $model, 'user' => $user,
      'roles' => $roles, 'selRoleIds' => $selRoleIds, 'message' => $message));
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
    $model = new User();
    $model->role_status = ACTIVE;
    $users = $model->get();
    $user = array();
    foreach ($users as $usr) {
      $user[$usr['user_id']]['user_id'] = $usr['user_id'];
      $user[$usr['user_id']]['email'] = $usr['email'];
      $user[$usr['user_id']]['role'][] = array(
          'role_id' => $usr['role_id'], 'role' => $usr['role']
      );      
    }
    $this->render('index', array('users' => $user));
  }

}
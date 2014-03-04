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

class UserController extends Controller {

  /**
   * actionAssign
   * function id used fior change, assign permission to a role
   */
  public function actionAssign() {
    $haveAdminPrivilege = false;
    if (isset(Yii::app()->session['user'])) {
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) {
      $this->redirect(Yii::app()->homeUrl);
    }
    $model = new User();    
    if (isset($_POST['User'])) {
      $post = $_POST['User'];
      $model->attributes = $post;
      if ($model->validate()) {
        $model->user_email = $post['user_email'];
        $userDetail = $model->getUserByEmail();
        if (empty($userDetail)) {
          $model->user_status = 1;
          $model->user_id = $model->saveUser();
        } else {
          $model->user_id = $userDetail['id'];
        }
        $model->delete();
        if (array_key_exists('role_id', $post) && !empty($post['role_id'])) {
          foreach ($post['role_id'] as $role) {
            if (!empty($role)) {
              $model->role_id = $role;
              $model->save();
            }
          }
          $this->redirect('/rbacconnector/user/index');
        }
      }
    }
    $selectedRole = array();
    $user = array();
    $roles = array();
    $selRoleIds = array();
    if (array_key_exists('id', $_GET) && !empty($_GET['id'])) {   
      //get user   
      $model->id = $_GET['id'];
      $userDetail = $model->get();
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
    //get all roles
    $role = new Role();
    $roles = $role->get();
    $this->render('user', array('model' => $model, 'user' => $user,
        'roles' => $roles, 'selRoleIds' => $selRoleIds));
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
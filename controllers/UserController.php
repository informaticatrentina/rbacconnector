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


  public function actionDisable() 
  {
    $haveAdminPrivilege = false;
    if (isset(Yii::app()->session['user'])) {
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) {
      $this->redirect(Yii::app()->homeUrl);
    }

    if (Yii::app()->request->isAjaxRequest && array_key_exists('id', $_GET) && !empty($_GET['id'])) 
    {
      $identity_mgr = new UserIdentityManager();
      $responseidentity=$identity_mgr->disableUserbyId($_GET['id']);
      if($responseidentity['success']) $status='success';
      else $status='error';

      $response = array('response' => $status, 'message' => $responseidentity['msg']);
      echo CJSON::encode($response);
      Yii::app()->end();
    } 
    else 
    {
      Yii::app()->redirect(BASE_URL);
    }

    if (array_key_exists('id', $_GET) && !empty($_GET['id'])) 
    {   
      //get user    
      $identity_mgr = new UserIdentityManager();
      $responseid=$identity_mgr->disableUserbyId($_GET['id']);

      $response = Yii::app()->response;
      $response->format = \yii\web\Response::FORMAT_JSON;
      $response->data = array('response' => $responseid['success'], 'message' => $responseid['msg']);
      return $response;
    }
  }

  public function actionEnable() 
  {
    $haveAdminPrivilege = false;
    if (isset(Yii::app()->session['user'])) {
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) {
      $this->redirect(Yii::app()->homeUrl);
    }

    if (Yii::app()->request->isAjaxRequest && array_key_exists('id', $_GET) && !empty($_GET['id'])) 
    {
      $identity_mgr = new UserIdentityManager();
      $responseidentity=$identity_mgr->enableUserbyId($_GET['id']);
      if($responseidentity['success']) $status='success';
      else $status='error';

      $response = array('response' => $status, 'message' => $responseidentity['msg']);
      echo CJSON::encode($response);
      Yii::app()->end();
    } 
    else 
    {
      Yii::app()->redirect(BASE_URL);
    }
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
    
          if (isset($post['role_id']) && !empty($post['role_id'])) {
         
            $isRoleAssigned = false;

            if(is_array($post['role_id']))
            {
              foreach ($post['role_id'] as $role) {       
                if (!empty($role)) {
                  $isRoleAssigned = true;
                  $model->role_id = $role;
                  $model->save();
                }
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
      
        $userDetail = $model->getbyId($_GET['id']);        
        if (!empty($userDetail)) {        
          $model->user_email = $userDetail['email'];
          $model->user_status = $userDetail['status'];
          $userWebDetail = $model->getUserByEmail();
   
          if(!empty($userWebDetail))
          {
            $model->check_user_status = 'EDIT';
            $model->id = $userWebDetail['id'];
   
            $userWebdata=$model->get();
            if(isset($userWebdata[0]) && !empty($userWebdata[0]))
            {
              $datauser=$userWebdata[0];
              $model->id =  $datauser['id'];
              $model->user_status = $datauser['status'];
              $model->user_id = $datauser['user_id'];
             // $model->role_status = $datauser['role'];
              //$model->role_id = $datauser['role_id'];
            }
          } 
        }
     
          if(isset($model->user_id) && !empty($model->user_id)) $user['user_id'] = $model->user_id;
          $user['email'] = $userDetail['email'];

          $model->user_email = $userDetail['email'];
     
          if(isset($datauser['role_id']) && !empty($datauser['role_id']))
          {
            $selRoleId = $datauser['role_id']; 
          } 
      }
    } catch (Exception $e) {
      $message = $e->getMessage();
    }    
    //including the js files required for this view.
    Yii::app()->clientScript->registerScriptFile(
      Yii::app()->getAssetManager()->publish(
        Yii::getPathOfAlias('rbacconnector.assets.js') . '/role.js'
      ), CClientScript::POS_END
    );
    $this->render('user', array('model' => $model, 'user' => $user,
      'roles' => $roles, 'selRoleId' => $selRoleId, 'message' => $message));
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

    // Recupero gli utenti da Mongodb
    $users = $model->getAllUsers();

    // Recupero gli utenti da Mysql
    $usersweb = $model->get();

    $data_web=array();
    // Normalizzo gli utenti ricavati per recuperare i ruoli locali
    if(!empty($usersweb))
    {
      foreach($usersweb as $webusr)
      {       
        if(isset($webusr['email']) && !empty($webusr['email']) && isset($webusr['role']) && !empty($webusr['role']))
        {
          $data_web[$webusr['email']]=$webusr['role'];
        }        
      }
    }

    $data = array();

    if(!empty($users))
    {
      $datatmp=array();
      $i=1;

      foreach ($users as $usr) 
      {
        $datatmp[]=array('id' => $i,
        'user_id'=> $usr['_id'],
        'email' => $usr['email'],
        'role' => (array_key_exists(trim($usr['email']), $data_web))?($data_web[$usr['email']]):('N.D'),
        'gdpr' => (isset($usr['gdpr']) && $usr['gdpr']==1)?('SI'):('NO'),
        'gdpr_date' => (isset($usr['gdpr_date']) && !empty($usr['gdpr_date']))?(date("d/m/Y - H:i", strtotime($usr['gdpr_date']))):('---'),
        'gdpr_date_deleted' => (isset($usr['gdpr_date_del']) && !empty($usr['gdpr_date_del']))?(date("d/m/Y - H:i", strtotime($usr['gdpr_date_del']))):('---'),
        'status' => $usr['status']
        );
        $i++;
      }

      // Paginazione
      $page = ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;
      $total = count($datatmp); //total items in array    
      $limit = 40; //per page    
      $totalPages = ceil( $total/ $limit ); //calculate total pages
      $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
      $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
      $offset = ($page - 1) * $limit;
      if( $offset < 0 ) $offset = 0;

      $previous=1;
      $next=1;
      $page_pag=$page;
      
      if($page>1)
      {
        $previous=$page-1;
      }

      if($page==$totalPages)
      {
        $next=$totalPages;
      }
      else $next=$page+1;

      
      $data = array_slice( $datatmp, $offset, $limit );
    }

       //including the js files required for this view.
        Yii::app()->clientScript->registerScriptFile(
          Yii::app()->getAssetManager()->publish(
            Yii::getPathOfAlias('rbacconnector.assets.js') . '/user.js'
          ), CClientScript::POS_END
        );

    $this->render('index', array('users' => $data, 'totalPages' => $totalPages, 'page_sel' => $page, 'previous' => $previous, 'next' => $next));
  }

   /**
   * actionExport 
   * export user in csv file
   */
  public function actionExport() {

    $haveAdminPrivilege = false;
    if (isset(Yii::app()->session['user'])) {    
      $haveAdminPrivilege = User::checkPermission(Yii::app()->session['user']['email'], 'is_admin');
    } 
    if (!$haveAdminPrivilege) {
      $this->redirect(Yii::app()->homeUrl);
    }

    $model = new User();

    // Recupero gli utenti da Mongodb
    $users = $model->getAllUsers();

    // Recupero gli utenti da Mysql
    $usersweb = $model->get();

    $data_web=array();
    // Normalizzo gli utenti ricavati per recuperare i ruoli locali
    if(!empty($usersweb))
    {
      foreach($usersweb as $webusr)
      {       
        if(isset($webusr['email']) && !empty($webusr['email']) && isset($webusr['role']) && !empty($webusr['role']))
        {
          $data_web[$webusr['email']]=$webusr['role'];
        }        
      }
    }

    if(!empty($users))
    {
      $i=1;

      $header = array(
        'id' => Yii::t('rbac', 'S.No.'),
        'firstname' => Yii::t('rbac', 'First Name'),
        'lastname' => Yii::t('rbac', 'Last Name'),
        'email' => Yii::t('rbac', 'User email'),
        'role' => Yii::t('rbac', 'Role'),
        'gdpr' => Yii::t('rbac', 'Gdpr Policy'),
        'gdpr_date' => Yii::t('rbac', 'Gdpr Date'),
        'gdpr_date_deleted' => Yii::t('rbac', 'Gdpr Date Deleted')
      );

      header("Content-disposition: attachment; filename=users.csv");
      header("Content-Type: text/csv");
      $filePath = fopen("php://output", 'w');
      @fputcsv($filePath, $header);

      foreach ($users as $usr) 
      {
        $tmp_data=array(
        'id' => $i,
        'firstname' => $usr['firstname'],
        'lastname' => $usr['lastname'],
        'email' => $usr['email'],
        'role' => (array_key_exists(trim($usr['email']), $data_web))?($data_web[$usr['email']]):('N.D'),
        'gdpr' => (isset($usr['gdpr']) && $usr['gdpr']==1)?('SI'):('NO'),
        'gdpr_date' => (isset($usr['gdpr_date']) && !empty($usr['gdpr_date']))?(date("d/m/Y - H:i", strtotime($usr['gdpr_date']))):('---'),
        'gdpr_date_deleted' => (isset($usr['gdpr_date_del']) && !empty($usr['gdpr_date_del']))?(date("d/m/Y - H:i", strtotime($usr['gdpr_date_del']))):('---'),
        );       
        $i++;
        @fputcsv($filePath, $tmp_data);        
      }
      exit;
    }
  }
}

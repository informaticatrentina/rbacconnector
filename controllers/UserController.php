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

      if ($_SERVER['REQUEST_METHOD'] === 'POST') 
      {                 
        if(!isset($_POST['User']['user_email']) || empty($_POST['User']['user_email'])) throw new Exception('Attenzione, campo email obbligatorio.');
        else
        {          
          $email=trim($_POST['User']['user_email']);
          $model->user_email = $email;
        } 

        if(!isset($_POST['role_name']) || empty($_POST['role_name'])) throw new Exception('Attenzione, non è stato selezionato nessun ruolo.');
        else
        {
          $role_name=trim($_POST['role_name']);
          $model->role_name = $role_name;
        } 

        // Recupero info utente by email - In particolare esso deve essere

        $datauser=$model->getUserByEmail($email);
        //die(print('<pre>'.print_r($datauser,TRUE).'</pre>'));
        
        if(empty($datauser)) throw new Exception('Attenzione, non è presente nessun utente attivo con mail: '.$email);
        else
        {
          if(!isset($datauser['_id']['$id'])) throw new Exception('Attenzione, non è presente nessun utente attivo con mail: '.$email);
          else
          {
            $user_id=$datauser['_id']['$id'];
            $model->user_id=$user_id;
          } 
          
          // Aggiorno il ruolo dell'utente

          $model->setRuolobyId($role_name,$user_id);
          $this->redirect('/rbacconnector/user/index');         
        }
      }      
     
      if (array_key_exists('id', $_GET) && !empty($_GET['id'])) 
      {
        $userDetail = $model->getbyId($_GET['id']);    

        if (!empty($userDetail)) 
        {        
          $model->user_email = $userDetail['email'];

          if(isset($userDetail['site-user-info']['role'][0])) $selRoleName=$userDetail['site-user-info']['role'][0];
          else $selRoleName=null;        
        }
        else
        {
          $this->redirect('/rbacconnector/user/index');
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
      'roles' => $roles, 'selRoleName' => $selRoleName, 'message' => $message));
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
        'role' => (isset($usr['site-user-info']['role'][0]))?($usr['site-user-info']['role'][0]):('N.D'),
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

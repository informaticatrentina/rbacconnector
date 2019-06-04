<?php

/**
 * UserIdentityManager
 *
 * UserIdentityManager class is used for interacting with UserIdentityAPI class.
 * UserIdentityManager class is used for get user detail, create user
 * Copyright (c) 2014 <ahref Foundation -- All rights reserved.
 * Author: Pradeep Kumar <pradeep@incaendo.com>
 * This file is part of <Backendconnector>.
 * This file can not be copied and/or distributed without the express permission of
 *   <ahref Foundation.
 */

class UserIdentityManager extends CFormModel{

  public function getAllUsers()
  {
    try 
    {
      $users = new UserIdentityAPI();
      $response=$users->get('users',array('source' => SOURCE), '');  

      if($response['success']==TRUE) return array('success' => TRUE, 'data' => $response['data']['_items']);    
      else return array('success' => FALSE, 'msg' => $response['msg']);
    }
    catch(Exception $e)
    {
      return array('success' => FALSE, 'msg' => $e->getMessage());      
    }    
  }

  public function getUserbyId($user_id)
  {
    try 
    {
      $users = new UserIdentityAPI();
      $response=$users->get('users',array('source' => SOURCE, '_id' => $user_id), '');  
       if($response['success']==TRUE) return array('success' => TRUE, 'data' => $response['data']['_items']);    
      else return array('success' => FALSE, 'msg' => $response['msg']);
    }
    catch(Exception $e)
    {
      return array('success' => FALSE, 'msg' => $e->getMessage());      
    }
  }

  public function disableUserbyId($user_id)
  {
    try 
    {
      $instance = new UserIdentityAPI();
      // Imposto la data di disattivazione odierna
      $now= new DateTime();
      $now->setTimezone(new DateTimeZone('Europe/Rome'));


      $inputParam = array(
        'status' => 0,
        'gdpr' => 0,
        'gdpr_date_del' => $now->format('Y-m-d H:i:s'),
        'id' => $user_id
      );


      $response=$instance->curlPut('users',$inputParam);

      // Scrivo il file di log se la risposta è positiva
      if(isset($response['success']) && $response['success']==true)
      {
        if (is_dir(RUNTIME_DIRECTORY)) 
        {
          if(is_writable(RUNTIME_DIRECTORY))
          {
            $filename = RUNTIME_DIRECTORY.'/gdpr_data_response.txt';
            file_put_contents($filename, $now->format('Y-m-d H:i:s').' ### '.json_encode($inputParam). PHP_EOL, FILE_APPEND);
          }
        }
      }
      return $response;
    }
    catch(Exception $e)
    {
      return array('success' => FALSE, 'msg' => $e->getMessage());      
    }
  }
}
?>
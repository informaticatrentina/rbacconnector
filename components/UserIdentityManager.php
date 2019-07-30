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

  public function enableUserbyId($user_id)
  {
    try 
    {
      $attiva_utente=false;
      // Recupero email
      $user = new UserIdentityAPI();
      $dataresponse=$user->get('users',array('source' => SOURCE, '_id' => $user_id), '');  

      // Scrivo il file di log se la risposta è positiva
      if(isset($dataresponse['success']) && $dataresponse['success']==true)
      {
        $email=$dataresponse['data']['_items'][0]['email'];
        $dataresponseemail=$user->get('users',array('source' => SOURCE, 'email' => $email, 'stato' => 1), '');
        //die(print('<pre>'.print_r($dataresponseemail,TRUE).'</pre>'));
        if(isset($dataresponseemail['success']) && $dataresponseemail['success']==true)
        {
          //die(print('attiva utente: '.$attiva_utente));
          if(is_array($dataresponseemail['data']['_items']) && !empty($dataresponseemail['data']['_items']))
          {
            $check_enable_status=false;
            
            foreach($dataresponseemail['data']['_items'] as $single_user)
            {
              if($single_user['status']==1) $check_enable_status=true;              
            }
            
            if($check_enable_status==false) $attiva_utente=true;
          }
          else $attiva_utente=true;
        }
      }

      if($attiva_utente)
      {
        $inputParam = array(
          'status' => 1,
          'gdpr' => 1,
          'id' => $user_id
        );
  
        $response=$user->curlPut('users',$inputParam);

        // Scrivo il file di log se la risposta è positiva
      
        if(isset($response['success']) && $response['success']==true)
        {
          if (is_dir(RUNTIME_DIRECTORY)) 
          {
            if(is_writable(RUNTIME_DIRECTORY))
            {
              $filename = RUNTIME_DIRECTORY.'/gdpr_data_response.txt';
              $now= new DateTime();
              $now->setTimezone(new DateTimeZone('Europe/Rome'));
              file_put_contents($filename, $now->format('Y-m-d H:i:s').' ### '.json_encode($inputParam). PHP_EOL, FILE_APPEND);
            }
          }
        }
        return $response;
      }
      else return  array('success' => FALSE, 'msg' => 'non attivabile'); 
    }
    catch(Exception $e)
    {
      return array('success' => FALSE, 'msg' => $e->getMessage());      
    }
  }
}
?>
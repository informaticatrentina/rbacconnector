<?php

/**
 * PageController
 * This class is used for including header, css and js file for header according to bootstrap css version.
 * class is extended by each controller for including header and inherit property of controller class also.
 * Copyright (c) 2014 <ahref Foundati on -- All rights reserved.
 * Author: Pradeep Kumar<pradeep@incaendo.com>
 * This file is part of <Timu>.
 * This file can not be copied and/or distributed without the express permission of <ahref Foundation. 
 */
class PageController extends Controller {

  public $header;

  /**
   * setHeader
   * function is used for set header html according to bootstrap css version
   * @param string bootstrap version
   */
  protected function setHeader($version) {
    $basePath = Yii::getPathOfAlias('application.modules.static.navbar');
    $baseUrl = Yii::app()->getAssetManager()->publish($basePath);
    switch ($version) {
      case '2.0':
        $this->header = $this->renderPartial(
          'application.modules.static.navbar.views.navbar.navbar2', array(), true
        );
        Yii::app()->clientScript->registerCssFile($baseUrl . '/css/navbar2.css');
        break;
      case '3.0':
        $this->header = $this->renderPartial(
          'application.modules.static.navbar.views.navbar.navbar3', array(), true
        );
        Yii::app()->clientScript->registerCssFile($baseUrl . '/css/navbar3.css');
        break;
      default :
        $this->header = $this->renderPartial(
          'application.modules.static.navbar.views.navbar.navbar2', array(), true
        );
        Yii::app()->clientScript->registerCssFile($baseUrl . '/css/navbar2.css');
        break;
    }
  }
}

?>

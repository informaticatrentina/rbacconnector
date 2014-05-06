<?php

class RbacconnectorModule extends CWebModule {

  public function init() {
    if (defined('SITE_THEME')) {
      Yii::app()->theme = SITE_THEME;
    }
    $this->setImport(array(
        'rbacconnector.models.*',
        'rbacconnector.components.*',
        'rbacconnector.assets.*',
    ));
  }

  public function beforeControllerAction($controller, $action) {
    $this->registerScript();
    if (parent::beforeControllerAction($controller, $action)) {
      return true;
    } else {
      return false;
    }
  }

  public function registerScript() {
    $basePath = Yii::getPathOfAlias('application.modules.rbacconnector.assets');
    $baseUrl = Yii::app()->getAssetManager()->publish($basePath);
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($baseUrl . '/css/bootstrap.css');
    $cs->registerCssFile($baseUrl . '/css/rbac.css');
  }

}

<?php

class RbacModule extends CWebModule {

  public function init() {
    $this->setImport(array(
        'rbac.models.*',
        'rbac.components.*',
        'rbac.assets.*',
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
    $basePath = Yii::getPathOfAlias('application.modules.rbac.assets');
    $baseUrl = Yii::app()->getAssetManager()->publish($basePath);
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($baseUrl . '/css/bootstrap.css');
    $cs->registerCssFile($baseUrl . '/css/rbac.css');
  }

}

<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/9/18
 * Time: 5:31 PM
 */
require_once __DIR__ .'../../../vendor/autoload.php';

$model = new \Bolzen\Core\Model\ModelLoader();
$session = new \Bolzen\Core\Session\Session();
$config = new \Bolzen\Core\Config\Config();
$database = new \Bolzen\Core\Database\Database($config);
$user = new \Bolzen\Core\User\User($session,$database);
$accessControl = new \Bolzen\Core\AccessControl\AccessControl($user,$session,$database,$config);
$model->setModelDependencies($session,$database,$accessControl,$user);

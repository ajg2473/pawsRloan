<?php
use Symfony\Component\Routing\Route;
use Bolzen\Core\RouteCollection\RouteCollection;

$config = $container->get('config');
$routes = new RouteCollection($config);

####################################
# Do not modify the line above
# Your Routes goes here
##################################

//Manager
$routes->add('Manager/index', new Route('{token}/manager/index', array(
        '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::index')));

//Administrator
$routes->add('Administrator/index', new Route('{token}/administrator/index', array(
    '_controller'=>'\Bolzen\Src\Administrator\Controller\AdministratorController::index')));


$routes->add('Staff/index', new Route('{token}/staff/index', array(
    '_controller'=>'\Bolzen\Src\Staff\Controller\StaffController::index'

)));

//Borrower
$routes->add('Borrower/index', new Route('{token}/borrower/index', array(
    '_controller'=>'\Bolzen\Src\Borrower\Controller\BorrowerController::index'
)));


// Staff
$routes->addAjax(new Route('{token}/staff/swipe', array(
    '_controller'=>'\Bolzen\Src\UniversityID\Controller\UniversityIDController::isVerify'
)));

$routes->addAjax(new Route('{token}/staff/history', array(
    '_controller'=>'\Bolzen\Src\Staff\Controller\StaffController::loadHistory'
)));


$routes->addAjax(new Route('{token}/staff/lateItem', array(
    '_controller'=>'\Bolzen\Src\Staff\Controller\StaffController::loadOverViewItems'
)));

$routes->addAjax(new Route('{token}/staff/checkOut', array(
    '_controller'=>'\Bolzen\Src\Staff\Controller\StaffController::listOut'
)));

$routes->addAjax(new Route('{token}/staff/checkIn', array(
    '_controller'=>'\Bolzen\Src\Staff\Controller\StaffController::checkIn'
)));

$routes->addAjax(new Route('{token}/staff/available', array(
    '_controller'=>'\Bolzen\Src\Staff\Controller\StaffController::availableItem'
)));

$routes->addAjax(new Route('{token}/staff/massivecheckout', array(
    '_controller'=>'\Bolzen\Src\Staff\Controller\StaffController::checkOutMassive'
)));




//Administrator add new Manager
$routes->addAjax(new Route('{token}/administrator/add', array(
    '_controller'=>'\Bolzen\Src\Administrator\Controller\AdministratorController::addManager'
)));


//Administrator delete manager
$routes->addAjax(new Route('{token}/administrator/del', array(
    '_controller'=>'\Bolzen\Src\Administrator\Controller\AdministratorController::deleteManager'
)));




// Borrower
$routes->add('Borrower/index', new Route('{token}/borrower/index', array(
    '_controller'=>'\Bolzen\Src\Borrower\Controller\BorrowerController::index')));

$routes->addAjax(new Route('{token}/manager/addCategory', array(
    '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::addCategory')));

$routes->addAjax(new Route('{token}/manager/loadFeeForm', array(
    '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::loadFeeForm')));

$routes->addAjax(new Route('{token}/manager/addStaff', array(
        '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::addStaff')));

$routes->addAjax(new Route('{token}/manager/removeStaff', array(
    '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::removeStaff')));

$routes->addAjax(new Route('{token}/manager/addFee', array(
    '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::addFee')));

$routes->addAjax(new Route('{token}/manager/categoryForm', array(
    '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::loadCategoryForm')));

$routes->addAjax(new Route('{token}/manager/inventoryForm', array(
    '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::loadInventoryForm')));

$routes->addAjax(new Route('{token}/manager/addInventory', array(
    '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::addInventory')));


$routes->addAjax(new Route('{token}/manager/addBlocklist', array(
    '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::addBlocklist')));


$routes->addAjax(new Route('{token}/manager/addPolicy', array(
    '_controller'=>'\Bolzen\Src\Manager\Controller\ManagerController::addPolicy')));


//login
$routes->add('{token}/login/login', new Route('login', array(
    '_controller'=>'\Bolzen\Src\Account\Controller\LoginController::login')));

$routes->addAjax(new Route('{token}/login/login', array(
    '_controller'=>'\Bolzen\Src\Account\Controller\LoginController::authenicate')));


$routes->addAjax(new Route("{token}/policy/{categoryid}", array(
    "_controller"=>'\Bolzen\Src\Policy\Controller\PolicyController::viewPolicy'
)));

$routes->addAjax(new Route("{token}/inventory/list", array(
    "_controller"=>'\Bolzen\Src\Inventory\Controller\InventoryController::listItems'
)));

$routes->addControllerOnly(new Route("account/{account}", array(
    "_controller"=>'\Bolzen\Src\Account\Controller\LoginController::account'
)));

//universityid
$routes->add('login/universityid', new Route('{token}/universityid', array(
    '_controller'=>'\Bolzen\Src\Account\Controller\LoginController::uid')));

$routes->addControllerOnly(new Route("index", array(
    "_controller"=>'\Bolzen\Src\Home\Controller\HomeController::index'
)));

$routes->addAjax(new Route("{token}/updateUniversityId", array(
    "_controller"=>'\Bolzen\Src\UniversityID\Controller\UniversityIDController::update'
)));

//Errors
$routes->add('Errors/denied', new Route('/accessDenied', array(
    '_controller'=>'\Bolzen\Src\Error\Controller\AccessDeniedController::denied'

)));


$routes->add('Errors/invalidSession', new Route('/invalidSession', array(
    '_controller'=>'\Bolzen\Src\Error\Controller\AccessDeniedController::invalidSession'

)));


###############################
# Do not modify below
##############################

return $routes->getRouteCollection();

<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/18/18
 * Time: 1:45 AM
 */

use Bolzen\Core\Container\Container;
use Symfony\Component\DependencyInjection\Reference;

$container = new \Bolzen\Core\Container\Container();
$container = $container->getContainer();
#Define your container dependencies or configuration here if need... Otherwise leave as default

$container->register('login', \Bolzen\Src\Service\Login\Login::class)
    ->setArguments(array(
        new Reference('accessControl')
    ));



//echo "<pre>".print_r($container,true)."</pre>";

#do not modified below this line
return $container;

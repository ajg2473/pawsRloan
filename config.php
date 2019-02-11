<?php


return array(

    'debug'=>false,
    'configureFor'=>'prod',
    'useDatabase'=>true,

    ##################################
    # APPLICATION HOSTING ENVIRONMENT #
    ##################################
    'devServer'=>array(
        'projectDirectory'=>'pawsrloan',
        'scheme'=>'http',
        'host'=>'localhost'

    ),

    'testServer'=>array(
        'projectDirectory'=>'~iste330t22',
        'scheme'=>'https',
        'host'=>'ist-serenity.main.ad.rit.edu'

    ),

    'prodServer'=>array(
        'projectDirectory'=>'ksc2650/paws',
        'scheme'=>'https',
        'host'=>'people.rit.edu'

    ),

    #####################################
    # APPLICATION DATABASE CONFIGURATION #
    #####################################

    'devDatabase'=>array(
        'prefix'=>'mysql',
        'host'=> 'localhost',
        'username'=>'root',
        'password'=>'team22#1!',
        'database'=>'paws'
    ),

    'testDatabase'=>array(
        'prefix'=>'mysql',
        'host'=> 'localhost',
        'username'=>'',
        'password'=>'',
        'database'=>''
    ),

    'prodDatabase'=>array(
        'prefix'=>'mysql',
        'host'=> 'localhost',
        'username'=>'root',
        'password'=>'team22#1!',
        'database'=>'paws'
    )

);

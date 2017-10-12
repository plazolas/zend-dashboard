<?php
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterServiceFactory;

return [
    // Session configuration.
    'session_config' => [
        // Session cookie will expire in 1 hour.
        'cookie_lifetime' => 60*60*1, #3600
        // Store session data on server maximum for 2 days.
        'gc_maxlifetime'     => 60*60*24*2  #864000
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ],
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class // could be: ArrayStorage,  SessionStorage, Redis etc..
    ],
    'service_manager' => [
        'factories' => [
            //Adapter::class => AdapterServiceFactory::class, // zf3
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',   // zf2
        ]
    ]
    
    // ...
];

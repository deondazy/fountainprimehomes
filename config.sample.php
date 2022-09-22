<?php

$config = new Okoye\Core\Config;

// Database configuration settings
$config->database = [
    'name'         => '',
    'host'         => '',
    'user'         => '',
    'password'     => '',
    'table_prefix' => '',
];

// Database table configurations
$config->database_tables = [
    'users'    => $config->database->table_prefix . 'users',
    'sessions' => $config->database->table_prefix . 'sessions',
    'requests' => $config->database->table_prefix . 'requests',
];

// Debug configuration settings
$config->debug = [
    'on'       => true, // Turn off for production
    'logPath'  => __DIR__ . '/error.log',
];

// Site configuration settings
$config->site = [
    'name'           => '',
    'url'            => '',
    'desc'           => '',
    'key'            => 'BtGKOhySDpPn65TRBFCm',
    'email'          => '', // The site support email
    'security_email' => '', // Site security email
    'noreply_email'  => '', // Site noreply email
    'info_email'     => '', // Site info email
    'request_expire' => strtotime('+1 day'),
];

// Cookie configuration settings
$config->cookie = [
    'login' => [
        'name'   => 'okye_auth',
        'expire' => strtotime('+30 days'),
    ],
    '_2fa' => [
        'name' => 'okye_2fa_auth',
        'secret' => '1qw23ddf4SDNMAA09IUyT_',
        'expire' => strtotime('+30 days'),
    ]
];

// Mail configuration settings (PHPMailer)
$config->mail = [
    'use_smtp'      => true,
    'charset'       => 'UTF-8',
    'smtp_auth'     => true,
    'smtp_host'     => '',
    'smtp_port'     => 465,
    'smtp_username' => '',
    'smtp_password' => '',
    'smtp_security' => 'ssl'
];

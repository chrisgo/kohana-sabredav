<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'webdav' => array(
        'root_directory' => 'public',
        'lock_file' => 'data/locks',
        'base_uri' => '/webdav',
    ),
    'auth' => array(
        'enable' => false,
        'role' => 'login',
        'realm' => 'kohana', 
    ),
);
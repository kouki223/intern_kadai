<?php
return array(
    'default' => array(
        'type'        => 'mysqli',
        'connection'  => array(
            'hostname'   => 'db',
            'port'       => 3306,
            'database'   => 'memoapp',
            'username'   => 'root',
            'password'   => 'root',
            'persistent' => false,
        ),
        'identifier'   => '`',
        'table_prefix' => '',
        'charset'      => 'utf8mb4',
        'collation'    => 'utf8mb4_unicode_ci',
        'enable_cache' => true,
        'profiling'    => false,
    ),
);
?>

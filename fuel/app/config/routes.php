<?php
return array(
	// ルーティング設定
	'_root_' => 'notes/index', // デフォルトのルート

	//ユーザー機能関連
	'users/login' => 'users/login',
	'users/check_email' => 'users/check_email',
	'users/password_login' => 'users/password_login',
	'users/register' => 'users/register',
	'users/create' => 'users/create',
	'logout'   => 'users/logout',

	//ノート機能関連（View用）
	'notes/index' => 'notes/index',
    'notes/create_note' => 'notes/create_note',
    'notes/detail/(:num)' => 'notes/detail/$1',

    //ノート機能関連（API用）
    'notes/api/notes' => 'notes/api_notes',
    'notes/api/delete_note' => 'notes/api_delete_note',
);
?>
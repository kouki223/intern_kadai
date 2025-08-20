<?php
return array(
	// ルーティング設定
	'_root_' => 'notes/index', // デフォルトのルート
	//ノート機能のルーティングとユーザー機能におけるルーティングを準備する
	//Viewが必要になるルーティングとViewの必要ないルーティングが存在する

	//ユーザー機能関連
	'users/login' => 'users/login',
	'register' => array('user/register'),
	'logout'   => array('user/logout'),

	//ノート機能関連
	'notes' => 'notes/index',
    'notes/view/(:num)' => 'notes/view/$1',
);
?>

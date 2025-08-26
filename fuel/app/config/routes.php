<?php
return array(
	// ルーティング設定
	'_root_' => 'notes/index', // デフォルトのルート
	//ノート機能のルーティングとユーザー機能におけるルーティングを準備する
	//Viewが必要になるルーティングとViewの必要ないルーティングが存在する

	//ユーザー機能関連
	'users/login' => 'users/login',// ログイン画面
	'users/check_email' => 'users/check_email',
	'users/password_login' => 'users/password_login',// パスワードログイン処理
	'users/register' => 'users/register',// ユーザー登録画面
	'users/create' => 'users/create',// ユーザー登録処理
	'logout'   => 'users/logout',// ログアウト処理

	//ノート機能関連
	'notes' => 'notes/index',
    'notes/view/(:num)' => 'notes/view/$1',
);
?>

<?php

return array(
    // ドライバ
    'driver' => array('Simpleauth'),

    'salt' => 'Th1s=mY0Wn_$@|+',
    'verify_multiple_logins' => true,//ログインの成功を確認した後(ユーザーの複数認証)も継続してログインを許可するか？
    'auth_cookie_name' => 'fuel_auth',
    'user_model' => 'Model_User', // ユーザーモデルの継承
    'salt' => 'soccer', // パスワードのハッシュ化に加えたセキュリティ
    'iterations' => '10000',
);
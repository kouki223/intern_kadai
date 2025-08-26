<?php

return array(
    // ドライバ
    'driver' => 'Simpleauth',
    'salt' => 'Th1s=mY0Wn_$@|+',
    'verify_multiple_logins' => true,//ログインの成功を確認した後(ユーザーの複数認証)も継続してログインを許可するか？
    'auth_cookie_name' => 'fuel_auth',
    'iterations' => '10000',
);
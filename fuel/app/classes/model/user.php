<?php
use Fuel\Core\DB;
use Auth\Auth;

class Model_User extends \Orm\Model
{
    // ユーザログイン認証関連
    public static function find_by_username($username)
    {
        // DBからメールアドレスに一致するユーザを検索(DBクラスのクエリビルダーを使用)
        $result = DB::SELECT('id', 'username', 'password', 'email') -> from('users')
            -> where('username', '=', $username)
            -> execute()
            -> as_array();// 結果を連想配列としてkeyにはカラムの値を、値にはDBのデータを格納して返してもらう

        if (empty($result)) {
            return null;
        }
        return $result[0];
    }

    // コントローラーからPOSTリクエストで受け取ったユーザー名とパスワードを受け取って検証する
    public static function verify_password($username, $password)
    {
        // DBからメールアドレスに一致するユーザを検索し変数userに格納
        $user = self::find_by_username($username);

        if (validate_user($username = $user['username'], $password = $user['password'])) {
            $user_id = $user['id'];
            return Auth::force_login($user_id);
        } else {
            return false;
        }
    }

    // 新規登録
    public static function create_user($username, $password)
    {
        // パスワードのハッシュ化
        $hashed_password = Auth::hash_password($password);

        // DBに新規ユーザを挿入(挿入するテーブルとカラムを指定する)
        $result = DB::insert('users')
            ->set(array(
                'username' => $username,
                'password' => $hashed_password,
            ))
            ->execute();// クエリの実行

        // 挿入が成功したかどうかを確認
        if (!$result) {
            return false; // 挿入が失敗した場合はfalseを返す
        }
        // 挿入されたユーザーIDで強制ログイン
        Auth::force_login($result[0]); 
        // 挿入が成功した場合はtrueを返す
        return $result[1] > 0; // $result[1]は挿入された行数
    }
}

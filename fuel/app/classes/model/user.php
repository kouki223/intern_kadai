<?php
use Fuel\Core\DB;
use Auth\Auth;

class Model_User extends \Orm\Model
{
    // ユーザログイン認証関連
    public static function find_by_email($email)
    {
        // DBからメールアドレスに一致するユーザを検索(DBクラスのクエリビルダーを使用)
        return DB::SELECT('id', 'email', 'password') -> from('users')
            -> where('email', '=', $email)
            -> execute()
            -> as_array();// 結果を連想配列としてkeyにはカラムの値を、値にはDBのデータを格納して返してもらう
    }

    // コントローラーからPOSTリクエストで受け取ったメールアドレスとパスワードを受け取って検証する
    public static function verify_password($email, $password)
    {
        // DBからメールアドレスに一致するユーザを検索し変数userに格納
        $user = self::find_by_email($email);

        // 事前に一度emailは検証しているが、再度、emailが存在するか検証する
        if (! $user) {
            return false; // ユーザ(email)が存在しない場合はfalseを返す
        }

        // Authクラスのverify_passwordメソッドを使用して、パスワードを検証
        return Auth::verify_password($password, $user['password']);   
    }
    // 新規登録
    public static function create_user($email, $password)
    {
        // パスワードのハッシュ化
        $hashed_password = Auth::hash_password($password);

        // DBに新規ユーザを挿入(挿入するテーブルとカラムを指定する)
        $result = DB::insert('users')
            ->set(array(
                'email' => $email,
                'password' => $hashed_password,
            ))
            ->execute();// クエリの実行

        // 挿入が成功したかどうかを確認
        if (!$result) {
            return false; // 挿入が失敗した場合はfalseを返す
        }
        // 挿入が成功した場合はtrueを返す
        return $result[1] > 0; // $result[1]は挿入された行数
    }
}

<?php
use Auth\Auth;

class Controller_Users extends Controller_Base
{

    protected $format = 'json'; // レスポンスフォーマットをJSONに設定

    public function action_login()
    {

        $this->template->title = 'ログイン(初期画面)';
        $this->template->content = View::forge('users/login');

    }

    public function action_check_email()
    {

        $this->template->title = 'ログイン(email)';

        // POSTリクエストでメールアドレスを受け取る
        $email = Input::post('email');

        // Model_Userを使用してPOSTされたメールアドレスに対応するレコードを変数userに格納
        $user = Model_User::find_by_email($email);

        // Model_Userのfind_by_emailメソッドで、POSTリクエストで受け取ったメールアドレスに一致するレコードをDBから検索し、結果を配列として返す
        if ($user)
        {
            return $this->response(['success' => true]);
        }
        else
        {
            return $this->response(['success' => false, 'message' => 'メールアドレスが登録されていません。']);
        }
    }

    public function action_password_login()
    {

        $this->template->title = 'ログイン(パスワード)';

        // POSTリクエストでログイン情報を受け取る
        $email = Input::post('email');
        $password = Input::post('password');

        // Authパッケージでパスワードを検証したいが,emailを要件に使うためまずはModel_Userのverify_passwordメソッドを使用する
        if (Model_User::verify_password($email, $password)) {

            // 取得したレコードからユーザーIDを指定して強制ログイン
            Auth::force_login($user['id']);

            Session::set_flash('success', 'ログインに成功しました。');
            return $this->response(['success' => true, 'redirect' => Uri::create('notes/index')]);
        }else {
            // ログイン失敗時の処理
            Session::set_flash('error', 'パスワードが間違っています。');
            return $this->response(['success' => false, 'message' => 'パスワードが間違っています。']);
        }
    }

    public function action_register()
    {
        $this->template->title = '新規登録';
        $this->template->content = View::forge('users/register');
    }

    public function action_create()
    {
        // POSTリクエストで新規登録情報を受け取る
        $email = Input::post('email');
        $password = Input::post('password');

        // Model_Userのcreate_userメソッドを使用して新規登録処理を行う
        if (Model_User::create_user($email, $password)) {
            Session::set_flash('success', '新規登録が完了しました。ノート一覧にリダイレクトします。');
            return Response::forge(json_encode(['success' => true, 'redirect' => Uri::create('notes')
        ]),array('Content-Type' => 'application/json'));
        } else {
            Session::set_flash('error', '新規登録に失敗しました。');
            return Response::forge(json_encode([
            'success' => false,
            'message' => '新規登録に失敗しました。'
        ]))->set_content_type('application/json');
        }
    }

    public function action_logout()
    {
        // ログアウト処理
        Auth::logout();
        Response::redirect('users/login');
    }

}
?>
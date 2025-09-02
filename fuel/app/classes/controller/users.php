<?php
use Auth\Auth;

class Controller_Users extends Controller_Base
{
    public function action_login()
    {
        $this->template->title = 'ログイン(初期画面)';
        $this->template->content = View::forge('users/login');
        $this->template->page = "login";

        if (Auth::check()) {
            Response::redirect('notes/index');
        }
    }

    public function post_check_username()
    {
        $this->is_api_request = true;
    
        try {
            $username = Input::post('username');
        
            if (empty($username)) {
                $response = [
                    'success' => false,
                    'message' => 'メールアドレスが入力されていません'
                ];
            } else {
                $user = Model_User::find_by_username($username);
            
                if ($user) {
                    $response = ['success' => true];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'ユーザーが登録されていません。'
                    ];
                }
            }
            // JSONレスポンスを返す
            return Response::forge(json_encode($response))
                ->set_header('Content-Type', 'application/json')
                ->set_header('Cache-Control', 'no-cache');
        }
        catch (Exception $e){
        // エラーログを出力
            Log::error('Check userame error: ' . $e->getMessage());
        
        return Response::forge(json_encode([
            'success' => false,
            'message' => 'サーバーエラーが発生しました'
        ]))->set_header('Content-Type', 'application/json');
        }
    }

    public function post_password_login()
    {
        $this->is_api_request = true;

    try {
        $username = Input::post('username');
        $password = Input::post('password');
        $hashed_input = Auth::instance()->hash_password($password);

        $user = Model_User::find_by_username($username);

        // emailの存在確認
        if ($user) {
            $stored_password = $user['password'];

            if ($hashed_input === $stored_password) {
                $user_id = $user['id'];
                Auth::force_login($user_id);

                return Response::forge(json_encode([
                    'success' => true,
                    'redirect' => Uri::create('/notes/index')
                ]))->set_header('Content-Type', 'application/json');
            } else {
                // パスワード不一致
                return Response::forge(json_encode([
                    'success' => false,
                    'message' => 'パスワードが正しくありません。'
                ]))->set_header('Content-Type', 'application/json');
            }
        } else {
            // ユーザーが存在しない
            return Response::forge(json_encode([
                'success' => false,
                'message' => 'メールアドレスが登録されていません。'
            ]))->set_header('Content-Type', 'application/json');
        }
        }
        catch (Exception $e) {
            // エラーログを出力
            Log::error('Login error: ' . $e->getMessage());

            return Response::forge(json_encode([
                'success' => false,
                'message' => 'サーバーエラーが発生しました'
            ]))->set_header('Content-Type', 'application/json');
        }
    }

    public function action_register()
    {
        $this->template->title = '新規登録';
        $this->template->content = View::forge('users/register');

        if (Auth::check()) {
            Response::redirect('notes/index');
        }
    }

    public function post_create()
    {
        $username = Input::post('username');
        $password = Input::post('password');

        try {
            
            $user_id = Model_User::create_user($username, $password); 
            
            if ($user_id) {
                if (Auth::login($username, $password)) {
                    return Response::redirect('/notes/index');
                } else {
                    return Response::forge(json_encode([
                        'success' => false,
                        'message' => '自動ログインに失敗しました。'
                    ]))->set_header('Content-Type', 'application/json');
                }
            } else {
                return Response::forge(json_encode([
                    'success' => false,
                    'message' => 'ユーザー登録に失敗しました。'
                ]))->set_header('Content-Type', 'application/json');
            }
        } catch (\Exception $e) {
            \Session::set_flash('error', 'ユーザー名はすでに使われています');

            return \Response::redirect('/users/register');
        }
    }

    public function action_logout()
    {
        Auth::logout();
        Response::redirect('users/login');
    }
}
?>

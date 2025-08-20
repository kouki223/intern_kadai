<?php
class Controller_Users extends Controller_Base
{
    public function action_login()
    {
        $this->template->title = 'ログイン';
        $this->template->content = View::forge('users/login');
    }

    public function action_register()
    {
        $this->template->title = '新規登録';
        $this->template->content = View::forge('users/register');
    }

    public function action_logout()
    {
        Auth::logout();
        Response::redirect('users/login');
    }

}
?>
<?php
class Controller_Notes extends Controller_Base
{

    public function before()//未ログインの場合は新規登録ページにリダイレクトするようにbeforeを使用する
    {
        parent::before();
        if (!Auth::check()) {
            Response::redirect('users/register');
        }
    }

    public function action_index()
    {
        $this->template->title   = 'ノート一覧';
        $this->template->content = View::forge('notes/index');
    }

    public function action_detail()
    {
        $this->template->title   = 'ノート詳細';
        $this->template->content = View::forge('notes/detail');
    }
}
?>
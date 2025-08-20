<?php

class Controller_Base extends Controller_Template
{
    public $template = 'template'; // ベースとなるテンプレートファイルを指定

    public function before()
    {
        parent::before();
        // テンプレートViewをセット
        $this->template->title   = 'デフォルトタイトル';
        $this->template->content = '';
    }

    public function after($response)
    {
        // アクションでセットされたcontentをテンプレートに渡す
        if (isset($this->template->content)) {
            $this->template->content = $this->template->content;
        }
        return Response::forge($this->template);
    }
}
?>
<?php

class Controller_Base extends Controller_Template
{
    public $template = 'template';
    protected $is_api_request = false;

    public function before()
    {
        parent::before();
        $this->template->title   = 'Note App';
        $this->template->content = '';
        $this->template->page = '';
    }

    public function after($response)
    {   
        if ($this->is_api_request) {
            return $response;
        }
        
        if ($response instanceof Response) {
            return $response;
        }
        $template_response = Response::forge($this->template);
        return $template_response;
    }
}
?>
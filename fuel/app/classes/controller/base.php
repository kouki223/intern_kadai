<?php

class Controller_Base extends Controller_Template
{
    public $template = 'template'; // ベースとなるテンプレートファイルを指定
    protected $is_api_request = false;

    public function before()
    {
        parent::before();
        // テンプレートViewをセット
        $this->template->title   = 'Note App';
        $this->template->content = '';
        $this->template->page = '';
    }

    public function after($response)
    {
        \Log::debug('After method called');
        \Log::debug('Response type: ' . gettype($response));
        \Log::debug('Response value: ' . var_export($response, true));
        
        if ($this->is_api_request) {
            \Log::debug('API request - returning original response');
            return $response;
        }
        
        if ($response instanceof Response) {
            \Log::debug('Response is already a Response object');
            return $response;
        }
        
        \Log::debug('Creating template response');
        $template_response = Response::forge($this->template);
        \Log::debug('Template response created: ' . get_class($template_response));
        return $template_response;
    }
}
?>
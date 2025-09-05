<?php

class Controller_Notes extends Controller_Base
{
    public function before()
    {
        parent::before();
        
        if (!Auth::check()) {
            Response::redirect('users/login');
        }
    }

    public function action_index()
    {
        $this->template->title = 'ノート一覧';
        $this->template->content = View::forge('notes/index');
        $this->template->page = "notes-index";
    }

    public function get_api_notes()
    {
        $this->is_api_request = true;

        try {
            $user_id = Auth::get('id');
            $notes = Model_Note::find_by_user($user_id);

            return Response::forge(json_encode([
                'success' => true,
                'notes' => $notes
            ]))->set_header('Content-Type', 'application/json');

        } catch (Exception $e) {
            return Response::forge(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]), 500)->set_header('Content-Type', 'application/json');
        }
    }

    public function post_create_note()
    {
        $this->is_api_request = true;

        try {
            $user_id = Auth::get('id');
            $title = Input::post('title', '新しいノート');
            $content = Input::post('content', '');

            $note = Model_Note::create_for_note($user_id, $title, $content);

            return Response::forge(json_encode(array(
                'success' => true,
                'note' => array(
                    'id' => $note->id,
                    'title' => $note->title,
                    'content' => $note->content,
                )
            )))->set_header('Content-Type', 'application/json');

        } catch (Exception $e) {         
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => 'ノートの作成に失敗しました'
            )), 500)->set_header('Content-Type', 'application/json');
        }
    }

    public function post_api_delete_note()
    {
        $this->is_api_request = true;
        
        try {
            $user_id = Auth::get('id');
            $note_id = Input::post('id');
            
            if (!$note_id) {
                throw new Exception('ノートIDが指定されていません');
            }
            
            $note = Model_Note::find($note_id);
            if (!$note) {
                throw new Exception('ノートが見つかりません');
            }
            
            if ($note->user_id != $user_id) {
                throw new Exception('このノートを削除する権限がありません');
            }
            
            $note->delete();
            
            return Response::forge(json_encode([
                'success' => true,
                'message' => 'ノートを削除しました'
            ]))->set_header('Content-Type', 'application/json');
            
        } catch (Exception $e) {

            return Response::forge(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]), 500)->set_header('Content-Type', 'application/json');
        }
    }

    public function action_detail($id = null)
    {
        if (!$id) {
            throw new HttpNotFoundException();
        }

        $user_id = Auth::get('id');
        $note = Model_Note::find_by_user_and_id($user_id, $id);

        if (!$note) {
            throw new HttpNotFoundException('ノートが見つかりません');
        }

        $this->template->title = '詳細ページ';
        $this->template->content = View::forge('notes/detail');
        $this->template->page = "note-detail";
    }

    public function get_api_note($id)
    {
        $this->is_api_request = true;

        try {
            if (!$id) {
                throw new Exception('ノートIDが指定されていません');
            }

            list(, $user_id) = Auth::get_user_id();
            $note = Model_Note::find_by_user_and_id($user_id, $id);

            if (!$note) {
                throw new Exception('ノートが見つかりません');
            }

            return Response::forge(json_encode(array(
                'success' => true,
                'note' => array(
                    'id' => $note['id'],
                    'title' => $note['title'],
                    'content' => $note['content'],
                    'created_at' => \Date::forge($note['created_at'])->format('%Y-%m-%d %H:%M:%S'),
                    'updated_at' => \Date::forge($note['updated_at'])->format('%Y-%m-%d %H:%M:%S'),
                )
                )), 200)->set_header('Content-Type', 'application/json');

        } catch (Exception $e) {     
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            )), 404)->set_header('Content-Type', 'application/json');
        }
    }

    public function put_api_update($id = null)
    {
        $this->is_api_request = true;

        try {
            if (!$id) {
                throw new Exception('ノートIDが指定されていません');
            }

            list(, $user_id) = Auth::get_user_id();
            $note = Model_Note::find_by_user_and_id($user_id, $id);

            if (!$note) {
                return Response::forge(json_encode([
                    'success' => false,
                    'message' => 'ノートが見つかりません'
                ]), 404)->set_header('Content-Type', 'application/json');
            }

            $title = Input::put('title', null);
            $content = Input::put('content', null);

            if ($title !== null) $note->title = $title;
            if ($content !== null) $note->content = $content;

            $note->save();

            return Response::forge(json_encode([
                'success' => true,
                'message' => '保存しました',
                'updated_at' => $note->updated_at
            ]), 200)->set_header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Response::forge(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]), 500)->set_header('Content-Type', 'application/json');
        }
    }
}
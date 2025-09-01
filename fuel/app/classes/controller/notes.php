<?php

class Controller_Notes extends Controller_Base
{
    public function before()
    {
        parent::before();
        
        // ログインチェック
        if (!Auth::check()) {
            Response::redirect('users/login');
        }
    }

    /**
     * ノート一覧ページ
     */
    public function action_index()
    {
        $this->template->title = 'ノート一覧';
        $this->template->content = View::forge('notes/index');
        $this->template->page = "notes-index";
    }

    // ノート一覧取得
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

    // ノート作成
    public function post_create_note()
    {
        $this->is_api_request = true;

        try {
            $user_id = Auth::get('id');
            $title = Input::post('title', '新しいノート');
            $content = Input::post('content', '');

            $note = Model_Note::create_for_user($user_id, $title, $content);

            return Response::forge(json_encode(array(
                'success' => true,
                'note' => array(
                    'id' => $note->id,
                    'title' => $note->title,
                    'content' => $note->content,
                )
            )))->set_header('Content-Type', 'application/json');

        } catch (Exception $e) {
            Log::error('Note creation error: ' . $e->getMessage());
            
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => 'ノートの作成に失敗しました'
            )), 500)->set_header('Content-Type', 'application/json');
        }
    }

    // ノート削除
    public function post_api_delete_note()
    {
        $this->is_api_request = true;
        
        try {
            $user_id = Auth::get('id');
            $note_id = Input::post('id');
            
            // ノートIDを関数から渡されているか確認
            if (!$note_id) {
                throw new Exception('ノートIDが指定されていません');
            }
            
            // AuthのユーザーIDとノートのユーザーIDが一致するか確認
            $note = Model_Note::find($note_id);
            if (!$note) {
                throw new Exception('ノートが見つかりません');
            }
            
            if ($note->user_id != $user_id) {
                throw new Exception('このノートを削除する権限がありません');
            }
            
            // 削除実行
            $note->delete();
            
            return Response::forge(json_encode([
                'success' => true,
                'message' => 'ノートを削除しました'
            ]))->set_header('Content-Type', 'application/json');
            
        } catch (Exception $e) {
            Log::error('Note deletion error: ' . $e->getMessage());
            
            return Response::forge(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]), 500)->set_header('Content-Type', 'application/json');
        }
    }

    // ノート詳細ページ
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

    // ノート詳細取得
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
            Log::error('Note detail error: ' . $e->getMessage());
            
            return Response::forge(json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            )), 404)->set_header('Content-Type', 'application/json');
        }
    }
    /**
     * API: ノート更新（自動保存用）
     */
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
                throw new Exception('ノートが見つかりません');
            }

            // PUT/PATCHデータの取得
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            if (!$data) {
                parse_str($input, $data); // x-www-form-urlencoded fallback
            }

            // モデルに更新処理を実装しているならそれを利用
            if (method_exists($note, 'update_content')) {
                $note->update_content($data);
            } else {
                if (isset($data['title'])) {
                    $note->title = $data['title'];
                }
                if (isset($data['content'])) {
                    $note->content = $data['content'];
                }
                $note->updated_at = \Date::forge()->get_timestamp();
                $note->save();
            }

            // 保存後の更新日時を取得
            $note->reload();

            return Response::forge(json_encode(array(
                'success' => true,
                'message' => '保存しました',
                'updated_at' => $note->get_formatted_updated_at()
            )), 200)->set_header('Content-Type', 'application/json');

        } catch (Exception $e) {
            Log::error('Note update error: ' . $e->getMessage());

            return Response::forge(json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            )), 500)->set_header('Content-Type', 'application/json');
        }
    }
}
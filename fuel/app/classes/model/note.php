<?php
use Fuel\Core\DB;
use Auth\Auth;

class Model_Note extends \Orm\Model
{
    //　静的オブジェクトの定義
    protected static $_properties = array(
        'id',
        'user_id',
        'title',
        'content',
        'created_at',
        'updated_at',
    );

    public static function find_by_user($user_id)
    {
        // DBから指定されたユーザーIDに一致するノートを検索して配列で返す
        $result = DB::SELECT('id', 'user_id', 'title', 'content', 'created_at', 'updated_at')
            -> from('notes')
            -> where('user_id', '=', $user_id)
            -> order_by('created_at', 'desc')
            -> execute()
            -> as_array();

        if (empty($result)) {
            return [];
        }
        return $result;
    }

    public static function find_by_user_and_id($user_id, $note_id)
    {
        // DBから指定されたユーザーIDとノートIDに一致する最初のノートを検索して返す
        $result = DB::SELECT('id', 'user_id', 'title', 'content', 'created_at', 'updated_at')
            -> from('notes')
            -> where('user_id', '=', $user_id)
            -> and_where('id', '=', $note_id)
            -> execute()
            -> as_array();

        if (empty($result)) {
            return null;
        }
        return $result[0];
    }

    public static function create_note($user_id, $title, $content)
    {
        // DBに新しいノートを挿入
        $result = DB::insert('notes')
            ->set(array(
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'created_at' => \Date::forge()->get_timestamp(),
                'updated_at' => \Date::forge()->get_timestamp(),
            ))
            ->execute();

        // 挿入が成功したかどうかを確認
        return $result[1] > 0; // $result[1]は挿入された行数
    }

    public static function update_note($user_id, $note_id, $title, $content)
    {
        // DBの指定されたノートを更新
        $result = DB::update('notes')
            ->set(array(
                'title' => $title,
                'content' => $content,
                'updated_at' => \Date::forge()->get_timestamp(),
            ))
            ->where('user_id', '=', $user_id)
            ->and_where('id', '=', $note_id)
            ->execute();

        // 更新が成功したかどうかを確認
        return $result > 0; // 更新された行数を返す
    }

    public static function delete_note($user_id, $note_id)
    {
        // DBから指定されたノートを削除
        $result = DB::delete('notes')
            ->where('user_id', '=', $user_id)
            ->and_where('id', '=', $note_id)
            ->execute();

        // 削除が成功したかどうかを確認
        return $result > 0; // 削除された行数を返す
    }

}
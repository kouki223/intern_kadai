<?php
use Fuel\Core\DB;
use Auth\Auth;

class Model_Note extends \Orm\Model
{

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

    public static function find_by_user_and_id($user_id, $id)
    {
        return self::find('first', [
            'where' => [
                ['user_id', $user_id],
                ['id', $id],
            ]
        ]);
    }

    public static function create_for_note($user_id, $title, $content)
    {
        list($id, $rows) = DB::insert('notes')
            ->set([
                'user_id'    => $user_id,
                'title'      => $title,
                'content'    => $content,
                'created_at' => \Date::forge()->get_timestamp(),
                'updated_at' => \Date::forge()->get_timestamp(),
            ])
            ->execute();
            
        if ($rows > 0) {
            return static::find($id);
        }

        return null;
    }

    public static function update_note($user_id, $note_id, $title, $content)
    {
        $result = DB::update('notes')
            ->set(array(
                'title' => $title,
                'content' => $content,
                'updated_at' => \Date::forge()->get_timestamp(),
            ))
            ->where('user_id', '=', $user_id)
            ->and_where('id', '=', $note_id)
            ->execute();

        return $result > 0;
    }

    public static function delete_note($user_id, $note_id)
    {
        $result = DB::delete('notes')
            ->where('user_id', '=', $user_id)
            ->and_where('id', '=', $note_id)
            ->execute();

        return $result > 0;
    }

}
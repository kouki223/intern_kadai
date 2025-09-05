<?php
use Fuel\Core\DB;
use Auth\Auth;

class Model_User extends \Orm\Model
{

    protected static $_properties = array(
        'id', 
        'username',
        'password',
        'created_at',
        'updated_at',
    );
    
    public static function find_by_username($username)
    {

        $result = DB::SELECT('id', 'username', 'password', 'email') -> from('users')
            -> where('username', '=', $username)
            -> execute()
            -> as_array();

        if (empty($result)) {
            return null;
        }
        return $result[0];
    }

    public static function create_user($username, $password)
    {
        $hashed_password = Auth::hash_password($password);

        $result = DB::insert('users')
            ->set(array(
                'username' => $username,
                'password' => $hashed_password,
            ))
            ->execute();

        if (!$result) {
            return false;
        } 
        return $result[1] > 0; // $result[1]は挿入された行数
    }
}

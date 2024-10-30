<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 25-Jan-15
 * Time: 15:04
 */

namespace models;


class User {
    /**
     * @return bool if logged in
     */
    public static function IsLoggedIn(){
        return self::Current() !== null;
    }

    /**
     * @return null|\WP_User
     */
    public static function Current(){
        $user = \wp_get_current_user();
        if($user->ID == 0)
            return null;
        return $user;
    }
}
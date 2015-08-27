<?php
//Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class QHShopSession
{
    protected static $instance;

    protected function __construct()
    {

    }

    protected function __clone()
    {

    }

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new QHShopSession();
        }

        return self::$instance;
    }

    public static function run()
    {
        $instance = self::get_instance();

        add_action('init', function(){
            if (!session_id()) {
                session_start();
            }
        });

        return $instance;
    }

    public static function get($session_key = null)
    {
        return (is_null($session_key)) && !isset($_SESSION[$session_key]) ? false : $_SESSION[$session_key];
    }

    public static function insert($session_key = null, $session_value = array())
    {
        $instance = self::get_instance();
        if (!is_null($session_key) && !isset($_SESSION[$session_key])) {
            $_SESSION[$session_key] = $session_value;
        } elseif (!is_null($session_key)) {
            $_SESSION[$session_key] = array_merge($_SESSION[$session_key], $session_value);
        }
        return $instance;
    }

    public static function update($session_key = null, $session_value = array())
    {
        if (!is_null($session_key) && isset($_SESSION[$session_key])) {
            $_SESSION[$session_key] = $session_value;
        } elseif (!is_null($session_key)) {
            self::insert($session_key, $session_value);
        }
        return self::$instance;
    }

    public static function destroy($session_key = null)
    {
        if (!is_null($session_key)) {
            session_unset($_SESSION[$session_key]);
        } else {
            session_destroy();
        }
        return self::$instance;
    }
}

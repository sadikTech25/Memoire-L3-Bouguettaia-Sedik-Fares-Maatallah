<?php
class core_manager
{
    private static $local_string_db_connection = "mysql:host=localhost;dbname=ultraevent_db";
    private static $local_username_db_connection = "root";
    private static $local_password_db_connection = "";
    private static $online_string_db_connection = "mysql:host=????????;dbname=????????";
    private static $online_username_db_connection = "????????";
    private static $online_password_db_connection = "??????????";

    public static function generate_reference($saltkey)
    {
        $crypto = substr(md5(sha1($saltKey.date('Y-m-d H:i:s.u')).$saltkey), 12, 20);
        return $crypto;
    }
    public static function display_label($code)
    {
        $label = '';

        switch ($code) 
        {            
            case 0 : $label = 'Tables'; break;
            case 1 : $label = 'Chairs'; break;
            case 2 : $label = 'Speakers'; break;
            case 3 : $label = 'Screens'; break;
            case 4 : $label = 'Photograph'; break;
            case 5 : $label = 'Security Agent'; break;
            case 6 : $label = 'Cars'; break;
            case 7 : $label = 'Salle Des Fétes'; break;

        }

        return $label;
    }
    public static function generate_pdo()
    {
        $pdo = NULL;

        try
        {
            $pdo = ($_SERVER['SERVER_NAME'] == "localhost" || $_SERVER['SERVER_NAME'] == "127.0.0.1") ? 
                    new PDO(self::$local_string_db_connection, self::$local_username_db_connection, self::$local_password_db_connection) :
                    new PDO(self::$online_string_db_connection, self::$online_username_db_connection, self::$online_password_db_connection);
        }
        catch(Exception $exception)
        {
            self::treat_exception($exception);
        }

        return $pdo;
    }

    public static function throw_exception($msg)
    {
        throw new Exception($msg);
    }

    public static function treat_exception($e)
    {
        echo $e->getMessage();
    }
}

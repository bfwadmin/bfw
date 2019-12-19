<?php
namespace Lib\Cache;

class CacheFactory
{
    public static function GetInstance($_connarray = null) {
        if (is_null ( $_connarray )) {
            if (! isset ( self::$_instance ['default'] ) || is_null ( self::$_instance ['default'] )) {
                self::$_instance ['default'] = Core::LoadClass ( "Lib\\Cache\\" . DB_TYPE );
            }
            return self::$_instance ['default'];
        } else {
            if (!empty ( $_connarray )) {
                $_db_connstr= var_export($_connarray,true);
                $key = md5 ( $_db_connstr );
                if (! isset ( self::$_instance [$key] ) || is_null ( self::$_instance [$key] )) {
                    if (isset ( $_connarray ['dbtype'] )) {
                        self::$_instance [$key] = Core::LoadClass ( "Lib\\Db\\" . $_connarray ['dbtype'], $_connarray );
                    } else {
                        self::$_instance [$key] = Core::LoadClass ( "Lib\\Db\\" . DB_TYPE, $_connarray );
                    }
                }
                return self::$_instance [$key];
            } else {
                throw new \Exception ( "config wrong" );
            }
        }
    }
}

?>
<?php 

namespace Db;

include_once __DIR__ . '/../configs/dbconfig.php';

use \PDO;

//Синглтон для коннекта к базе

class Db 
{
    protected static $instance; 

    private function __construct() {} 
    private function __clone() {} 

    //Создаем инстанс коннекта, если еще нет, иначе возвращаем его
    public static function getInstance() :\PDO 
    {
        if (self::$instance === null) {
            $opt = 
            [
				\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];
            try {
                self::$instance = new \PDO(
                    "mysql:host={$dbconfig['DBHOST']};dbname={$dbconfig['DBNAME']};charset={$dbconfig['DBCHARSET']}",
                    $dbconfig['DBLOGIN'],
                    $dbconfig['DBPASS'],
                    $opt
                );
            }
            catch (\PDOException $e) {
                die("db connection error: " . $e->getMessage());
            }            
        }
        return self::$instance;
    }  

    public static function request(string $sql, ?array $params = null) :\PDOStatement
    {
        try { 
            if (!isset($params)) {
                $stmt = self::$instance->query($sql);
            } else {
                $stmt = self::$instance->prepare($sql);
                $stmt->execute($params);
            }
        }
        catch (\PDOException $e) {
            die("db request error: " . $e->getMessage());
        }
        return $stmt;
    }

    public static function fetch(\PDOStatement $statement): array
    {
        $data = $statement->fetch();

        return is_array($data) ? $data : [];
    }
}

<?php
require_once 'config.php';

class Db{
    protected static $_instance = null;
    protected static $db_name = DB_NAME;
    protected static $db_host = DB_HOST;
    protected static $db_user = DB_USER;
    protected static $db_password = DB_PASSWORD;
    private function __construct(){}
    public static function init_Db(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public static function connect(){
        $dbh = new PDO('mysql:host='.self::$db_host.'; dbname='.self::$db_name.'; charset=utf8', self::$db_user, self::$db_password);
        return $dbh;
    }
    private static function fillArray($arr1, $arr2){
        foreach($arr1 as $key => $value){
            $filledArray[$value] = $arr2[$key];
        }
        return $filledArray;
    }
    public static function update($table, $colName, $colValue, $id){
        $dbh = self::connect();
        $sql = "UPDATE $table SET $colName= $colValue WHERE id = $id";
        $sth = $dbh->prepare($sql);
        $sth->execute();
    }
    public static function delete($table, $id){
        $dbh = self::connect();
        $sth = $dbh->prepare("DELETE FROM $table WHERE id = $id");
        $sth->execute();
    }
    public static function insert($table, $keys, $values){
        $dbh = self::connect();
        $createQuestion = [];
        $createQuestion = self::fillArray($keys, $values);
        $columns = [];
        $rows = [];
        foreach($createQuestion as $column => $row){
            if($row){
              array_push($columns, $column);
              array_push($rows, $row);
            }  
        }
        $columns_str = implode(', ', $columns);
        for ($i=0; $i < count($rows); $i++){
            if($i === (count($rows) - 1)){
                $rows_str .= "'" . $rows[$i] . "'";
            } else {
                $rows_str .= "'" . $rows[$i] . "', "; 
            }
        }
        $sql = "INSERT INTO `$table` ($columns_str) VALUES ($rows_str)";
        $sth = $dbh->prepare($sql);
        return $sth->execute();
    }
    public static function select($query){
        $dbh = self::connect();
        $sth = $dbh->prepare($query);
        $sth->execute();
        $result = $sth -> fetchAll();
        return $result;
    }
}

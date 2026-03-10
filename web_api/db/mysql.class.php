<?php

/**
 * Provides support for MySQL database.
 * @author Jakub Konieczka
 *
 */
class MySql implements IDataBase
{

    private $_link;
    private $_schema;
    private $PDO;

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#Connect()
     */
    public function Connect($server, $port, $dbName, $schema, $username, $password)
    {

        $dsn = "mysql:dbname=$dbName;host=$server";

        try {

            $this->PDO = new PDO($dsn, $username, $password);
            $this->PDO->query('SET NAMES "utf8"');

            if (Config::$ShowErrors)
                $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            else
                $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        } catch (Exception $ex) {
            die("Unable to connect to MySQL server. Details:<br>" . $ex->getMessage());
        }
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#Disconnect()
     */
    public function Disconnect()
    {

        $this->PDO = null;
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#ExecuteQuery()
     */
    public function ExecuteQuery($query)
    {
        //echo "<br>query: $query";
        DataBase::$QUERY_COUNT++;
        $query = str_replace("#S#", $this->_schema ?? "", $query);
        $result = $this->PDO->query($query);
        
        if(Config::$Debugbar && class_exists('Debugbar')) {Debugbar::addSQL($query);}

        if ($result == false) {
            Errors::LogError("MySql:ExecuteQuery", $this->LastError() . " ::: " . $query);
        }
        return $result;
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#ExecuteQueryWithParams()
     */
    public function ExecuteQueryWithParams($query, $params, $remove = false)
    {
        //echo "<br>query: $query";
        DataBase::$QUERY_COUNT++;
        $query = str_replace("#S#", $this->_schema ?? "", $query);

        $pre = $this->PDO->prepare($query);

        if ($remove) {
            echo "<br/><br/>";
            var_dump($params);
            echo "<br/>" . $query . "<br/><br/>";
        }
        $i = 0;
        foreach ($params as $key => &$val) {
            $i++;
            if (is_int($val)) {
                $pre->bindParam($i, $val, PDO::PARAM_INT);
            } else {
                $pre->bindParam($i, $val, PDO::PARAM_STR);
            }
        }
        
        if(Config::$Debugbar && class_exists('Debugbar')) {Debugbar::addSQL($query, $params);}

        $pre->execute();
        //$pre->debugDumpParams();
        return $pre;
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#FetchArray()
     */
    public function FetchArray($result)
    {

        if ($result != false)
            return $result->fetch();
        //return mysql_fetch_array($result);
        else
            return array();
    }
    
    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#FetchObject()
     */
    public function FetchObject($result)
    {
        if ($result != false)
            return $result->fetchObject();
        else
            return false;
    }
    

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#UniqueResult()
     */
    public function UniqueResult($query)
    {
        $result = self::ExecuteQuery($query);
        if ($result != false) {
            $row = self::FetchArray($result);
            return $row[0];
        } else
            return null;
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#LastInsertedId()
     */
    public function LastInsertedId($table)
    {
        return $this->PDO->lastInsertId(); //mysql_insert_id($this->_link);	
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#LastError()
     */
    public function LastError()
    {

        $err = $this->PDO->errorInfo();
        return $err[0] != '00000' ? $err[0] : '';
        /*
          $errorNo = mysql_errno($this->_link);
          if($errorNo > 0)
          return '';//$errorNo . ": " . mysql_error($this->_link);
          else
          return "";
         */
    }

}

if (!function_exists('mysql_query_params')) {

    function mysql_query_params__callback($at)
    {
        global $mysql_query_params__parameters;
        return $mysql_query_params__parameters[$at[1] - 1];
    }

    function mysql_query_params($query, $parameters = array(), $database = false)
    {
        // Escape parameters as required & build parameters for callback function
        global $mysql_query_params__parameters;
        foreach ($parameters as $k => $v)
            $parameters[$k] = ( is_int($v) ? $v : ( NULL === $v ? 'NULL' : "'" . mysql_real_escape_string($v) . "'" ) );
        $mysql_query_params__parameters = $parameters;

        // Call using mysql_query
        $query2 = preg_replace_callback('/\$([0-9]+)/', 'mysql_query_params__callback', $query);
        //echo "<br>query: $query2";
        if (false === $database)
            return mysql_query($query2);
        else
            return mysql_query($query2, $database);
    }

}
?>
<?php

/**
 * Provides support for PostgreSql database.
 * @author Jakub Konieczka
 *
 */
class PostgreSql implements IDataBase
{

    private $_link;
    private $_schema;

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#Connect()
     */
    public function Connect($server, $port, $dbName, $schema, $username, $password)
    {
        try {
            $this->_schema = $schema . ".";
            $this->_link = @pg_connect("host=$server port=5433 dbname=$dbName user=$username password=$password");
            if ($this->_link) {
                
            } else {
                $lastError = error_get_last();
                throw new Exception($lastError["message"]);
            }
        } catch (Exception $ex) {
            die("Unable to connect to PostgreSQL server. Details:<br>" . $ex->getMessage());
        }
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#Disconnect()
     */
    public function Disconnect()
    {
        @pg_close($this->_link);
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#ExecuteQuery()
     */
    public function ExecuteQuery($query)
    {
        //echo "<br>query: $query";
        $query = str_replace("#S#", $this->_schema, $query);
        $query = str_replace("`", "\"", $query);
        $result = @pg_query($this->_link, $query);
        if ($result == false) {
            Errors::LogError("PostgreSql:ExecuteQuery", $this->LastError());
        }
        return $result;
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#ExecuteQueryWithParams()
     */
    public function ExecuteQueryWithParams($query, $params)
    {
        $query = str_replace("#S#", $this->_schema, $query);
        $query = str_replace("\"", "", $query);
        $query = str_replace("`", "\"", $query);
        //echo "<br>query: $query";
        $result = pg_query_params($this->_link, $query, $params);
        if ($result == false) {
            Errors::LogError("PostgreSql:ExecuteQueryWithParams", $this->LastError());
        }
        return $result;
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#FetchArray()
     */
    public function FetchArray($result)
    {
        if ($result != false)
            return pg_fetch_array($result);
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
            return pg_fetch_object($result);
        else
            return array();
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
        $result = self::ExecuteQuery("SELECT MAX(id) FROM " . $this->_schema . $table);
        if ($result != false) {
            $row = self::FetchArray($result);
            return $row[0];
        } else
            return null;
    }

    /**
     * (non-PHPdoc)
     * @see class/db/IDataBase#LastError()
     */
    public function LastError()
    {
        $error = pg_last_error($this->_link);
        if ($error != null && $error != "")
            return $error;
        else
            return "";
    }

}

?>
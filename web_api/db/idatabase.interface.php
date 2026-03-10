<?php

/**
 * Provides primary methods of datatbase server.
 * @author Jakub Konieczka
 *
 */
interface IDataBase {
	
	/**
	 * Connect to the database server using the given parameters.
	 * @param $server
	 * @param $port
	 * @param $dbName
	 * @param $username
	 * @param $password
	 */
	public function Connect($server, $port, $dbName, $schema, $username, $password);
	/**
	 * Closes the connection to the database.
	 */
	public function Disconnect();
	/**
	 * Executes given SQL query and returns the result as #resource.
	 * @param $query
	 * @return resource
	 */
	public function ExecuteQuery($query);
	/**
	 * Executes given SQL query using the given parameters and returns the result as a #resource.
	 * The parameters in the query must be named as "$ 1", "$ 2", etc.
	 * The values of the parameters must be supplied as an array: "array ($ param1, $ param2)", etc.
	 * @param $query
	 * @param $params
	 * @return unknown_type
	 */
	public function ExecuteQueryWithParams($query, $params);
	/**
	 * Processes the query result, and returns the next row as associative arrays.
	 * @param $result
	 * @return array
	 */
	public function FetchArray($result);
        
        /**
         * Processes the query result, and returns the current row of a result set as an object.
         * @param $result
         * @return stdClass
         */
        public function FetchObject($result);
	/**
	 * Executes given query and returns the first column of the first row.
	 * @param $query
	 * @return unknown_type
	 */
	public function UniqueResult($query);
	/**
	 * Retrieves the ID generated for an AUTO_INCREMENT column by the previous INSERT query.
	 * @param $table
	 * @return int
	 */
	public function LastInsertedId($table);
	/**
	 * Returns the last error that occurred, if no error returns an empty string.
	 * @return string
	 */
	public function LastError();
	
}

?>
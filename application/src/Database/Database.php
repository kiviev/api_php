<?php

namespace Api\Database;

use Api\Config\Env;
use PDO;

/**
*
*/
class Database {
	protected  $CONF;
	protected  $host;
	protected  $user;
	protected  $pass;
	protected  $db;
	protected  $conex;
	protected  $query;
	protected  $result;
    /**
     *Funcion constructora , con ella generamos una instancia de la clase, utiliza el método conexion() para que cuando se cree la instancia, se conecte automáticamente a la base de datos
     */
    public function __construct()
	{
		$this->CONF = Env::getDbConfig();
		$this->host = $this->CONF['host'];
		$this->user = $this->CONF['username'];
		$this->pass = $this->CONF['password'];
		$this->db   = $this->CONF['dbname'];
		$this->conex = $this->conexion($this->host , $this->user , $this->pass ,$this->db);
	}

	/**
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $db
     * @return PDO
     */
    private function conexion($host,$user,$pass,$db)
	{
		try {
		  $con = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
		  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  return $con;
		} catch(PDOException $e) {
		  echo 'Error: ' . $e->getMessage();
		}
	}

	/**
	 * query set the query
	 *
	 * @param string $q the SQL query
	 * @return object $this returns the own instance to be able to chain methods
	 */
	public function query($q)
	{
		$this->query = $q;
		return $this;
	}

	/**
	 * get get the result of executing a SQL query
	 *
	 * @return object $this returns the own instance to be able to chain methods
	 */
	public function get()
	{
		$this->result = $this->conex->query($this->query);
		return $this;
	}

	/**
	 * exequery Execute a SQL query
	 * @param string $query The SQL query
	 * @return  result of the query
	 */
	public function exequery($query) {

		$out = $this->conex->query($query);
		return $out;
	}

	/**
	 * formatearParaDB  Process the argument by escaping it for database. DO NOT add apostrophes
	 * @param  mixed $st SQL query
	 * @param  string
	 */
	public  function formatearParaDB($st) {
		// Carácteres que dan problemas al pasar por JSON. Esta lista puede crecer.
		$st = str_replace("\\u2018","\\u0091",$st); // ‘
		$st = str_replace("\\u2019","\\u0092",$st); // ’
		// $st = str_replace("\\u201c","\\u0093",$st); // “
		$st = str_replace("\\u201d","\\u0094",$st); // ”
		$st = str_replace("\\u2022","\\u0095",$st); // •
		$st = str_replace("\\u2026","\\u0085",$st); //…
		$st = str_replace("\\u2122","\\u0099",$st); // ™
		return $this->query = $st;
	}

	/**
	 * loadResult Return the value of the first row and column to choose from the query. Null in case of error or no results found
	 * @param string $query The SQL query
	 * @param int $col The column number to obtain (by default the first, that is, number 0)
	 * @return The result of the query
	 */
	public function loadResult($unique = false, $col = 0) {
		if($unique) $this->query = $this->prepareQueryUniqueRow($this->query);
		$res = $this->exequery($this->query);
		$value = null;
		if ($res) {
			if ($row = $res->fetchAll(PDO::FETCH_ASSOC) ) {
				$value= $row;
				if($col){
					$value = $row[$col];
				}
			}
		}
		return  $value ?? [];
	}

	/**
	 * prepareQueryUniqueRow Prepare the query to return a single row
	 *
	 * @param string $query The SQL query
	 * @return string Query transformed
	 */
	protected function prepareQueryUniqueRow($query) {
		$query = preg_replace('#[\s]*;[\s]*$#', '', $query); //eliminar último punto y coma ;

		//si ya viene limit no incluirlo
		if (preg_match('/[\s]LIMIT[\s]+[0-9,\s]+$/i', $query)) return $query;

		//no se puede añadir LIMIT 1 cuando existe una función en el select, como count(*)
		if (preg_match('/^[\s]*SELECT[\s](.*?)\((.*)[\s]FROM[\s]/i', $query)) return $query;

		//LIMIT no siempre va en la última posición
		if (preg_match('/[\s](FOR UPDATE|LOCK IN SHARE MODE)[\s]*$/i', $query)) return $query;

		$query .= ' LIMIT 1';
		return $query;
	}

	/**
	 * loacAssoc 
	 *
	 * @param boolean $unique if we want a single result
	 * @return array result query
	 */
	public function loadAssoc($unique = false){
		if($unique) $this->query = $this->prepareQueryUniqueRow($this->query);
		$res = $this->exequery($this->query);
		$row = null;
		if ($res) {
			if ($object = $res->fetch()) {
				$row = $object;
			}
		}
		return $row;
	}

	/**
	 * loadObject transforms the query into an object
	 *
	 * @param boolean $unique if we want a single result
	 * @return object result query
	 */
	public function loadObject($unique = false){
		if($unique) $this->query = $this->prepareQueryUniqueRow($this->query);
		$res = $this->exequery($this->query);
		$obj = null;
		if ($res) {
			if ($object = $res->fetchObject()) {
				$obj = $object;
			}
		}
		return $obj;
	}

	/**
	 * loadObjectList transforms the query into an array of objects
	 *
	 * @param boolean $unique if we want a single result
	 * @return array
	 */
	public function loadObjectList($unique = false) {
		if($unique) $this->query = $this->prepareQueryUniqueRow($this->query);
		$res = $this->exequery($this->query);
		$array = null;
		if ($res) {
			$array = array();
			while ($row = $res->fetchObject()) {
				$array[] = $row;
			}
		}
		return $array;
	}

	/**
	 * loadResultArray
	 *
	 * @param string $col The indexes of the main array will be made by the value of the column of each row. Null for array of integers
	 * @param integer $limit Se limita el numero total de elementos a devolver
	 * @return array
	 */
	public function loadResultArray($col = 0, $limit = -1) {
		$res = $this->exequery($this->query);
		$array = null;
		if ($res) {
			$array = array();
			while (($row = $res->fetch()) && ($limit==-1 || count($array) < $limit)) {
				$array[] = $row[$col];
			}
		}
		return $array;
	}

	/**
	 * loadAssocList Returns an array of arrays, which will be each response row. Null in case of error. Empty array if there were no results
	 * @param string $query The SQL query
	 * @param string $col The indexes of the main array will be made by the value of the column of each row. Null for array of integers
	 * @param integer $limit Se limita el numero total de elementos a devolver
	 * @return array de arrays
	 */
	public function loadAssocList($query = null, $col = null, $limit = -1) {
		if(!$query) $query = $this->query;
		$res = $this->exequery($query);
		$array = null;
		if ($res) {
			$array = array();
			while (($row = $res->fetchAll(PDO::FETCH_ASSOC)) && ($limit==-1 || count($array) < $limit)) {
				if ($col) {
					if (!isset($row[$col])) {
						// FWUtils::log(BlinkFW_LogFile::LEVEL_ERROR, '[ERROR MySQL : No existe la columa '.$col.'] : '.$this->query);
						break;
					}
					$array[$row[$col]] = $row;
				}
				else {
					$array[] = $row;
				}
			}
		}
		return $array;
	}

    /**
	 * select prepare the select part of the SQl query
     * @param array $str, array of strings that serves to give the select all the arguments that we want to look for
     * @param string $table  we pass the table on which we want to select
     * @return object $this returns the own instance to be able to chain methods
     */
    public function select(Array $str,  $table)
	{
		$from=trim($table);
		$this->query='SELECT ';
		for ($i=0; $i <count($str) ; $i++) {
		$str[$i]=trim($str[$i]);
			$this->query .= $str[$i];
			if($i+1 <count($str)){
			 	$this->query .= ' , ';
			}
		}
		$this->query .= ' FROM '.$from . ';' ;
		return $this;
	}

    /**
	 * where prepare the where part of the SQl query
     * @param string $column name of the column
     * @param string $operator symbol to be able to compare ej (>, <, <=,> =, =, <>)
     * @param string $value value we seek
     * @return object $this returns the own instance to be able to chain methods
     */
    public function where($column ,$operator, $value)
	{
		$column =trim($column );
		$operator=trim($operator);
		$value=trim($value);
		$this->query =$this->no_semicolon($this->query);
		$this->query  .= ' WHERE ' .$column  .'  '.$operator.' ' .'"' .$value . '"'.' ;';
		return $this;
	}

    /**
	 * andd prepare the where part of the SQl query
     * I've called it andd () because and is a reserved word
     * @param string $column name of the column
     * @param string $operator symbol to be able to compare ej (>, <, <=,> =, =, <>)
     * @param string $value value we seek
     * @return object $this returns the own instance to be able to chain methods
     */
    public function andd($column , $operator , $value)
    {
        $column=trim($column);
        $operator=trim($operator);
        $value=trim($value);
        $this->query =$this->no_semicolon($this->query);
        $this->query.= ' AND ' . $column . ' '. $operator .  '"'.$value . '"' ;
        return $this;
	}
	
    /**
	 * insert prepare the insert part of the SQl query
     * @param array $column array with the collection of columns that we want to insert
     * @param array $values array with the collection of values that we want to insert into those columns (IMPORTANT: we must be careful with the order, the values must be entered in the same order)
     * @param string $table table on which we want to insert the data
     * @return object $this returns the own instance to be able to chain methods
     */
    public function insert(Array $column , Array $values , $table)
    {
        $table=trim($table);
        $this->query = 'INSERT INTO '.' ' .$table .' ' ;
        if(count($column) != count($values)){
            echo "ERROR el Nº de argumentos de \$column y de \$values debe coincidir";
            exit;
        }else{
            $count= count($column);
            $st_colum='(';
            $st_values='(';
            for($i=0 ; $i <$count; $i++ ){
                $st_colum.= $column[$i];
                $st_values.= '"'.$values[$i].'"';
                if($i+1 < $count){
                    $st_colum .= ', ';
                    $st_values.= ', ';
                }
            }
            $st_colum .=') ';
            $st_values .=') ';
            $this->query .= $st_colum . ' VALUES ' . $st_values.' ;';
        }
        return $this;
	}
	
    /** update prepare the update part of the SQl query
	 * ATTENTION This method must be continued with a where, if not update the data of the entire column
     * @param string $column name of the column we want to update
     * @param string $operator the symbol to be able to compare ej (>, <, <=,> =, =, <>)
     * @param string $value the new value that we want to update
     * @param string $table table on which we want to update the data
     * @return object $this returns the own instance to be able to chain methods
     */
    public function update( $column , $operator , $value ,$table)
    {
        $table=trim($table);
        $column=trim($column);
        $operator=trim($operator);
        $value=trim($value);
        $this->query= "UPDATE ". $table .
            " SET ".$column . " ".$operator ." '" .$value."'";
        return $this;
	}
	
    /** delete prepare the update part of the SQl query
	 * ATTENTION This method must necessarily continue with a where, otherwise we will erase the data of the whole column
     * @param string $table table on which we want to delete
     * @return object $this returns the own instance to be able to chain methods
     */
    public function delete( $table)
    {
        $table=trim($table);
        $this->query= 'DELETE FROM '.$table ;
        return $this;
	}
	
    /**
	 * join prepare the join part of the SQl query
     * @param string $from first table to join
     * @param string $table2 second table to join 
     * @param string $on1 first part with which to match
     * @param string $on2 second part with which to match
     * @param string $type type of the JOIN (LEFT JOIN, RIGHT JOIN, INNER JOIN), default INNER JOIN
     * @return object $this returns the own instance to be able to chain methods
     */
    public function join($from,$table2, $on1,$on2,$type='INNER JOIN')
	{
		$from=trim($from);
		$table2=trim($table2);
		$on1=trim($on1);
		$on2=trim($on2);
		$type=trim($type);
		$this->query =$this->no_semicolon($this->query);
		$this->query .= ' ' . $type . ' '.$from.' ' . ' ON '. $on1 . ' = ' . $on2 . ';' ;
        return $this;
	}

    /**
	 * limit prepare the limit part of the SQl query
     * @param int $value this parameter can be a number or a string, it is the amount of values that we want to be returned
     * @return object $this returns the own instance to be able to chain methods
     */
    public function limit($value)
	{
		$value=trim($value);
		$this->query =$this->no_semicolon($this->query);
		$this->query .= ' LIMIT ' .$value.' ;'  ;
		return $this;
	}

    /** exe execute the query that we have been building with functions such as select(), insert(), update(), delete() andd(), join().
     * @return bool|PDO result |string with select() returns the selected values with the complete query
     */
    public function exe()
	{
        $query=$this->query;
        $query=strtolower($query);
        if( (strripos($query, 'update') !==false ) ||
            (strripos($query, 'delete') !==false )
            ){
            if( (strripos($query, 'where')  ) ){
                $conex=$this->conex;
                $resul=$conex->query($this->query);
            }else{
                /**
                 * crear una funcion para gestionar que no esté where
                 * cuando se usa update o delete
                 */
                $resul='ERROR';
                echo '<br> ERROR se ha de utilizar where cuando se utilice UPDATE o DELETE<br>';
            }
        }else{
            $conex=$this->conex;
            $resul=$conex->query($this->query);
        }

        return $resul;
	}
	
	/**
	 * no_semicolon remove semicolon from the string
	 * @param string $str 
	 * @return string string semicolon replaced
	 */
    static function no_semicolon($str)
	{
		return str_replace(';', '', $str);
	}

   /**
	* close close the DB connection
	*
	* @return void
	*/
    public function close()
	{
		$this->conex = null;
	}


}

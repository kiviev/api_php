<?php
namespace Api\App\Models;

use Api\App\Models\Model;
use Api\Database\Database;
use Api\Config\Env;
use JsonSerializable;

class User extends Model implements JsonSerializable
{
	protected static $table = 'persons';

	/**
	 * getDataDbFromId obtains the data of a user through a query to the DB
	 * @param int $id id of the user
	 * @return object result of the query
	 */
	public static function getDataDbFromId($id){
		$user = static::getDb()->select(['*'] ,static::$table)
		->where('id','=', $id)
		->get()
		->loadObject(true);
		return $user;
	}
	
	/**
	 * getObjectFromId gets an object of the User class. If you can not find it, return an empty object
	 * @param [type] $id id of the user
	 * @return object
	 */
	public static function getObjectFromId($id){
		$userDb = static::getDataDbFromId($id);
		$user = $userDb ? $user = new User(
			[
				'id' => $userDb->id,
				'name' => $userDb->name,
				'created_at' => $userDb->created_at,
				'updated_at' => $userDb->updated_at
			]
			) : static::getEmtpyObject();
		
		return $user;
	}

	/**
	 * getEmptyObject Create an empty object
	 * @return object
	 */
	public static function getEmtpyObject(){
		return new User(['status' => 400]);
	}

	/**
	 * searchPropery search for users and the terms that match the search term in the DB
	 * @param string $term term to search
	 * @return array array of users with prepared properties
	 */
	public static function searchProperty($term){
		$db = static::getDb();
		$term = $db->formatearParaDB($term);
		$query = "SELECT
			per.id AS iduser,
			per.name AS name,
			p.name AS property,
			pv.value AS value
		FROM properties_values AS pv
			INNER JOIN  persons_properties AS pp
				ON pv.id = pp.idvalue
			INNER join properties AS p
				ON p.id = pp.idproperty
			INNER JOIN persons AS per
				ON per.id = pp.iduser
		WHERE pv.value LIKE '%$term%';";

		$data = $db
		->query($query)
		->get()
		->loadResult();
		$data = static::prepareSearchPropertyData($data);
		return $data;
	}

	/**
	 * prepareSearchPropertyData prepare an array of user objects with their properties within an array
	 * @param array $data data result of the search in DB
	 * @return array prepared
	 */
	public static function prepareSearchPropertyData($data){
		if(is_array($data)){
			$users = array();
			foreach ($data as $key => $val) {
				if(!array_key_exists($val['iduser'] , $users)){
					$users[$val['iduser'] ] = array();
				} 
				if(!array_key_exists('properties' , $users[$val['iduser'] ])){
					$users[$val['iduser']]['properties'] = array();
				} 
				foreach ($val as $k => $v) {
					if(!($k === 'property' || $k === 'value')){
						$users[$val['iduser']][$k] = $v;
					}
					else{
						if(!count($users[$val['iduser']]['properties'])  || !array_key_exists($val['property'] , $users[$val['iduser']]['properties']) ){

							$users[$val['iduser']]['properties'][$val['property']] = $val['value'] ;

						}
					}
				}

			}
			return $users;
		}
	}

	/**
	 * jsonSerialize serialize the user object
	 * @return void
	 */
	public function jsonSerialize(){
        return array(get_object_vars($this));
    }


}

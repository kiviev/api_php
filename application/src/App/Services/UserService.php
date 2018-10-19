<?php
namespace Api\App\Services;

use Api\App\Models\User;

/**
 *
 */
class UserService
{

	/**
	 * searchProperty User search service and the terms that match the search term in the DB
	 * @param string $term term to search
	 * @return void
	 */
	public static function searchProperty($term){
		return User::searchProperty($term);
	}

	/**
	 * getObjectFromId service for the find of a user by its id
	 * @param int $id user id to find
	 * @return void
	 */
	public static function getObjectFromId($id){
		return User::getObjectFromId($id);
	}

}

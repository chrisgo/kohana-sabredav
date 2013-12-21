<?php

use Sabre\DAV;

/**
 * This is an authentication backend that uses a file to manage passwords.
 *
 * The backend file must conform to Apache's htdigest format
 *
 * @copyright Copyright (C) 2007-2013 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
class Kohana_Sabre_Auth_Backend extends DAV\Auth\Backend\AbstractBasic
{
	
	/**
	 * Role to check (optional)
	 */
	private $role;
	
	/**
	 * Constructor
	 */
	public function __construct($role)
	{
		$this->role = $role;
	}
	
	/**
	 * Validates a username and password
	 *
	 * This method should return true or false depending on if login
	 * succeeded.
	 *
	 * @return bool
	 */
	protected function validateUserPass($username, $password) 
	{
		if (Auth::instance()->logged_in($this->role))
		{
			return true;
		}
		else
		{
			if (Auth::instance()->login($username, $password))
			{
				if (isset($this->role) AND $this->role != NULL)
				{
					$user = Auth::instance()->get_user();
					$sql  = 'SELECT user_id ';
					$sql .= 'FROM roles_users, roles ';
					$sql .= 'WHERE user_id = :user_id AND ';
					$sql .= '      UPPER(roles.name) = :role AND ';
					$sql .= '      roles.id=roles_users.role_id';
					$results = DB::query(Database::SELECT, $sql)
							     ->parameters(array(
							         ':user_id' => $user->id,
							         ':role' => strtoupper($this->role),
							     ))
							     ->execute();
					if ($results->count() > 0) return true;
				}
				else
				{
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * Returns information about the currently logged in username.
	 *
	 * If nobody is currently logged in, this method should return null.
	 *
	 * @return string|null
	 */
	public function getCurrentUser() 
	{
		$user = Auth::instance()->get_user();
		if (! $user) 
		{
			return null;
		}
		else
		{
			return $user->username;
		}
	}
	
	/**
	 * Override function here. We want to cache authentication cookies
	 * in the syncing client to avoid HTTP-401 roundtrips.
	 * If the sync client supplies the cookies, then OC_User::isLoggedIn()
	 * will return true and we can see this WebDAV request as already authenticated,
	 * even if there are no HTTP Basic Auth headers.
	 * In other case, just fallback to the parent implementation.
	 *
	 * @return bool
	 */
	public function authenticate(Sabre\DAV\Server $server, $realm) 
	{
		if (Auth::instance()->logged_in($this->role))
		{
			$this->currentUser = Auth::instance()->get_user()->username;
			return true;
		}
		return parent::authenticate($server, $realm);
	}

}

<?php defined('SYSPATH') or die('No direct script access.');

use Sabre\DAV;
use Sabre\DAV\Auth;

class Kohana_Controller_Webdav extends Controller
{
	
	public $http_status;

	/**
	 * Main index() function copied from sabre example
	 *
	 * https://code.google.com/p/sabredav/wiki/GettingStarted#Tutorial_setup
	 *
	 * This will serve as the "server.php" file wrapped in a Kohana controller
	 */
	public function action_index()
	{
		// Grab the id from the request
		$id = $this->request->param('id');
		// Log the hit
		Log::instance()->add(Log::DEBUG, "Method:" . $this->request->method()." | "."Path: " . $id);
		
		// Load up some variables from the config file
		$base_uri = Kohana::$config->load('sabredav.webdav.base_uri');
		$root_dir = Kohana::$config->load('sabredav.webdav.root_directory');
		$lock_file = Kohana::$config->load('sabredav.webdav.lock_file');
		$auth_enable = Kohana::$config->load('sabredav.auth.enable');
		$auth_role = Kohana::$config->load('sabredav.auth.role');
		$auth_realm = Kohana::$config->load('sabredav.auth.realm');
		
		// Deal with the Auth plugin first 
		if (isset($auth_enable) AND $auth_enable)
		{
			$auth_backend = new Kohana_Sabre_Auth_Backend($auth_role);
			$auth_plugin = new Auth\Plugin($auth_backend, $auth_realm);
			// Adding the plugin to the server
			$root_directory = new Kohana_Sabre_DAV_FS_Directory($root_dir, $auth_plugin);
			// The server object is responsible for making sense out of the WebDAV protocol
			$server = new DAV\Server($root_directory);
			$server->addPlugin($auth_plugin);
		}
		else
		{
			$root_directory = new DAV\FS\Directory($root_dir);
			$server = new DAV\Server($root_directory);
		}

		// If your server is not on your webroot, make sure the following line has the correct information
		// $server->setBaseUri('/~evert/mydavfolder'); // if its in some kind of home directory
		// $server->setBaseUri('/dav/server.php/'); // if you can't use mod_rewrite, use server.php as a base uri
		// $server->setBaseUri('/'); // ideally, SabreDAV lives on a root directory with mod_rewrite sending every request to server.php
		$server->setBaseUri($base_uri);
		
		// Support for php://input missing plugin
		$kohana_plugin = new Kohana_Sabre_Plugin_Request_Put(Request::initial()->body());
		$server->addPlugin($kohana_plugin);
		
		// Support for html frontend
		$browser = new DAV\Browser\Plugin();
		$server->addPlugin($browser);
		
		// The lock manager is reponsible for making sure users don't 
		// overwrite each others changes. Change 'data' to a different
		// directory, if you're storing your data somewhere else.
		$lock_backend = new DAV\Locks\Backend\File($lock_file);
		$kohana_lock_plugin = new Kohana_Sabre_Plugin_Lock($lock_backend, Request::initial()->body());
		$server->addPlugin($kohana_lock_plugin);
		
		// All we need to do now, is to fire up the server
		$server->exec();
		$this->http_status = $server->httpResponse->code;
	}
	
	/**
	 * Mangle the response status based on the WWW-Authenticate header
	 */
	public function after()
	{
		// Call the parent first
		parent::after();
		// We want to set the correct response code
		$this->response->status($this->http_status);
		//$this->response->body($this->http_body);
	}

}
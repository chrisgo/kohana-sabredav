<?php defined('SYSPATH') or die('No direct script access.');

use Sabre\DAV;

class Kohana_Controller_Webdav extends Controller
{

	/**
	 * Main index() function copied from sabre example
	 *
	 * https://code.google.com/p/sabredav/wiki/GettingStarted#Tutorial_setup
	 *
	 * This will serve as the "server.php" file wrapped in a Kohana controller
	 */
	public function action_index()
	{
		// Load up some variables from the config file
		$base_uri = Kohana::$config->load('sabredav.webdav.base_uri');
		$root_dir = Kohana::$config->load('sabredav.webdav.root_directory');
		$lock_file = Kohana::$config->load('sabredav.webdav.lock_file');

		$root_directory = new DAV\FS\Directory($root_dir);
		
		// The server object is responsible for making sense out of the WebDAV protocol
		$server = new DAV\Server($root_directory);
		
		// If your server is not on your webroot, make sure the following line has the correct information
		// $server->setBaseUri('/~evert/mydavfolder'); // if its in some kind of home directory
		// $server->setBaseUri('/dav/server.php/'); // if you can't use mod_rewrite, use server.php as a base uri
		// $server->setBaseUri('/'); // ideally, SabreDAV lives on a root directory with mod_rewrite sending every request to server.php
		$server->setBaseUri($base_uri);
		
		// The lock manager is reponsible for making sure users don't overwrite each others changes. Change 'data' to a different
		// directory, if you're storing your data somewhere else.
		$lock_backend = new DAV\Locks\Backend\File($lock_file);
		$lock_plugin = new DAV\Locks\Plugin($lock_backend);
		
		$server->addPlugin($lock_plugin);
		
		// All we need to do now, is to fire up the server
		$server->exec();
	}

}
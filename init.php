<?php defined('SYSPATH') or die('No direct script access.');

// include vendor Sabre
require_once Kohana::find_file('vendor/SabreDAV-1.8.7/vendor','autoload','php');

// route for demo controller
Route::set('webdav', 'webdav(/<id>)',
	array(
		'id' => '[/.a-zA-Z0-9-_()@: ]+',
	))
	->defaults(array(
		'controller' => 'Webdav',
		'action'     => 'index',
));

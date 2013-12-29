<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * We are extending the Kohana response class so that we can 
 * pass all the HTTP status codes that webdav needs to implement.
 * 
 * This is just taking ALL the HTTP Status codes inside of 
 * Sabre\HTTP\Response class and adding them to the Kohana allowed 
 * status codes.  If we don't do this, Kohana will throw a 
 * 500 Exception (with HTTP Status 500) which breaks a lot of things
 * 
 * For easy reference, all additional status codes are commented
 * with // (Sabredav/Webdav) 
 * 
 */

class Response extends Kohana_Response 
{
	
	// HTTP status codes and messages
	public static $messages = array(
		// Informational 1xx
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing', // (Sabredav/Webdav)

		// Success 2xx
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
        207 => 'Multi-Status', 						// RFC 4918 (Sabredav/Webdav)
        208 => 'Already Reported', 					// RFC 5842 (Sabredav/Webdav)
        226 => 'IM Used', 							// RFC 3229 (Sabredav/Webdav)
					
		// Redirection 3xx
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found', // 1.1
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved', 							// (Sabredav/Webdav)
		307 => 'Temporary Redirect',

		// Client Error 4xx
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot', 					// RFC 2324 (Sabredav/Webdav)
		422 => 'Unprocessable Entity', 				// RFC 4918 (Sabredav/Webdav)
		423 => 'Locked', 							// RFC 4918 (Sabredav/Webdav)
		424 => 'Failed Dependency', 				// RFC 4918 (Sabredav/Webdav)
		426 => 'Upgrade required', 					// (Sabredav/Webdav)
		428 => 'Precondition required', 			// draft-nottingham-http-new-status (Sabredav/Webdav)
		429 => 'Too Many Requests', 				// draft-nottingham-http-new-status (Sabredav/Webdav)
		431 => 'Request Header Fields Too Large', 	// draft-nottingham-http-new-status (Sabredav/Webdav)

		// Server Error 5xx
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		507 => 'Insufficient Storage', 				// RFC 4918 (Sabredav/Webdav)
		508 => 'Loop Detected', 					// RFC 5842 (Sabredav/Webdav)
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not extended', 						// (Sabredav/Webdav)
        511 => 'Network Authentication Required', 	// draft-nottingham-http-new-status(Sabredav/Webdav)
		
	);
	
}

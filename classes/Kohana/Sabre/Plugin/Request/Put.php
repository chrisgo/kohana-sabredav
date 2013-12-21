<?php

use Sabre\DAV;

/**
 * Kohana PUT Request Plugin
 *
 * This plugin provides a way for the PUT webdav method to grab the request body
 * after Kohana eats it up on the initial request
 * 
 * Copied from Sabredav examples
 * 
 */

class Kohana_Sabre_Plugin_Request_Put extends DAV\ServerPlugin {

    /**
     * reference to server class
     *
     * @var Sabre\DAV\Server
     */
    protected $server;
    
    /**
     * reference to the body
     */
    protected $body;

    /**
     * Creates the object.
     *
     * TODO: Possibly grab the body using Kohana methods
     *       Request::initial()->body() 
     */
    public function __construct($body = null) 
    {
        $this->body = $body;
    }

    /**
     * Initializes the plugin and subscribes to events
     *
     * @param DAV\Server $server
     * @return void
     */
    public function initialize(DAV\Server $server) 
    {
        $this->server = $server;
        $this->server->subscribeEvent('beforeMethod',array($this,'httpPutInterceptor'));
    }

    /**
     * This method intercepts PUT requests to collections and returns the html
     *
     * @param string $method
     * @param string $uri
     * @return bool
     */
    public function httpPutInterceptor($method, $uri) {
		if ($method !== 'PUT') return true;
		$this->server->httpRequest->setBody($this->body);
        //return false;
    }

}

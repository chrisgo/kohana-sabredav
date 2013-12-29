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

class Kohana_Sabre_Plugin_Lock extends DAV\Locks\Plugin 
{

    /**
     * reference to the body
     */
    protected $body;

    /**
     * Creates the object.
     */
	public function __construct($locksBackend = null, $body) 
	{
        $this->locksBackend = $locksBackend;
        $this->body = $body;
    }
    
    /**
     * Locks an uri
     *
     * We just modified this from the original Sabredav locks plugin 
     * to get the body from the right place because Kohana eats is up (as usual)
     * 
     * @param string $uri
     * @return void
     */
    protected function httpLock($uri) {
    
    	$lastLock = null;
    	if (!$this->validateLock($uri,$lastLock)) {
    
    		// If the existing lock was an exclusive lock, we need to fail
    		if (!$lastLock || $lastLock->scope == LockInfo::EXCLUSIVE) {
    			//var_dump($lastLock);
    			throw new DAV\Exception\ConflictingLock($lastLock);
    		}
    
    	}
    
    	if ($body = $this->body) {
    		// This is a new lock request
    		$lockInfo = $this->parseLockRequest($body);
    		$lockInfo->depth = $this->server->getHTTPDepth();
    		$lockInfo->uri = $uri;
    		if($lastLock && $lockInfo->scope != LockInfo::SHARED) throw new DAV\Exception\ConflictingLock($lastLock);
    
    	} elseif ($lastLock) {
    
    		// This must have been a lock refresh
    		$lockInfo = $lastLock;
    
    		// The resource could have been locked through another uri.
    		if ($uri!=$lockInfo->uri) $uri = $lockInfo->uri;
    
    	} else {
    
    		// There was neither a lock refresh nor a new lock request
    		throw new DAV\Exception\BadRequest('An xml body is required for lock requests');
    
    	}
    
    	if ($timeout = $this->getTimeoutHeader()) $lockInfo->timeout = $timeout;
    
    	$newFile = false;
    
    	// If we got this far.. we should go check if this node actually exists. If this is not the case, we need to create it first
    	try {
    		$this->server->tree->getNodeForPath($uri);
    
    		// We need to call the beforeWriteContent event for RFC3744
    		// Edit: looks like this is not used, and causing problems now.
    		//
    		// See Issue 222
    		// $this->server->broadcastEvent('beforeWriteContent',array($uri));
    
    	} catch (DAV\Exception\NotFound $e) {
    
    		// It didn't, lets create it
    		$this->server->createFile($uri,fopen('php://memory','r'));
    		$newFile = true;
    
    		}
    
    		$this->lockNode($uri,$lockInfo);
    
    		$this->server->httpResponse->setHeader('Content-Type','application/xml; charset=utf-8');
    		$this->server->httpResponse->setHeader('Lock-Token','<opaquelocktoken:' . $lockInfo->token . '>');
    		$this->server->httpResponse->sendStatus($newFile?201:200);
    				$this->server->httpResponse->sendBody($this->generateLockResponse($lockInfo));
    
    }

}

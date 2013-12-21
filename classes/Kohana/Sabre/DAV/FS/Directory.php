<?php

use Sabre\DAV;

/**
 * Implementation of the directory so we can marshall users to their
 * own private directory and not share one giant public folder 
 * which is the default implementation of sabredav.
 * 
 * http://t699.php-sabredav.phptalks.us/filesystem-implementation-t699.html
 * 
 */

class Kohana_Sabre_DAV_FS_Directory extends DAV\FS\Directory 
{
	
	/**
	 * Member variable for the authPlugin that we need to 
	 * pull the username from to build the proper path for 
	 * each user
	 */
	private $auth_plugin;
	
	/**
     * Creates the object.
     */
    public function __construct($path, $auth_plugin) 
    {
    	parent::__construct($path);
        $this->auth_plugin = $auth_plugin;
    }
    
    /**
     * Creates a new file in the directory
     */
    public function createFile($name, $data = null) 
    {
    	$new_path = $this->path.'/'.$this->auth_plugin->getCurrentUser().'/'.$name;
    	file_put_contents($new_path, $data);
    }
    
    /**
     * Creates a new subdirectory
     */
    public function createDirectory($name) 
    {
    	$new_path = $this->path.'/'.$this->auth_plugin->getCurrentUser().'/'.$name;
    	mkdir($new_path);
    }
    
    /**
     * Returns a specific child node, referenced by its name
     */
    public function getChild($name) 
    {
    	$path = $this->path.'/'.$this->auth_plugin->getCurrentUser().'/'.$name;
    	if (!file_exists($path)) throw new DAV\Exception\NotFound('File with name ' . $path . ' could not be located');
    	if (is_dir($path)) 
    	{
    		return new Sabre\DAV\FS\Directory($path);
    	} 
    	else 
    	{
    		return new Sabre\DAV\FS\File($path);
    	}
    }
    
    /**
     * Returns an array with all the child nodes
     */
    public function getChildren() 
    {
        $nodes = array();
        $path = $this->path.'/'.$this->auth_plugin->getCurrentUser();
        foreach (scandir($path) as $node) 
        {
        	if ($node!='.' && $node!='..') 
        	{
        		$nodes[] = $this->getChild($node);
        	}
        }
        return $nodes;
    }
    
    /**
     * Checks if a child exists.
     */
    public function childExists($name) 
    {
    	$path = $this->path.'/'.$this->auth_plugin->getCurrentUser().'/'.$name;
    	return file_exists($path);
    }
    
    /**
     * Deletes all files in this directory, and then itself
     */
    public function delete() 
    {
    	foreach($this->getChildren() as $child) $child->delete();
    	rmdir($this->path.'/'.$this->auth_plugin->getCurrentUser());
    }
    
    /**
     * Returns available diskspace information
     */
    public function getQuotaInfo() 
    {
    	$path = $this->path.'/'.$this->auth_plugin->getCurrentUser();
    	return array(
    		disk_total_space($path)-disk_free_space($path),
    		disk_free_space($path)
    	);
    }

}

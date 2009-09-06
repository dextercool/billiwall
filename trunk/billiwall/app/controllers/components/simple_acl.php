<?php
/**
* SimpleAclComponent
*
* this is a simple authentication component
* the logic is based on the rdAuth by gwoo - thanks for your work!
*
* PHP versions 4 and 5
*
* Copyright (c) Marcin Domanski http://kabturek.info/
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright      Copyright (c) 2007, Marcin Domanski.
* @version        0.8.6117
* @link            http://wiki.kabturek.info/simpleaclcomponent
* @link            http://kabturek.info/
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
class SimpleAclComponent extends Object
{

	/**
	* name of the column with the group of the user
	*
	* @var string
	* @access public
	*/
	var $role = 'role';

	/**
	* Model that has the user data
	*
	* @var string
	* @access public
	*/
	var $userModel = 'User';

	/**
	* name of the column with the username
	*
	* @var string
	* @access public
	*/
	var $username = 'username';

	/**
	* true if the user is an admin
	*
	* @var boolean
	* @access public
	*/
	var $admin = false;

	/**
	* array of administrators
	*
	* @var array
	* @access public
	*/
	var $admins = array('Admin');

	/**
	* the access array
	*
	* @var array
	* @access public
	*/
	var $access = array();

	/**
	* function that sets the access array
	*
	* @param string $controllerName name of the controller
	*/
	function initialize($controller)
	{
		$vars = get_class_vars('AppController');
		if(!empty($vars['access']))
		{
			$this->access = $vars['access'];
		}

		if(!empty($controller->access))
		{
			$this->access = am($this->access, $controller->access);
		}
		return $this->access;

	}

	/**
	* Function to check the access for the action based on the access list
	*
	* @param array $aro the user
	* @param string $aco
	* @param string $acoAction
	* @return boolean
	*/
	function isAuthorized($aro, $controller, $action = null)
	{
		//if the user is an admin -> allow
		if( !empty($this->admins) &&
			 ((in_array($aro[$this->userModel][$this->role], $this->admins)
			 	|| in_array($aro[$this->userModel][$this->username], $this->admins)
				|| in_array($aro[$this->userModel]['id'], $this->admins))))
		{
			$this->admin = true;
			return true;
		}

		// if the key doesnt exist - allow access
		if (is_array($this->access) && array_key_exists($action, $this->access))
		{
			if(!empty($aro[$this->userModel][$this->role]))
			{
				if (in_array($aro[$this->userModel][$this->role], $this->access[$action]))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			//be sure to check if the user doesnt have the same username as the admin group
			elseif($aro[$this->userModel][$this->username])
			{
				if (in_array($aro[$this->userModel][$this->username], $this->access[$action]))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			elseif($aro[$this->userModel]['id'])
			{
				if (in_array($aro[$this->userModel]['id'], $this->access[$action]))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}

		// if the action is an admin action and the user isnt an admin and the permissions for the action werent
		// defined
		if(strpos($action, Configure::read('Routing.admin')) === 0)
		{
			return false;
		}

		return	true;
	}

}?>
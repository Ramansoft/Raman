<?php

namespace Raman\Controller;

use Raman\Security\Raman_Security_Encryption;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * Raman Contoller Action
 *
 * @author Mostafa Lavaei <lavaei@ramansoft.co>
 * @version 1.0  
 */
class Raman_Controller_Action extends \Zend_Controller_Action
{
	
	protected $lastPage;
	
	/**
	 * @var Array
	 */
	protected $authSes;
	
	/**
	 * Minimum Auth Level
	 * @var Integer
	 */
	protected $authLevel;
	
	/**
	 * Policy for Un-Auhtenticated users
	 * @var Integer
	 */
	protected $authPolicy;
	
	/**
	 * If you choose redirect-policy, user will redirect to this address
	 * @var string
	 */
	protected $loginPage;
	
	/**
	 * @var \Zend_Session_Namespace
	 */
	protected $flashMessages;
	
	/**
	 * @var \Zend_Session_Namespace
	 */
	protected $csrfSes;
	
	
	/**
	 * Page Layout
	 * @var string
	 */
	protected $layout;
	
	
	/**
	 * Raman_Translate instance
	 * @var \Raman\Translate\Raman_Translate_Adapter
	 */
	protected $translate;
	
	/**
	 * @see Zend_Controller_Action::init()
	 */
	public function init()
	{

		require_once 'Raman/Security/Encryption.php';
		
		/*
		 * Define Authentication's Levels
		 * 0 			=> Guests
		 * [1-20] 		=> Users
		 * [100-120] 	=> Admins
		 */
		define(AUTH_LEVEL_GUEST, 			0);
		define(AUTH_LEVEL_USER_UNVERIFIED,	1);
		define(AUTH_LEVEL_USER_VERIFIED,	2);
		define(AUTH_LEVEL_USER_Bronze,		3);
		define(AUTH_LEVEL_USER_SILVER,		4);
		define(AUTH_LEVEL_USER_GOLD,		5);
		define(AUTH_LEVEL_USER_SPECIAL,	   20);
		define(AUTH_LEVEL_ADMIN_SLAVE,	  100);
		define(AUTH_LEVEL_ADMIN_MASTER,	  101);
		
		/*
		 * Define Authentication's Policies
		 */
		define(AUTH_POLYCY_NOACTION, 		0);
		define(AUTH_POLYCY_NOTIFICATION, 	1);
		define(AUTH_POLYCY_REDIRECT, 		2);
		
		
		/*
		 * Define Request's parameters
		 */
		define(MODULE_NAME, $this->getRequest()->getModuleName());
		define(CONTROLLER_NAME, $this->getRequest()->getControllerName());
		define(ACTION_NAME, $this->getRequest()->getActionName());
		define(MODULE_PATH, APPLICATION_PATH . '/modules/' . MODULE_NAME);
		
		
		/*
		 * initialize
		 */				

		// Add Models to include path
		ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . MODULE_PATH . '/models/');
		
		// Add Forms to include path
		ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . MODULE_PATH  . '/forms/');
		
		ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . MODULE_PATH  . '/forms/' . CONTROLLER_NAME);
		
		// Add Module Helper to helper path
		$this->view->addHelperPath(MODULE_PATH . '/views/helpers/');
		$this->view->addHelperPath('Raman/View/Helper','Raman_View_Helper');				
		
		
		/*
		 * Set layout
		 */
		if(isset($this->layout))
			$this->_helper->layout()->setLayout($this->layout);
		else
			$this->_helper->layout()->setLayout(TEMPLATE_NAME . DIRECTORY_SEPARATOR . 'inner');

		
		/*
		 * Start sessions
		 */
		$this->authSes 			= new \Zend_Session_Namespace('authentication');
		$this->flashMessages 	= new \Zend_Session_Namespace('flashMessages');
		$this->csrfSes 			= new \Zend_Session_Namespace('csrf');
		
		
		/*
		 * Manage Flashe Messages
		 */
		if(!isset($this->flashMessages->queue) || !($this->flashMessages->queue instanceof \SplQueue))
			$this->flashMessages->queue 	= new \SplQueue();		
				
		
		
		/*
		 * Set Translator
		 */
		$this->translate  = \Zend_Registry::get('translate');
		
		
		/*
		 * Set view parameters
		 */
		$this->viewParams();	

		
		
		/*
		 * Set head links
		 */
		$this->setHeadLinks();

		
				
	}			
	
	public function authenticate()
	{		
		if($this->authLevel == AUTH_LEVEL_GUEST)
			return true;
		
		/*
		 * extract session
		 */
		$userId 		= $this->authSes->id;
		$username 		= $this->authSes->username;
		$authLevel 		= $this->authSes->authLevel;
		
		try 
		{
			if(!isset($this->authSes->id) 				||
					!isset($this->authSes->username) 	||
					!isset($this->authSes->authLevel)
			){
				throw new \Exception('Session is not set');
			}
			

			/*
			 * assert
			 */
			if( ($authLevel >= $this->authLevel))
			{
				$this->view->authLevel = $this->authSes->authLevel;
				return true; 
			}
			else 
			{
				throw new \Exception('Session is not valid');
			}
		}
		catch (\Exception $ex)
		{
			/*
			 * if authentication failed
			 */
			switch ($this->authPolicy)
			{
				case AUTH_POLYCY_NOACTION:
					break;
			
				case AUTH_POLYCY_NOTIFICATION:			
					$this->setFlashMessage(array(
						'title' 	=> 'Access Denied',
						'content' 	=> 'Please create a new account or login if you have.',
						'template' 	=> DIALOG_TEMPLATE_WARNING
					));			
					break;
			
				case AUTH_POLYCY_REDIRECT:		

					$this->authSes->lastPage = DEFAULT_PROTOCOL . '://' . $this->getRequest()->getHttpHost() . $this->getRequest()->getRequestUri();
					
					$this->setFlashMessage(array(
						'title' 	=> 'Access Denied',
						'content' 	=> 'Please create a new account or login if you have.',
						'template' 	=> DIALOG_TEMPLATE_WARNING
					));
			
					$this->_redirect($this->loginPage);
					break;
			}
		}		
				
	}
	
	public function getFlashMessage()
	{							
		if(isset($this->flashMessages->queue)) 
				while(!$this->flashMessages->queue->isEmpty())
					$return .= $this->view->dialog($this->flashMessages->queue->dequeue());
				
		return $return;
	}
	
	public function setFlashMessage($dialogConfigs)
	{
	    $dialogConfigs = str_replace(array(
	        "'",
	        '"'
	    ), array(
	        "\'",
	        '\"'
	    ), $dialogConfigs);
	    
		return $this->flashMessages->queue->enqueue($dialogConfigs);
	}
	
	protected function viewParams()
	{
		$this->view->hash             = $this->csrfSes->token;
		$this->view->flashMessages 	  = $this->getFlashMessage();
		$this->view->translate        = $this->translate;
	}
	
	protected function setHeadLinks()
	{
		$moduleName 	= $this->getRequest()->getModuleName();
		$controllerName = $this->getRequest()->getControllerName();
		$actionName 	= $this->getRequest()->getActionName();
		$styleSheet 	= "specCss/$moduleName/$controllerName/$actionName.css";
		$jsFile 		= "specJs/$moduleName/$controllerName/$actionName.js";
		
		if(file_exists("./$styleSheet"))
			$this->view->headLink()->appendStylesheet(ROOT_URL . $styleSheet);
		
		if(file_exists("./$jsFile"))
			$this->view->headScript()->appendFile(ROOT_URL . $jsFile);
	}
	
	/**
	 * Retrieve the Doctrine Container.
	 *
	 * @return Bisna\Doctrine\Container
	 */
	public function getDoctrineContainer()
	{
		return \Zend_Registry::get('doctrine');
	}
	
	/**
	 * Handle All Ajax Requests
	 */
	public function ajaxAction()
	{			
		
		$this->_helper->layout()->disableLayout();
	
		$request 		= $this->_getParam('request');
	
		$controllerName = ucfirst($this->getRequest()->getControllerName());
		$ajaxClassName 	= $controllerName . 'Ajax';
		
		require_once "$ajaxClassName.php";
				
		$ajaxObject 	= new $ajaxClassName();		
		
		echo $ajaxObject->$request($this->_getAllParams());
	}   

	/**
	 * Module Loader
	 */
	public function loaderAction()
	{
		/*
		 * [module_]controller_function
		 */
		$module 		= $this->_getParam('mod');
		$controller 	= $this->_getParam('com');//Component === Controller
		$function 		= $this->_getParam('fun');

		if($module && $controller && $function)
		{
			$class 		= ucfirst($module) . ucfirst($controller) . 'Services';
			$classPath 	= APPLICATION_PATH . "/modules/$module/models/$class.php";
		}
		elseif (!$module && $controller && $function)
		{
			$class 		= ucfirst($controller) . 'Services';
			$classPath 	= APPLICATION_PATH . "/models/$class.php";
		}
		else
		{
			throw new \Exception('Bad Request');
		}
			
		
		if(file_exists($classPath))
			include_once $classPath;
		else
			throw new \Exception('Resource not found');
		
		$object = new $class();
		
		echo $object->$function($this->_getAllParams());
		
	}	
}
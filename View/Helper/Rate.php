<?php
/**
 * Implement the jqxRating
 *
 * @author Mostafa Lavaei <lavaei@ramansoft.co>
 * @version 1.0
 */
class Raman_View_Helper_Rate
{	
	/**
	 * Html tag name. it will generate automatically
	 * @var string
	 */
	protected $TagName;
	    
	
	/**
	 * Scripts dependencies
	 * @var array
	 */
	protected $Dependencies;
	    
	
	/**
	 * Path to jqwidgets library
	 * @var string
	 */
	protected $JQwidgetsPath;
	
	
	/**
	 * The primary key in database
	 * @var integer
	 */
	protected $PrimaryKey;
	    
	
	/**
	 * Ajax url
	 * @var string
	 */
	protected $Url;
	
	
	/**
	 * A floating point number between 0 and 5
	 * @var float
	 */
	protected $Value;	    	
	
	
	/**
	 * The minimum authentication level required for rating
	 * @var string
	 */
	protected $MinimumAuthenticationLevel;
	    
	
	/**
	 * Path to the login page
	 * @var string
	 */
	protected $LoginPage;
	
	
	/**
	 * Path to the signup page
	 * @var string
	 */
	protected $SignupPage;
	    
	
	/**
	 * Get SignupPage's value
	 * @return SignupPage's value
	 * @see Raman_View_Helper_Rate::$SignupPage
	 */
	public function getSignupPage() 
	{
	  return $this->SignupPage;
	}
	
	/**
	 * Set SignupPage's value
	 * @param string $SignupPage
	 * @see Raman_View_Helper_Rate::$SignupPage
	 */
	public function setSignupPage($value) 
	{
	  $this->SignupPage = $value;
	}
	    
	
	/**
	 * Get LoginPage's value
	 * @return LoginPage's value
	 * @see Raman_View_Helper_Rate::$LoginPage
	 */
	public function getLoginPage() 
	{
	  return $this->LoginPage;
	}
	
	/**
	 * Set LoginPage's value
	 * @param string $LoginPage
	 * @see Raman_View_Helper_Rate::$LoginPage
	 */
	public function setLoginPage($value) 
	{
	  $this->LoginPage = $value;
	}
	
	/**
	 * Get MinimumAuthenticationLevel's value
	 * @return MinimumAuthenticationLevel's value
	 */
	public function getMinimumAuthenticationLevel() 
	{
	  return $this->MinimumAuthenticationLevel;
	}
	
	/**
	 * Set MinimumAuthenticationLevel's value
	 * @param string $MinimumAuthenticationLevel
	 */
	public function setMinimumAuthenticationLevel($value) 
	{
	  $this->MinimumAuthenticationLevel = $value;
	}
	
	
	/**
	 * Get Value's value
	 * @return Value's value
	 */
	public function getValue() 
	{
	  return $this->Value;
	}
	
	/**
	 * Set Value's value
	 * @param float $Value
	 */
	public function setValue($value) 
	{
	  $this->Value = $value;
	}
	    
	
	/**
	 * Get Url's value
	 * @return Url's value
	 */
	public function getUrl() 
	{
	  return $this->Url;
	}
	
	/**
	 * Set Url's value
	 * @param string $Url
	 */
	public function setUrl($value) 
	{
	  $this->Url = $value;
	}
	
	
	/**
	 * Get PrimaryKey's value
	 * @return PrimaryKey's value
	 */
	public function getPrimaryKey() 
	{
	  return $this->PrimaryKey;
	}
	
	/**
	 * Set PrimaryKey's value
	 * @param integer $PrimaryKey
	 */
	public function setPrimaryKey($value) 
	{
	  $this->PrimaryKey = $value;
	}
	    
	
	/**
	 * Get JQwidgetsPath's value
	 * @return JQwidgetsPath's value
	 */
	public function getJQwidgetsPath() 
	{
	  return $this->JQwidgetsPath;
	}
	
	/**
	 * Set JQwidgetsPath's value
	 * @param string $JQwidgetsPath
	 */
	public function setJQwidgetsPath($value) 
	{
	  $this->JQwidgetsPath = $value;
	}
	
	/**
	 * Get Dependencies's value
	 * @return Dependencies's value
	 */
	public function getDependencies() 
	{
	  return $this->Dependencies;
	}
	
	/**
	 * Set Dependencies's value
	 * @param string $Dependencies
	 */
	public function setDependencies($value) 
	{
	  $this->Dependencies = $value;
	}
	
	
	/**
	 * Get TagName's value
	 * @return TagName's value
	 */
	public function getTagName() 
	{
	  return $this->TagName;
	}
	
	/**
	 * Set TagName's value
	 * @param string $TagName
	 */
	public function setTagName($value) 
	{
	  $this->TagName = $value;
	}
	

	

	public function __construct(array $initData=array())
	{
		foreach ($initData as $attr=>$value)
			$this->$attr 	= $value;
		
		if(!isset($this->MinimumAuthenticationLevel))
			$this->setMinimumAuthenticationLevel(AUTH_LEVEL_USER_VERIFIED);
		
		if(!isset($this->Dependencies))
		{
			$this->setDependencies(array(
					'jqx.base.css',
					'jqx.arctic.css',
					'jqxcore.js',
					'jqxrating.js'					
			));
		}
		
		if(!isset($this->TagName))
			$this->setTagName('Raman_Rate_' . date("isu", time()));
		
		
		if(!isset($this->LoginPage))
			$this->setLoginPage(ROOT_URL . 'users/index/login');
		
		if(!isset($this->SignupPage))
			$this->setSignupPage(ROOT_URL . 'users/index/signup');
		
		if(!isset($this->JQwidgetsPath))
			$this->setJQwidgetsPath(ROOT_URL . '/libPlugins/jqwidgets');
	}	
	
	
	/**
	 * @param array $data        	
	 */
	public function render()
	{	

		$tagName 	= $this->getTagName();

		// load scripts
		$return .= "<script>" . $this->getScripts() . "</script>";
		

		// create html
		$return .= "<div id=\"$tagName\"></div>";
		
		return $return;
	}
	
	public function getScripts()
	{
		$authSes 	= new Zend_Session_Namespace('authentication');
		$dialog 	= new Raman_View_Helper_Dialog();
		
		$primary 	= $this->getPrimaryKey();
		$url 		= $this->getUrl();
		$value 		= $this->getValue();
		$minAuthLvl = $this->getMinimumAuthenticationLevel();
		$tagName 	= $this->getTagName();
		$loginPage 	= $this->getLoginPage();
		$signupPage = $this->getSignupPage();
		$userId 	= (int) $authSes->userId;
		$authLevel 	= (int) $authSes->authLevel;
		
		$dialog->setTemplate(Raman_View_Helper_Dialog::DIALOG_TEMPLATE_DANGER);
		$dialog->setTitle('Login Required');
		$dialog->setContent("You don't have permission to rate. Please <a href='$signupPage'>create an account</a> or <a href='$loginPage'>login</a> if you have ");
		$showLoginRequiredError = $dialog->getScripts();
		
		// load scripts
   		$return = "$(document).ready(function(){ " . $this->generateDependencies("var userId = $userId;$('#$tagName').jqxRating({ value:$value, theme: 'classic', height:24});$('#$tagName').on('change', function (event) {if($authLevel < $minAuthLvl){ $showLoginRequiredError }else{ $.post('$url',{request:'rate', value:event.value, primary:'$primary'},function(data){});}}); $('#$tagName').css({'display':'inline-block'}); ") . "});";


		return $return;
	}
	
	public function generateDependencies($callback)
	{
		
		$JQwidgetsPath 	= $this->getJQwidgetsPath();
				
		
		$scriptsPath 	= array();
		$stylesPath 	= array();


		$extPattern 	= "@^.*\.([^.]*)$@";
		
		foreach ($this->getDependencies() as $depend)
		{

			preg_match($extPattern, $depend, $matches);
			$extention 	= $matches[1];
			
			switch($extention)
			{
				case 'js':
					$scriptsPath[] 	= "$JQwidgetsPath/$depend";					
					break;
					
				case 'css':
					$stylesPath[] 	= "$JQwidgetsPath\/styles\/$depend";					
					break;						
			}						
			
		}
		
		$scripts 	= $this->loadJavascripts($scriptsPath, $callback);
		$styles 	= $this->loadStyles($stylesPath);
		
		Zend_Registry::set('loadedScripts', $loadedScripts);
		
		return "$styles  $scripts" ;
	}
	
	protected function loadJavascripts(array $paths, $callback)
	{
		if(sizeof($paths) == 0)
			return;
		
		$currentPath = array_pop($paths);
		
		if(sizeof($paths) > 0)
			$nextScript 	= $this->loadJavascripts($paths, $callback);
		else 
			$nextScript 	= $callback;
		
		return "$.getScript('$currentPath', function(data, textStatus, jqxhr){ $nextScript }); ";

	}
	
	protected function loadStyles(array $paths)
	{
		if(sizeof($paths) == 0)
			return;
		
		$currentPath = array_pop($paths);
		
		if(sizeof($paths) > 0)
			$callback 	= $this->loadStyles($paths);
		
		return "$('<link>').appendTo('head').attr({type : 'text\/css', rel : 'stylesheet'}).attr('href', '$currentPath'); $callback";
	}
}
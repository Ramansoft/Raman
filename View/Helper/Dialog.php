<?php

/**
 * Implement the Bootstrap-Dialog as helper
 *
 * @author Mostafa Lavaei <lavaei@ramansoft.co>
 * @version 1.0
 */
class Raman_View_Helper_Dialog
{
	/**
	 * Set Theme & Buttons for normal dialogs
	 */
	const DIALOG_TEMPLATE_SIMPLE = 	0;
	
	/**
	 * Set Theme & Buttons for error messages
	*/
	const DIALOG_TEMPLATE_DANGER = 	1;	
	
	/**
	 * Set Theme & Buttons for warning messages
	*/
	const DIALOG_TEMPLATE_WARNING = 	2;
	
	/**
	 * Set Theme & Buttons for help messages
	*/
	const DIALOG_TEMPLATE_INFO = 		3;
	
	/**
	 * Set Theme & Buttons for prompt dialogs
	*/
	const DIALOG_TEMPLATE_QUESTION = 	4;
	
	/**
	 * Set Theme & Buttons for success messages
	*/
	const DIALOG_TEMPLATE_SUCCESS = 	5;
	
	/**
	 * Set Theme for normal dialogs
	*/
	const DIALOG_THEME_SIMPLE = 	"BootstrapDialog.TYPE_PRIMARY";
	
	/**
	 * Set Theme for error messages
	*/
	const DIALOG_THEME_DANGER = 	"BootstrapDialog.TYPE_DANGER";
	
	/**
	 * Set Theme for warining messages
	*/
	const DIALOG_THEME_WARNING = 	"BootstrapDialog.TYPE_WARNING";
	
	/**
	 * Set Theme for help messages
	*/
	const DIALOG_THEME_INFO = 	"BootstrapDialog.TYPE_INFO";
	
	/**
	 * Set Theme for prompt dialogs
	*/
	const DIALOG_THEME_QUESTION = "BootstrapDialog.TYPE_INFO";
	
	/**
	 * Set Theme for success messages
	*/
	const DIALOG_THEME_SUCCESS = 	"BootstrapDialog.TYPE_SUCCESS";
	
	/**
	 * Button OK with btn-primary class and close() callback
	*/
	const DIALOG_BUTTON_OK = 0;
	
	/**
	 * Button Close with btn-warning class and close() callback
	*/
	const DIALOG_BUTTON_CLOSE = 1;
	
	/**
	 * Button Continue with btn-success class and close() callback
	*/
	const DIALOG_BUTTON_CONTINUE = 2;
	
	/**
	 * Button Cancle with btn-warning class and close() callback
	*/
	const DIALOG_BUTTON_CANCEL = 3;
	
	/**
	 * Button Yes with btn-primary class and close() callback
	 */
	const DIALOG_BUTTON_YES = 4;
	
	/**
	 * Button No with btn-danger class and close() callback
	 */
	const DIALOG_BUTTON_NO = 5;
	
	/**
	 * Button OK with btn-success class and close() callback
	 */
	const DIALOG_BUTTON_SUCCESS_OK = 6;
	

	/**
	 * @var string
	 */
	protected $Template;
	
	
	/**
	 * @var string
	 */
	protected $Buttons = array();
	    
	
	/**
	 * @var string
	 */
	protected $Class;
	    
		
	/**
	 * @var string
	 */
	protected $Title;
	
	
	/**
	 * @var string
	 */
	protected $Content;
	    
	
	/**
	 * Get Content's value
	 * @return Content's value
	 * @see Raman_View_Helper_Dialog::$Content
	 */
	public function getContent() 
	{
	  return $this->Content;
	}
	
	/**
	 * Set Content's value
	 * @param string $Content
	 * @see Raman_View_Helper_Dialog::$Content
	 */
	public function setContent($value) 
	{
	   $value = str_replace(array(
	       "'"	       
	   ), array(
	       '"'
	   ), $value);
	    
	   $this->Content = $value;
	}
	    
	
	/**
	 * Get Title's value
	 * @return Title's value
	 * @see Raman_View_Helper_Dialog::$Title
	 */
	public function getTitle() 
	{
	  return $this->Title;
	}
	
	/**
	 * Set Title's value
	 * @param string $Title
	 * @see Raman_View_Helper_Dialog::$Title
	 */
	public function setTitle($value) 
	{
	  $this->Title = $value;
	}
	
	
	/**
	 * Get Class's value
	 * @return Class's value
	 * @see Raman_View_Helper_Dialog::$Class
	 */
	public function getClass() 
	{
	  return $this->Class;
	}
	
	/**
	 * Set Class's value
	 * @param string $Class
	 * @see Raman_View_Helper_Dialog::$Class
	 */
	public function setClass($value) 
	{
	  $this->Class = $value;
	}
	
	/**
	 * Get Buttons's value
	 * @return Buttons's value
	 * @see Raman_View_Helper_Dialog::$Buttons
	 */
	public function getButtons() 
	{
	  return $this->Buttons;
	}
	
	/**
	 * Set Buttons's value
	 * @param string $Buttons
	 * @see Raman_View_Helper_Dialog::$Buttons
	 */
	public function setButtons($value) 
	{
	  $this->Buttons = $value;
	}
	    
	
	/**
	 * Get Template's value
	 * @return Template's value
	 * @see Raman_View_Helper_Dialog::$Template
	 */
	public function getTemplate() 
	{
	  return $this->Template;
	}
	
	/**
	 * Set Template's value
	 * @param string $Template
	 * @see Raman_View_Helper_Dialog::$Template
	 */
	public function setTemplate($value) 
	{
	  $this->Template = $value;
	}
	
	
	public function __construct(array $configs = array())
	{
		$this->configure($configs);
	}
	
	public function dialog(array $configs = array())
	{
		$this->configure($configs);

		$buttons 	= $this->generateButtons($this->getButtons());
		
		$return 	= "<script>$(document).ready(function(){" . $this->getScripts() . "});</script>";
		
		return $return;
	}
	
	public function getScripts()
	{

		$this->createTemplate();
		
		$title 		= $this->getTitle();
		$class 		= $this->getClass();
		$content 	= $this->getContent();
		$buttons 	= $this->generateButtons($this->getButtons());
		
		return "
			BootstrapDialog.show({
				title: 		'$title',
				type: 		$class,
		        message: 	'$content',        	        
		        buttons: 	$buttons,
		        draggable: 	true,
		    });";
	}
	
	protected function configure(array $configs = array())
	{
		
		if(array_key_exists('content', $configs))
			$this->setContent($configs['content']);
	
		if(array_key_exists('title', $configs))
			$this->setTitle($configs['title']);
	
		if(array_key_exists('class', $configs))
			$this->setClass($configs['class']);
	
		if(array_key_exists('buttons', $configs))
			$this->setButtons($configs['buttons']);
	
		if(array_key_exists('template', $configs))
			$this->setTemplate($configs['template']);
	}
	
	/**
	 * Auto configure dialog by choosen template. The templates are consts with DIALOG_TEMPLATE prefix
	 */
	protected function createTemplate()
	{
		if(!isset($this->Buttons))
			$this->setButtons(array());
		
		switch ($this->getTemplate())
		{
			case self::DIALOG_TEMPLATE_SIMPLE:
				
				/*
				 * Create Buttons
				 */									
				array_push($this->Buttons, $this->getButton(self::DIALOG_BUTTON_OK));
				
				/*
				 * Set Theme
				 */
				$this->setClass(self::DIALOG_THEME_SIMPLE);								
					
				
				break;
				
			case self::DIALOG_TEMPLATE_DANGER:
				
				/*
				 * Create Buttons
				 */
				array_push($this->Buttons, $this->getButton(self::DIALOG_BUTTON_CLOSE));
				
				/*
				 * Set Theme
				 */
				$this->setClass(self::DIALOG_THEME_DANGER);
					
				break;
				
			case self::DIALOG_TEMPLATE_WARNING:
				
				/*
				 * Create Buttons
				 */									
				array_push($this->Buttons, $this->getButton(self::DIALOG_BUTTON_CLOSE));
				
				/*
				 * Set Theme
				 */
				$this->setClass(self::DIALOG_THEME_WARNING);
					
				break;
				
			case self::DIALOG_TEMPLATE_INFO:
				
				/*
				 * Create Buttons
				 */									
				array_push($this->Buttons, $this->getButton(self::DIALOG_BUTTON_OK));
				
				/*
				 * Set Theme
				 */
				$this->setClass(self::DIALOG_THEME_INFO);
					
				break;
				
			case self::DIALOG_TEMPLATE_QUESTION:
				
				/*
				 * Create Buttons
				 */
				array_push($this->Buttons, $this->getButton(self::DIALOG_BUTTON_CANCEL));
				array_push($this->Buttons, $this->getButton(self::DIALOG_BUTTON_NO));
				array_push($this->Buttons, $this->getButton(self::DIALOG_BUTTON_YES));				
				
				
				/*
				 * Set Theme
				 */
				$this->setClass(self::DIALOG_THEME_INFO);
					
				break;
				
			case self::DIALOG_TEMPLATE_SUCCESS:
				
				/*
				 * Create Buttons
				 */									
				array_push($this->Buttons, $this->getButton(self::DIALOG_BUTTON_SUCCESS_OK));
				
				/*
				 * Set Theme
				 */
				$this->setClass(self::DIALOG_THEME_SUCCESS);
					
				break;
		}
	}	
	
	
	/**
	 * Get button by const with DIALOG_BUTTON prefix
	 * @param integer $button
	 * @return array
	 */
	public function getButton($button)
	{
		switch ($button)
		{
			case self::DIALOG_BUTTON_OK:
				return array(
						'icon' 		=> "",
						'label' 	=> "Ok",
						'cssClass' 	=> "btn-primary",
						'action' 	=> "function(dialogItself){dialogItself.close();}"
				);
				break;
				
			case self::DIALOG_BUTTON_CLOSE:
				return array(
						'icon' 		=> "",
						'label' 	=> "Close",
						'cssClass' 	=> "btn-warning",
						'action' 	=> "function(dialogItself){dialogItself.close();}"
				);
				break;
				
			case self::DIALOG_BUTTON_CONTINUE:
				return array(
						'icon' 		=> "",
						'label' 	=> "Continue",
						'cssClass' 	=> "btn-success",
						'action' 	=> "function(dialogItself){dialogItself.close();}"
				);
				break;
				
			case self::DIALOG_BUTTON_CANCEL:
				return array(
						'icon' 		=> "",
						'label' 	=> "Cancle",
						'cssClass' 	=> "btn-warning",
						'action' 	=> "function(dialogItself){dialogItself.close();}"	
				);
				break;
				
			case self::DIALOG_BUTTON_YES:
				return array(
						'icon' 		=> "",
						'label' 	=> "Yes",
						'cssClass' 	=> "btn-primary",
						'action' 	=> "function(dialogItself){dialogItself.close();}"	
				);
				break;
				
			case self::DIALOG_BUTTON_NO:
				return array(
						'icon' 		=> "",
						'label' 	=> "No",
						'cssClass' 	=> "btn-danger",
						'action' 	=> "function(dialogItself){dialogItself.close();}"
				);
				break;		

			case self::DIALOG_BUTTON_SUCCESS_OK:
				return array(
						'icon' 		=> "",
						'label' 	=> "Ok",
						'cssClass' 	=> "btn-success",
						'action' 	=> "function(dialogItself){dialogItself.close();}"
				);
				break;
		}
	}
	
	
	/**
	 * Render buttons
	 * @param array $buttons
	 * @return string
	 */
	protected function generateButtons(array $buttons = array())
	{
	
		foreach ($buttons as $button)
		{
			$return .= '{';
				
			if(isset($button['icon']))
				$return .= "icon:'$button[icon]',";
				
			if(isset($button['label']))
				$return .= "label:'$button[label]',";
				
			if(isset($button['cssClass']))
				$return .= "cssClass:'$button[cssClass]',";
				
			if(isset($button['action']))
				$return .= "action:$button[action]";
				
			$return .= '},';
		}
	
		$return = trim($return, ',');
		$return = "[$return]";
	
		return $return;
	}
}
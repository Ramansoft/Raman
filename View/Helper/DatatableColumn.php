<?php
/**
 * Use in implementing the jqxDatatable
 *
 * @author Mostafa Lavaei <lavaei@ramansoft.co>
 * @version 1.1
 * @see Raman_View_Helper_Datatable
 */
class Raman_View_Helper_DatatableColumn
{
	
	/**
	 * Column's name in data source
	 * @var string
	 */
	protected $dataField;
	
	
	/**
	 * Column's label in grid
	 * @var string
	 */
	protected $text;
	
	
	/**
	 * Column's width in px or %
	 * @var string
	 */
	protected $width;
	
	
	/**
	 * Column's height in px or %
	 * @var string
	 */
	protected $height;
	
	
	/**
	 * Column's text align (head row)
	 * @var string
	 */
	protected $align = 'center';
	
	
	/**
	 * Cell's text align (data rows)
	 * @var string
	 */
	protected $cellsalign = 'center';
	
	
	/**
	 * Allow editing. By default extends from grid's editable attribute
	 * @var boolean
	 */
	protected $editable;
	
	
	/**
	 * The column's value type
	 * @var string
	 */
	protected $type;
	
	
	/**
	 * Default value
	 */
	protected $default;
	
	
	/**
	 * Cells values format
	 * @var string
	 */
	protected $cellsFormat;
	
	
	/**
	 * Determinate how to show cells
	 * @var string
	 */
	protected $columnType;
	
	
	/**
	 * 
	 */
	protected $editOptions;
	
	
	/**
	 *  Some columns types needs additional attributes
	 * @var string
	 */
	protected $cellsRenderer;
	
	
	/**
	 * javascript onClick event
	 * @var javascript code
	 */
	protected $buttonClick;
	
	
	/**
	 * 
	 */
	protected $createEditor;
	
	
	/**
	 * If set true, the column will not shown
	 * @var boolean
	 */
	protected $hidden;
	
	
	
	public function __construct(array $initData=array())
	{
		foreach ($initData as $attr=>$value)
			$this->$attr 	= $value;						
	}

	public function getColumn()
	{
		$column 		= array();
		$properties 	= get_object_vars($this);
		
		foreach ($properties as $attr=>$value)
		{						
			if(isset($this->$attr))
				$column[$attr] = $this->$attr;	
		}
		
		return $column;
	}
	
	/**
	 * 
	 * @param string $dataField
	 */
	public function setDatafield($dataField)
	{
		$this->dataField = $dataField;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $text
	 */
	public function setText($text)
	{
		$this->text = $text;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $width
	 */
	public function setWidth($width)
	{
		$this->width = $width;
		
		return $this;
	}
	
	
	/**
	 *
	 * @param string $height
	 */
	public function setHeight($height)
	{
		$this->height = $height;
	
		return $this;
	}
	
	
	
	/**
	 * 
	 * @param string $align
	 */
	public function setAlign($align)
	{
		$this->align = $align;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $cellsAlign
	 */
	public function setCellsAlign($cellsAlign)
	{
		$this->cellsalign = $cellsAlign;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param boolean $editable
	 */
	public function setEditable($editable)
	{
		$this->editable = $editable;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $default
	 */
	public function setDefault($default)
	{
		$this->default = $default;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $cellsFormat
	 */
	public function setCellsFormat($cellsFormat)
	{
		$this->cellsFormat = $cellsFormat;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $columnType
	 */
	public function setColumnType($columnType)
	{
		$this->columnType = $columnType;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $editOptions
	 */
	public function setEditOptions($editOptions)
	{
		$this->editOptions = $editOptions;
		
		return $this;
	}
	
	
	/**
	 * You can set template for columns with this function. By default there is no template
	 * has been set, it means the output is like simple table
	 * @param string $cellsRenderer is the content of cells renderer function 
	 * with <i>function (row, column, value, rowData)</i> defination where<br/>
	 * row is row's index,<br/>
	 * column is column's datafield,<br/> 
	 * value is cell's value<br/>
	 * and rowData is row object.<br/>
	 * The return value is the output of each column
	 */
	public function setCellsRenderer($cellsRenderer)
	{
		$this->cellsRenderer = "QOUTE_REMOVERfunction (row, column, value, rowData){ $cellsRenderer }QOUTE_REMOVER";
		
		return $this;
	}	
	
	
	/**
	 * 
	 * @param string $buttonClick
	 */
	public function setButtonClick($buttonClick)
	{
		$this->buttonClick = $buttonClick;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $createEditor
	 */
	public function setCreateEditor($createEditor)
	{
		$this->createEditor = $createEditor;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param boolean $hidden
	 */
	public function setHidden($hidden)
	{
		$this->hidden = $hidden;
		
		return $this;
	}
	
	
	/**
	 * Set cells renderer to render cells as image
	 * @param string $src
	 * @param string $alt
	 * @see Raman_View_Helper_DatatableColumn::setCellsRenderer($cellsRenderer)
	 */
	public function setCellsRendererImage($src, $alt='')
	{
		$this->setCellsRenderer("return '<center><img style=\"width:100%\" alt=\"$alt\" src=\"$src\"/></center>';");
		
		return $this;
	}
	
	public function setCellsRendererStar($value)
	{
		$Rate = new Raman_View_Helper_Rate();
		$Rate->setValue($value);

		
		$tagName 	= $Rate->getTagName();			
		$scripts 	= str_replace(array(
				"\n",
				"\r",
				"\t"
		), '', $Rate->getScripts());
		
		 	
		$this->setCellsRenderer("$scripts; return '<center style=\"position:relative !important\"><div id=\"$tagName\"></div></center><style>#$tagName{position:absolute; top:50%; left:50%; margin:-10px -50px}</style>';");
		
		return $this;
	}
}
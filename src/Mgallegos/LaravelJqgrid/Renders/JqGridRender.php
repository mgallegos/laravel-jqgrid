<?php 
/**
 * @file
 * JqGrid Render.
 *
 * All LaravelJqGrid code is copyright by the original authors and released under the MIT License.
 * See LICENSE.
 */

namespace Mgallegos\LaravelJqgrid\Renders;

class JqGridRender implements RenderInterface {
	
	/**
	 * Grid ID
	 *
	 * @var string
	 *
	 */
	protected $gridId;
	
	/**
	 * Filter toolbar
	 *
	 * @var boolean
	 *
	 */
	protected $filterToolbar;		
		
	
	/**
	 * Toggle button
	 *
	 * @var boolean
	 *
	 */
	protected $toggleButton;
	
	/**
	 * Clear button
	 *
	 * @var boolean
	 *
	 */
	protected $clearButton;
	
	/**
	 * Toggle button Text
	 *
	 * @var boolean
	 *
	 */
	protected $toggleButtonText;
	
	/**
	 * Clear button Text
	 *
	 * @var boolean
	 *
	 */
	protected $clearButtonText;
	
	/**
	 * PropertyValidatorInterface array
	 *
	 * @var array
	 * 	
	 */
	protected $colModelValidators;
	
	/**
	 * PropertyValidatorInterface array
	 *
	 * @var array
	 *
	 */
	protected $optionValidators;
	
	/**
	 * PropertyValidatorInterface array
	 *
	 * @var array
	 *
	 */
	protected $navigatorValidators;
	
	/**
	 * PropertyValidatorInterface array
	 *
	 * @var array
	 *
	 */
	protected $filterToolbarValidators;
	

	/**
	 * Columns model array
	 *
	 * @var array
	 */
	protected $colModel;
	
	/**
	 * Options array
	 *
	 * @var array
	 */
	protected $options;
	
	/**
	 * Navigator options array
	 *
	 * @var array
	 */
	protected $navigatorOptions;
	
	/**
	 * Navigator edit options array
	 *
	 * @var array
	 */
	protected $navigatorEditOptions;
	
	/**
	 * Navigator add options array
	 *
	 * @var array
	 */
	protected $navigatorAddOptions;
	
	/**
	 * Navigator delete options array
	 *
	 * @var array
	 */
	protected $navigatorDeleteOptions;
	
	/**
	 * Navigator search options array
	 *
	 * @var array
	 */
	protected $navigatorSearchOptions;
	
	/**
	 * Navigator view options array
	 *
	 * @var array
	 */
	protected $navigatorViewOptions;
	
	/**
	 * Filter toolbar options array
	 *
	 * @var array
	 */
	protected $filterToolbarOptions;
	
	
	/**
	 * Default column model properties array
	 *
	 * @var array
	 */
	protected $defaultColModelProperties;
	
	/**
	 * Default grid options array
	 *
	 * @var array
	 */
	protected $defaultGridOptions;
	
	/**
	 * Default navigator options
	 *
	 * @var array
	 */
	protected $defaultNavigatorOptions;
	
	/**
	 * Default filter toolbar options
	 *
	 * @var array
	 */
	protected $defaultfilterToolbarOptions;
	
	/**
	 * Array of JqGrid function type properties
	 *
	 * @var array
	 */
	protected $functionTypeProperties;
	
	
	/**
	 * Create a new JqGridRender instance.
	 *
	 * @param  
	 */
	public function __construct(array $optionValidators = array(), array $colModelValidators = array(), array $navigatorValidators = array(), array $filterToolbarValidators = array(), array $defaultGridOptions = array(), array $defaultColModelProperties = array(), array $defaultNavigatorOptions = array(), array $defaultfilterToolbarOptions = array(), array $functionTypeProperties = array(), array $defaultFilterToolbarButtonsOptions = array()) 
	{	
		$this->gridId = str_random(10);
		
		$this->colModelValidators = $colModelValidators;
		
		$this->optionValidators = $optionValidators;

		$this->navigatorValidators = $navigatorValidators;
		
		$this->filterToolbarValidators = $filterToolbarValidators;

		$this->colModel = array();
		
		$this->options = $defaultGridOptions;
		
		$this->navigatorOptions = $defaultNavigatorOptions;
		
		$this->navigatorEditOptions = array();
		
		$this->navigatorAddOptions = array();
		
		$this->navigatorDeleteOptions = array();
		
		$this->navigatorSearchOptions = array();
		
		$this->navigatorViewOptions = array();
		
		$this->filterToolbarOptions = $defaultfilterToolbarOptions;
		
		$this->defaultColModelProperties = $defaultColModelProperties;
		
		$this->defaultGridOptions = $defaultGridOptions;
				
		$this->defaultNavigatorOptions = $defaultNavigatorOptions;
		
		$this->defaultfilterToolbarOptions = $defaultfilterToolbarOptions;
		
		$this->functionTypeProperties = $functionTypeProperties;
		
		$this->filterToolbar = $defaultFilterToolbarButtonsOptions['filterToolbar'];
		
		$this->toggleButton = $defaultFilterToolbarButtonsOptions['toggleButton'];
		
		$this->clearButton = $defaultFilterToolbarButtonsOptions['clearButton'];
		
		$this->toggleButtonText = $defaultFilterToolbarButtonsOptions['toggleButtonText'];
		
		$this->clearButtonText = $defaultFilterToolbarButtonsOptions['clearButtonText'];
	}
	
	/**
	 * Add a column at the last position in the columns model.
	 *
	 * @param  string $id
	 * 	
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setGridId($id=null)
	{	
		$this->gridId = $id;
		
		return $this;
	}
	
	/**
	 * Add a column at the last position in the columns model.
	 *
	 * @param  array $properties
	 * 	An array of valid jqGrid column model property, the key of the array must correspond to a column model property.   
	 * 	Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:colmodel_options
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function addColumn(array $properties = array())
	{
		foreach ($this->colModelValidators as $validator)
		{
			$validator->validate($properties);
		}
		
		if (!isset($properties['name']))
		{
			$properties = array_add($properties, 'name', 'Col. ' . (count($this->colModel) + 1));
		}
		
		$this->markFunctionTypeProperty($properties);
				
		array_push($this->colModel, array_merge($this->defaultColModelProperties, $properties));
		
		return $this;
		
	}
	
	/**
	 * Set an identifier to the grid.
	 *
	 * @param  string $option
	 * 	A valid jqGrid option, online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:options
	 * @param  mixed $option
	 * 	A value of an option can be a string, boolean or array.
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setGridOption($option, $value)
	{
		foreach ($this->optionValidators as $validator)
		{
			$validator->validate(array_add(array(), $option, $value));
		}
		
		$property = array_add(array(), $option, $value);
				
		$this->markFunctionTypeProperty($property);
		
		if(isset($this->options[$option]))
		{
			$this->options[$option] = $property[$option];
		}
		else
		{
			$this->options = array_add($this->options, $option, $property[$option]);
		}

		return $this;
	}
	
	/**
	 * Set a jqGrid event.
	 *
	 * @param  string $event
	 * 	Valid grid event, online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:events&s[]=event
	 * @param  string $code
	 * 	Javascript code which will be executed when the event raises
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setGridEvent($event, $code)
	{
		foreach ($this->optionValidators as $validator)
		{
			$validator->validate(array_add(array(), $option, $value));
		}
		
		$this->options = array_add($this->options, $event, '###' . $code . '###');
		
		return $this;
	}
	
	/**
	 * Set options in the navigator or in any of the following modules add,edit,del,view, search. Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:navigator
	 *
	 * @param  string $module
	 * 	Can be navigator, add, edit, del, view, search.
	 * @param  array $options
	 * 	Options that are applicable to this module The key correspond to the options in jqGrid
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setNavigatorOptions($module, array $options)
	{
		foreach ($this->navigatorValidators as $validator)
		{
			$validator->validate(array_add(array(), $module, $options));
		}
		
		$this->markFunctionTypeProperty($options);
		
		switch ($module)
		{
			case 'navigator':
				$this->navigatorOptions = array_merge($this->navigatorOptions, $options);
				break;
			case 'edit':
				$this->navigatorEditOptions = $options;
				break;
			case 'add':
				$this->navigatorAddOptions = $options;
				break;
			case 'del':
				$this->navigatorDeleteOptions = $options;
				break;
			case 'search':
				$this->navigatorSearchOptions = $options;
				break;
			case 'view':
				$this->navigatorViewOptions = $options;
				break;
		}
			
		return $this;	
	}
	
	/**
	 * Set an event in the navigator or in the diffrent modules add,edit,del,view, search. Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:navigator
	 *
	 * @param  string $module
	 * 	Can be navigator, edit, add,  del, search, view.
	 * @param  string $event
	 * 	Valid event for the particular module
	 * @param  string $code
	 * 	Javascript code which will be executed when the event raises
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setNavigatorEvent($module, $event, $code)
	{	
		foreach ($this->navigatorValidators as $validator)
		{
			$validator->validate(array_add(array(), $module, $options));
		}
		
		switch ($module)
		{
			case 'navigator':
				$this->navigatorOptions = array_add($this->navigatorOptions, $event, '###' . $code . '###');
				break;
			case 'edit':
				$this->navigatorEditOptions = array_add($this->navigatorEditOptions, $event, '###' . $code . '###');
				break;
			case 'add':
				$this->navigatorAddOptions = array_add($this->navigatorAddOptions, $event, '###' . $code . '###');
				break;
			case 'del':
				$this->navigatorDeleteOptions = array_add($this->navigatorDeleteOptions, $event, '###' . $code . '###');
				break;
			case 'search':
				$this->navigatorSearchOptions = array_add($this->navigatorSearchOptions, $event, '###' . $code . '###');
				break;
			case 'view':
				$this->navigatorViewOptions = array_add($this->navigatorViewOptions, $event, '###' . $code . '###');
				break;
		}
			
		return $this;
	}
	
	/**
	 * Set options for the toolbar filter when enabled. Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:toolbar_searching
	 *
	 * @param  array $options
	 * 	Options that are applicable to the filter toolbar
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setFilterToolbarOptions(array $options)
	{
		foreach ($this->filterToolbarValidators as $validator)
		{
			$validator->validate(array_add(array(), $module, $options));
		}
		
		$this->markFunctionTypeProperty($options);
		
		$this->filterToolbarOptions = array_merge($this->filterToolbarOptions, $options);
			
		return $this;
	}
	
	/**
	 * Set a toolbar event.
	 *
	 * @param  string $event
	 * 	Valid toolbar grid event, online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:toolbar_searching
	 * @param  string $code
	 * 	Javascript code which will be executed when the event raises
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setFilterToolbarEvent($event, $code)
	{
		foreach ($this->filterToolbarValidators as $validator)
		{
			$validator->validate(array_add(array(), $event, $code));
		}
		
		$this->filterToolbarOptions = array_merge($this->filterToolbarOptions, array_add(array(), $event, '###' . $code . '###'));
	
		return $this;
	}
	
	/**
	 * Enable filter toolbar.
	 *
	 * @param  boolean $createToggleButton
	 * 	If true a toggle button will be created in the navigator. Default is null
	 * @param  boolean $createClearButton
	 * 	If true a clear button will be created in the navigator. Default is null
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function enableFilterToolbar($createToggleButton = null, $createClearButton = null)
	{
		$this->filterToolbar = true;
		
		if(!is_null($createToggleButton))
		{
			$this->toggleButton = $createToggleButton;
		}
		
		if(!is_null($createClearButton))
		{
			$this->clearButton = $createClearButton;
		}
		
		return $this;
	}
	
	/**
	 * Main method that construct the html and javascript code of the grid.
	 *
	 * @param  boolean $script
	 * 	If true a script tag before constructin the grid. Default is true
	 * @param  boolean $createTableElement
	 * 	If true the table element is created automatically from this method. Default is true
	 * @param  boolean $createPagerElement
	 * 	If true the pager element is created automatically from this method. Default is true
	 * @param  boolean $echo
	 * 	If false the function return the string representing the grid. Default is true
	 * @return mixed
	 * 	String if $echo is set to false, void in any other case
	 */
	public function renderGrid($script = true, $createTableElement = true, $createPagerElement = true, $echo = true)
	{
		$this->options = array_add($this->options, 'colModel', $this->colModel);
		
		if (!isset($this->options['pager']))
		{
			$this->options = array_add($this->options, 'pager', $this->gridId . 'Pager');
		}
		
		$html = '';
		
		if($createTableElement)
		{
			$html .= '<table id="'. $this->gridId .'"></table>';
		}
		
		if($createTableElement)
		{
			$html .= '<div id="' . $this->options['pager'] . '"></div>';
		}
		
		$script = 'jQuery("#' . $this->gridId . '").jqGrid(' .  json_encode($this->options) . ')';
		$script .= '.navGrid("#'. $this->options['pager'] .'", '. json_encode($this->navigatorOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorEditOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorAddOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorDeleteOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorSearchOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorViewOptions, JSON_FORCE_OBJECT) .' );';
		
		if($this->filterToolbar)
		{
			$script .= 'jQuery("#' . $this->gridId . '").jqGrid("filterToolbar", ' .  json_encode($this->filterToolbarOptions, JSON_FORCE_OBJECT) . ');';
			
			if($this->toggleButton)
			{
				$script .= 'jQuery("#' . $this->gridId . '").jqGrid("navButtonAdd", "#' .  $this->options['pager'] . '",{"caption":"'. $this->toggleButtonText .'", "buttonicon":"ui-icon-pin-s", "onClickButton":function(){ jQuery("#' . $this->gridId . '")[0].toggleToolbar();} });';
			}
			
			if($this->clearButton)
			{
				$script .= 'jQuery("#' . $this->gridId . '").jqGrid("navButtonAdd", "#' .  $this->options['pager'] . '",{"caption":"'. $this->clearButtonText .'", "buttonicon":"ui-icon-refresh", "onClickButton":function(){ jQuery("#' . $this->gridId . '")[0].clearToolbar();} });';
			}
		}
			
		$script = str_replace(array('"###','###"','\"'), array('', '', '"'), $script);
		
		$this->gridId = str_random(10);
		
		$this->options = $this->defaultGridOptions;
		
		$this->colModel = array();
		
		$this->navigatorOptions = array();
		
		$this->navigatorEditOptions = array();
		
		$this->navigatorAddOptions = array();
		
		$this->navigatorDeleteOptions = array();
		
		$this->navigatorSearchOptions = array();
		
		$this->navigatorViewOptions = array();
		
		$this->filterToolbarOptions = $this->defaultfilterToolbarOptions;
		
		if($script)
		{
			$script = '<script type="text/javascript">'.$script.'</script>';
		}
		
		if($echo)
		{
			echo $html.$script;
		}
		else
		{
			return $html.$script;
		}	
	}
	
	/**
	 * Mark function type properties.
	 * First and last quotes will be removed from the javascript code to all properties marked by this method
	 *
	 * @param  array $properties
	 * 	An array of valid jqGrid column model property, the key of the array must correspond to a column model property.
	 * 
	 * @return void
	 */
	protected function markFunctionTypeProperty(array &$properties)
	{
		foreach ($properties as $key => &$value) 
		{			
			if (in_array($key,array_keys($this->functionTypeProperties)))
			{			
					if (!in_array($value, $this->functionTypeProperties[$key]))
					{
						$value = '###' . $value . '###';
					}
			}
			else
			{
				if(is_array($value) && array_values($value) != $value)
				{
					$this->markFunctionTypeProperty($value);
				}	
			}
			
		}
	}
	
}

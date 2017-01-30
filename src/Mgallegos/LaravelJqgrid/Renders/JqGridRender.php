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
	* Pivot Grid
	*
	* @var boolean
	*
	*/
	protected $jqPivot;

	/**
	* Frozen columns
	*
	* @var boolean
	*
	*/
	protected $frozenColumn;

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
	* Options array
	*
	* @var array
	*/
	protected $pivotOptions;

	/**
	* Pivot options names array
	*
	* @var array
	*/
	protected $pivotOptionsNames;

	/**
	* Group Header options array
	*
	* @var array
	*/
	protected $groupHeaderOptions;

	/**
	* Group header options names array
	*
	* @var array
	*/
	protected $groupHeaderOptionsNames;

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
	* Filter toolbar button options array
	*
	* @var array
	*/
	protected $filterToolbarButtonsOptions;

	/**
	* Filter toolbar button options array
	*
	* @var array
	*/
	protected $exportButtonsOptions;

	/**
	* Laravel Excel File Properties
	*
	* @var array
	*/
	protected $fileProperties;

	/**
	* Laravel Excel Sheet Properties
	*
	* @var array
	*/
	protected $sheetProperties;

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
	* Default pivot grid options array
	*
	* @var array
	*/
	protected $defaultPivotGridOptions;

	/**
	* Default group header options array
	*
	* @var array
	*/
	protected $defaultGroupHeaderOptions;

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
	* Default filter toolbar button options
	*
	* @var array
	*/
	protected $defaultFilterToolbarButtonsOptions;

	/**
	* Default Export Buttons Options
	*
	* @var array
	*/
	protected $defaultExportButtonsOptions;

	/**
	* Laravel Excel Default File Properties
	*
	* @var array
	*/
	protected $defaultFileProperties;

	/**
	* Laravel Excel Default Sheet Properties
	*
	* @var array
	*/
	protected $defaultSheetProperties;

	/**
	 * Array of JqGrid function type properties
	 *
	 * @var array
	 */
	protected $functionTypeProperties;

	/**
	* Session token
	*
	* @var string
	*
	*/
	protected $token;

	/**
	 * Check on exist visible export button(s)
	 *
	 * @var boolean
	 *
	 */
	protected $exportButtonsVisible;

	/**
	 * Create a new JqGridRender instance.
	 *
	 * @param
	 */
	public function __construct(array $optionValidators = array(), array $colModelValidators = array(), array $navigatorValidators = array(), array $filterToolbarValidators = array(), array $defaultGridOptions = array(), array $defaultPivotGridOptions = array(), array $defaultGroupHeaderOptions = array(), array $defaultColModelProperties = array(), array $defaultNavigatorOptions = array(), array $defaultfilterToolbarOptions = array(), array $defaultFilterToolbarButtonsOptions = array(), array $defaultExportButtonsOptions = array(), array $defaultFileProperties = array(), array $defaultSheetProperties = array(), array $functionTypeProperties = array(), array $pivotOptionsNames = array(), array $groupHeaderOptionsNames = array(), $token)
	{
		$this->gridId = str_random(10);

		$this->jqPivot = false;

		$this->frozenColumn = false;

		$this->colModelValidators = $colModelValidators;

		$this->optionValidators = $optionValidators;

		$this->navigatorValidators = $navigatorValidators;

		$this->filterToolbarValidators = $filterToolbarValidators;

		$this->colModel = array();

		$this->options = $defaultGridOptions;

		$this->pivotOptions = $defaultPivotGridOptions;

		$this->pivotOptionsNames = $pivotOptionsNames;

		$this->groupHeaderOptions = $defaultGroupHeaderOptions;

		$this->groupHeaderOptionsNames = $groupHeaderOptionsNames;

		$this->navigatorOptions = $defaultNavigatorOptions;

		$this->navigatorEditOptions = array();

		$this->navigatorAddOptions = array();

		$this->navigatorDeleteOptions = array();

		$this->navigatorSearchOptions = array();

		$this->navigatorViewOptions = array();

		$this->filterToolbarOptions = $defaultfilterToolbarOptions;

		$this->filterToolbarButtonsOptions = $defaultFilterToolbarButtonsOptions;

		$this->exportButtonsOptions = $defaultExportButtonsOptions;

		$this->fileProperties = $defaultFileProperties;

		$this->sheetProperties = $defaultSheetProperties;

		$this->defaultColModelProperties = $defaultColModelProperties;

		$this->defaultGridOptions = $defaultGridOptions;

		$this->defaultPivotGridOptions = $defaultPivotGridOptions;

		$this->defaultGroupHeaderOptions = $defaultGroupHeaderOptions;

		$this->defaultNavigatorOptions = $defaultNavigatorOptions;

		$this->defaultfilterToolbarOptions = $defaultfilterToolbarOptions;

		$this->defaultFilterToolbarButtonsOptions = $defaultFilterToolbarButtonsOptions;

		$this->defaultExportButtonsOptions = $defaultExportButtonsOptions;

		$this->defaultFileProperties = $defaultFileProperties;

		$this->defaultSheetProperties = $defaultSheetProperties;

		$this->functionTypeProperties = $functionTypeProperties;

		$this->token = $token;
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
	 * 	An array of valid jqGrid column model property, the index key of the array must correspond to a column model property.
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

		if (in_array('frozen', $properties))
		{
			$this->frozenColumn = true;
		}

		if (!isset($properties['name']) && !isset($properties['index']))
		{
			$properties = array_add($properties, 'name', 'Col. ' . (count($this->colModel) + 1));
			$properties = array_add($properties, 'index', 'Col. ' . (count($this->colModel) + 1));
		}

		if (!isset($properties['name']) && isset($properties['index']))
		{
			$properties = array_add($properties, 'name', $properties['index']);
		}

		if (isset($properties['name']) && !isset($properties['index']))
		{
			$properties = array_add($properties, 'index', $properties['name']);
		}

		$this->markFunctionTypeProperty($properties);

		array_push($this->colModel, array_merge($this->defaultColModelProperties, $properties));

		return $this;

	}

	/**
	* Add a group header. This are columns that can be added above the normal grid columns.
	* This method has no effect when working with pivot grid.
	*
	* @param  array $properties
	* 	An array of valid group header options.
	* 	Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:groupingheadar
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function addGroupHeader(array $properties = array())
	{
		foreach ($this->optionValidators as $validator)
		{
			$validator->validate(array_add(array(), $option, $value));
		}

		$this->markFunctionTypeProperty($properties);

		if(!isset($this->groupHeaderOptions['groupHeaders']))
		{
			$this->groupHeaderOptions['groupHeaders'] = array();
		}

		array_push($this->groupHeaderOptions['groupHeaders'], $properties);

		return $this;
	}

	/**
	* Add a X dimension. Use this method only when working with pivot grids.
	*
	* @param  array $properties
	* 	An array of valid xDimension options.
	* 	Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:pivotsettings
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function addXDimension(array $properties = array())
	{
		foreach ($this->colModelValidators as $validator)
		{
			$validator->validate($properties);
		}

		$this->markFunctionTypeProperty($properties);

		if(!isset($this->pivotOptions['xDimension']))
		{
			$this->pivotOptions['xDimension'] = array();
		}

		array_push($this->pivotOptions['xDimension'], array_merge($this->defaultColModelProperties, $properties));

		return $this;
	}

	/**
	* Add a Y dimension. Use this method only when working with pivot grids.
	*
	* @param  array $properties
	* 	An array of valid yDimension options.
	* 	Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:pivotsettings
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function addYDimension(array $properties = array())
	{
		foreach ($this->colModelValidators as $validator)
		{
			$validator->validate($properties);
		}

		$this->markFunctionTypeProperty($properties);

		if(!isset($this->pivotOptions['yDimension']))
		{
			$this->pivotOptions['yDimension'] = array();
		}

		array_push($this->pivotOptions['yDimension'], array_merge($this->defaultColModelProperties, $properties));

		return $this;
	}

	/**
	* Add an aggregate. Use this method only when working with pivot grids.
	*
	* @param  array $properties
	* 	An array of valid aggregate options (all jqGrid column model property can be used).
	* 	Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:pivotsettings
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function addAggregate(array $properties = array())
	{
		foreach ($this->colModelValidators as $validator)
		{
			$validator->validate($properties);
		}

		$this->markFunctionTypeProperty($properties);

		if(!isset($this->pivotOptions['aggregates']))
		{
			$this->pivotOptions['aggregates'] = array();
		}

		array_push($this->pivotOptions['aggregates'], array_merge($this->defaultColModelProperties, $properties));

		return $this;
	}

	/**
	 * Set a jqGrid option.
	 *
	 * @param  string $option
	 * 	A valid jqGrid option, online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:options or
	 * 	a valid pivot grid option, online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:pivotsettings
	 * 	a valid group header option, online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:groupingheadar
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

		if (in_array($option, array('xDimension', 'yDimension', 'aggregates')))
		{
			foreach ($value as &$v)
			{
				$v = array_merge($this->defaultColModelProperties, $v);
				$this->markFunctionTypeProperty($v);
			}
		}

		$property = array_add(array(), $option, $value);

		$this->markFunctionTypeProperty($property);

		if (in_array($option, $this->pivotOptionsNames))
		{
			if(isset($this->pivotOptions[$option]))
			{
				$this->pivotOptions[$option] = $property[$option];
			}
			else
			{
				$this->pivotOptions = array_add($this->pivotOptions, $option, $property[$option]);
			}
		}
		else if (in_array($option, $this->groupHeaderOptionsNames))
		{
			if(isset($this->groupHeaderOptions[$option]))
			{
				$this->groupHeaderOptions[$option] = $property[$option];
			}
			else
			{
				$this->groupHeaderOptions = array_add($this->groupHeaderOptions, $option, $property[$option]);
			}
		}
		else
		{
			if(isset($this->options[$option]))
			{
				$this->options[$option] = $property[$option];
			}
			else
			{
				$this->options = array_add($this->options, $option, $property[$option]);
			}
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
			$validator->validate(array_add(array(), $event, $code));
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
			//$validator->validate(array_add(array(), $module, $options));
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
			$validator->validate($options);
		}

		$this->markFunctionTypeProperty($options);

		$this->filterToolbarOptions = array_merge($this->filterToolbarOptions, $options);

		return $this;
	}

	/**
	* Set a export button option
	*
	* @param  string $option
	* 	A valid export button option: xlsButtonVisible, xlsButtonText, xlsIcon, csvButtonVisible, csvButtonText, csvIcon, srcDateFormat, newDateFormat
	* @param  mixed $option
	* 	A value of an option can be a string or boolean.
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function setExportButtonsOption($option, $value)
	{
		if(isset($this->exportButtonsOptions[$option]))
		{
			$this->exportButtonsOptions[$option] = $value;
		}
		else
		{
			$this->exportButtonsOptions = array_add($this->exportButtonsOptions, $option, $value);
		}

		return $this;
	}

	/**
	* Set a Laravel Excel file property.
	*
	* @param  string $option
	* 	A valid Laravel Excel file property, online documentation available at http://www.maatwebsite.nl/laravel-excel/docs/reference-guide
	* @param  mixed $option
	* 	A value of an option can be a string, boolean or array.
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function setFileProperty($option, $value)
	{
		if(isset($this->fileProperties[$option]))
		{
			$this->fileProperties[$option] = $value;
		}
		else
		{
			$this->fileProperties = array_add($this->fileProperties, $option, $value);
		}

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
	* When this method is called the grid will be treated as Pivot Grid (differents javascript methods are used to generate the grid) according to the official documentation. Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:pivotdescription.
	*
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function setGridAsPivot()
	{
		$this->jqPivot = true;

		return $this;
	}

	/**
	* Hide XLS Navigator button.
	*
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function hideXlsExporter()
	{
		$this->exportButtonsOptions['xlsButtonVisible'] = false;

		return $this;
	}

	/**
	* Hide csv Navigator button.
	*
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function hideCsvExporter()
	{
		$this->exportButtonsOptions['csvButtonVisible'] = false;

		return $this;
	}

	/**
	* Set a Laravel Excel sheet property.
	*
	* @param  string $option
	* 	A valid Laravel Excel sheet property, online documentation available at http://www.maatwebsite.nl/laravel-excel/docs/reference-guide
	* @param  mixed $option
	* 	A value of an option can be a string, boolean or array.
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function setSheetProperty($option, $value)
	{
		if(isset($this->sheetProperties[$option]))
		{
			$this->sheetProperties[$option] = $value;
		}
		else
		{
			$this->sheetProperties = array_add($this->sheetProperties, $option, $value);
		}

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
		$this->filterToolbarButtonsOptions['filterToolbar'] = true;

		if(!is_null($createToggleButton))
		{
			$this->filterToolbarButtonsOptions['toggleButton'] = $createToggleButton;
		}

		if(!is_null($createClearButton))
		{
			$this->filterToolbarButtonsOptions['clearButton'] = $createClearButton;
		}

		return $this;
	}

	/**
	 * Main method that construct the html and javascript code of the grid.
	 *
	 * @param  boolean $script
	 * 	If true javascript tags will be included within the output. Default is true
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

		if(isset($this->options['filename']))
		{
			$fileName = mb_substr($this->options['filename'],0,31);
		}
		else
		{
			$fileName = $this->gridId;
		}

		if(isset($this->options['groupingView']))
		{
			$groupingView = $this->options['groupingView'];
		}
		else
		{
			$groupingView = array();
		}

		if(isset($this->groupHeaderOptions['groupHeaders']))
		{
			$groupHeaders = $this->groupHeaderOptions['groupHeaders'];
		}
		else
		{
			$groupHeaders = array();
		}

		$html = '';
		$html .= '<form method="' . $this->options['mtype'] . '" action="'. $this->options['url'] . '" accept-charset="UTF-8" id="'. $this->gridId .'ExportForm">
								<input name="_token" type="hidden" value="' . $this->token . '">
								<input id="'. $this->gridId .'Name" name="name" type="hidden" value="'. $fileName .'">
								<input id="'. $this->gridId .'Model" name="model" type="hidden">
								<input id="'. $this->gridId .'Sidx" name="sidx" type="hidden">
								<input id="'. $this->gridId .'Sord" name="sord" type="hidden">
								<input id="'. $this->gridId .'ExportFormat" name="exportFormat" type="hidden" value="xls">
								<input id="'. $this->gridId .'Filters" name="filters" type="hidden">
								<input id="'. $this->gridId .'PivotFlag" name="pivot" type="hidden" value="' . $this->jqPivot . '">
								<input id="'. $this->gridId .'Rows" name="pivotRows" type="hidden">
								<input id="'. $this->gridId .'SrcDateFormat" name="srcDateFormat" type="hidden">
								<input id="'. $this->gridId .'NewDateFormat" name="newDateFormat" type="hidden">
								<input id="'. $this->gridId .'FotterRow" name="fotterRow" type="hidden">
								<input name="fileProperties" type="hidden" value=\'' . json_encode($this->fileProperties) . '\'>
								<input name="sheetProperties" type="hidden" value=\'' . json_encode($this->sheetProperties) . '\'>
								<input name="groupingView" type="hidden" value=\'' . json_encode($groupingView) . '\'>
								<input name="groupHeaders" type="hidden" value=\'' . json_encode($groupHeaders) . '\'>
							</form>';

		if($createTableElement)
		{
			$html .= '<table id="'. $this->gridId .'"></table>';
		}

		if($createTableElement)
		{
			$html .= '<div id="' . $this->options['pager'] . '"></div>';
		}

		if($this->jqPivot)
		{
			$mtype = $this->options['mtype'];
			unset($this->options['colModel'], $this->options['mtype'], $this->options['datatype']);
			$script = 'jQuery("#' . $this->gridId . '").jqGrid("jqPivot", "'. $this->options['url'] . '", ' . json_encode($this->pivotOptions) . ', ' . json_encode($this->options) . ', {async : false, type: "' . $mtype .'"});';
			$script .= 'jQuery("#' . $this->gridId . '").navGrid("#'. $this->options['pager'] .'", '. json_encode($this->navigatorOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorEditOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorAddOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorDeleteOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorSearchOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorViewOptions, JSON_FORCE_OBJECT) .' );';
		}
		else
		{
			$script = 'jQuery("#' . $this->gridId . '").jqGrid(' .  json_encode($this->options) . ')';
			$script .= '.navGrid("#'. $this->options['pager'] .'", '. json_encode($this->navigatorOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorEditOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorAddOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorDeleteOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorSearchOptions, JSON_FORCE_OBJECT) .', '. json_encode($this->navigatorViewOptions, JSON_FORCE_OBJECT) .' );';
		}
/*
		$script .= 'jQuery("#' . $this->gridId . '").jqGrid("navButtonAdd", "#' .  $this->options['pager'] . '",{"id": "' . $this->gridId . 'XlsButton", "caption":"' . $this->exportButtonsOptions['xlsButtonText'] . '", "buttonicon":"' . $this->exportButtonsOptions['xlsIcon'] . '", "onClickButton":function(){ ' . $this->getJavascriptExportFunctionCode() . ' jQuery("#' . $this->gridId . 'ExportFormat").val("xls"); jQuery("#' . $this->gridId . 'ExportForm").submit();} });';
		$script .= 'jQuery("#' . $this->gridId . '").jqGrid("navButtonAdd", "#' .  $this->options['pager'] . '",{"id": "' . $this->gridId . 'CsvButton", "caption":"' . $this->exportButtonsOptions['csvButtonText'] . '", "buttonicon":"' . $this->exportButtonsOptions['csvIcon'] . '", "onClickButton":function(){ ' . $this->getJavascriptExportFunctionCode() . ' jQuery("#' . $this->gridId . 'ExportFormat").val("csv"); jQuery("#' . $this->gridId . 'ExportForm").submit();} });';

		if($this->exportButtonsOptions['xlsButtonVisible'] || $this->exportButtonsOptions['csvButtonVisible'])
		{
			$script .= 'jQuery("#' . $this->gridId . '").jqGrid("navSeparatorAdd", "#' .  $this->options['pager'] . '");';
		}

		if(!$this->exportButtonsOptions['xlsButtonVisible'])
		{
			$script .= 'jQuery("#' . $this->gridId . 'XlsButton").hide();';
		}

		if(!$this->exportButtonsOptions['csvButtonVisible'])
		{
			$script .= 'jQuery("#' . $this->gridId . 'CsvButton").hide();';
		}
*/
		foreach ($this->exportButtonsOptions as $key => $value)
		{
			if( preg_match('/ButtonVisible$/', $key) && gettype($value) == 'boolean' )
			{
				$script .= "\n\n\t// Add button and hendler for ". strtoupper(substr($key, 0, -1*strlen('ButtonVisible'))) . "-export : \n";
				$script .= $this->getJavascriptExportFunctionCode( substr($key, 0, -1*strlen('ButtonVisible') ) );
			}
		}

		if($this->exportButtonsVisible)
		{
			$script .= 'jQuery("#' . $this->gridId . '").jqGrid("navSeparatorAdd", "#' .  $this->options['pager'] . '");';
		}

		if($this->filterToolbarButtonsOptions['filterToolbar'])
		{
			$script .= 'jQuery("#' . $this->gridId . '").jqGrid("filterToolbar", ' .  json_encode($this->filterToolbarOptions, JSON_FORCE_OBJECT) . ');';

			if($this->filterToolbarButtonsOptions['toggleButton'])
			{
				$script .= 'jQuery("#' . $this->gridId . '").jqGrid("navButtonAdd", "#' .  $this->options['pager'] . '",{"caption":"'. $this->filterToolbarButtonsOptions['toggleButtonText'] .'", "buttonicon":"ui-icon-pin-s", "onClickButton":function(){ jQuery("#' . $this->gridId . '")[0].toggleToolbar();} });';
			}

			if($this->filterToolbarButtonsOptions['clearButton'])
			{
				$script .= 'jQuery("#' . $this->gridId . '").jqGrid("navButtonAdd", "#' .  $this->options['pager'] . '",{"caption":"'. $this->filterToolbarButtonsOptions['clearButtonText'] .'", "buttonicon":"ui-icon-refresh", "onClickButton":function(){ jQuery("#' . $this->gridId . '")[0].clearToolbar();} });';
			}

			$script .= 'jQuery("#' . $this->gridId . '").jqGrid("navSeparatorAdd", "#' .  $this->options['pager'] . '");';
		}

		if(!empty($this->groupHeaderOptions))
		{
			$script .= 'setTimeout(function () {jQuery("#' . $this->gridId . '").jqGrid("setGroupHeaders", ' .  json_encode($this->groupHeaderOptions) . ');}, 500);';
		}

		if($this->frozenColumn)
		{
			$script .= 'jQuery("#' . $this->gridId . '").jqGrid("setFrozenColumns");';
		}

		$script = str_replace(array('"###','###"','\"', '"JS>>>', '<<<JS"'), array('', '', '"', '' ,''), $script);

		$this->reset();

		if($script)
		{
			$script = '<script type="text/javascript">'. $script .'</script>';
		}

		if($echo)
		{
			echo $html . $script;
		}
		else
		{
			return $html.$script;
		}
	}

	/**
	* Reset variables to their original state.
	*
	* @return void
	*/
	protected function reset()
	{
		$this->gridId = str_random(10);

		$this->jqPivot = false;

		$this->frozenColumn = false;

		$this->options = $this->defaultGridOptions;

		$this->pivotOptions = $this->defaultPivotGridOptions;

		$this->groupHeaderOptions = $this->defaultGroupHeaderOptions;

		$this->colModel = array();

		$this->navigatorOptions = $this->defaultNavigatorOptions;

		$this->navigatorEditOptions = array();

		$this->navigatorAddOptions = array();

		$this->navigatorDeleteOptions = array();

		$this->navigatorSearchOptions = array();

		$this->navigatorViewOptions = array();

		$this->filterToolbarOptions = $this->defaultfilterToolbarOptions;

		$this->filterToolbarButtonsOptions = $this->defaultFilterToolbarButtonsOptions;

		$this->exportButtonsOptions = $this->defaultExportButtonsOptions;

		$this->fileProperties = $this->defaultFileProperties;

		$this->sheetProperties = 	$this->defaultSheetProperties;
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
			if (in_array($key, array_keys($this->functionTypeProperties)))
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

	/**
	 * Get exporter's javascript code.
	 *
	 * @param  string $exportFormat
	 * @return void
	 */
	protected function getJavascriptExportFunctionCode($exportFormat)
	{
		$code = 'jQuery("#' . $this->gridId . '").jqGrid("navButtonAdd", "#' .  $this->options['pager'];
		$code .= '",{"id": "' . $this->gridId . ucfirst($exportFormat) . 'Button", "caption":"' . $this->exportButtonsOptions[$exportFormat . 'ButtonText'];
		$code .= '", "buttonicon":"' . $this->exportButtonsOptions[$exportFormat . 'Icon'];
		$code .= '", "onClickButton":function(){ ';

		$code .= '
			var headers = [], rows = [], row, cellCounter, postData, groupingView, sidx, sord;
			jQuery("#' . $this->gridId . 'Model").val(JSON.stringify(jQuery("#' . $this->gridId . '").getGridParam("colModel")));
			postData = jQuery("#' . $this->gridId . '").getGridParam("postData");
			if(postData["filters"] != undefined)
			{
				jQuery("#' . $this->gridId . 'Filters").val(postData["filters"]);
			}
		';

		$code .= '
			groupingView = jQuery("#' . $this->gridId . '").getGridParam("groupingView");
			sidx = jQuery("#' . $this->gridId . '").getGridParam("sortname");
			if(sidx == null) sidx = "";
			sord = jQuery("#' . $this->gridId . '").getGridParam("sortorder");
			if(sord == null) sord = "";
			if(groupingView.groupField.length > 0)
			{
				jQuery("#' . $this->gridId . 'Sidx").val(groupingView.groupField[0] + " " + groupingView.groupOrder[0] + "," + " " + sidx);
			}
			else
			{
				jQuery("#' . $this->gridId . 'Sidx").val(sidx);
			}
			jQuery("#' . $this->gridId . 'Sord").val(sord);
		';

		if($this->jqPivot)
		{
			$code .= '
				jQuery.each($("#gbox_' . $this->gridId . '").find(".ui-jqgrid-sortable"), function( index, header )
				{
					headers.push(jQuery(header).text());
				});
				jQuery.each($("#gview_' . $this->gridId . '").find(".ui-widget-content"), function( index, gridRows )
				{
					row = {}, cellCounter = 0;
					jQuery.each($(gridRows).find("td"), function( index, cell)
					{
						row[headers[cellCounter++]] = $(cell).text();
					});
					for (i = cellCounter; i < headers.length; i++) {
							row[headers[i]] = "";
					}
					rows.push(row);
				});
				jQuery("#' . $this->gridId . 'Rows").val(JSON.stringify(rows));
				';
		}
		else
		{
			//Include footer row if exists
			$code .= '
				jQuery.each($("#gbox_' . $this->gridId . '").find(".ui-jqgrid-sortable").filter(":visible"), function( index, header )
				{
					headers.push(jQuery(header).text());
				});
				row = {}, cellCounter = 0;
				jQuery.each($("#gview_' . $this->gridId . '").find(".footrow").find("td").filter(":visible"), function( index, cell)
				{
					row[headers[cellCounter++]] = $(cell).text();
				});
				jQuery("#' . $this->gridId . 'FotterRow").val(JSON.stringify(row));
			';
		}


		if(isset($this->exportButtonsOptions['srcDateFormat']))
		{
			$code .= ' jQuery("#' . $this->gridId . 'SrcDateFormat").val("' . $this->exportButtonsOptions['srcDateFormat'] . '");';
		}

		if(isset($this->exportButtonsOptions['newDateFormat']))
		{
			$code .= ' jQuery("#' . $this->gridId . 'NewDateFormat").val("' . $this->exportButtonsOptions['newDateFormat'] . '");';
		}

		$code .= ' jQuery("#' . $this->gridId . 'ExportFormat").val("' . $exportFormat . '");';
		$code .= 'jQuery("#' . $this->gridId . 'ExportForm").submit();} });';

		if(!$this->exportButtonsOptions[$exportFormat . 'ButtonVisible'])
		{
			$code .= 'jQuery("#' . $this->gridId . ucfirst($exportFormat) . 'Button").hide();';
		}else
		{
			$this->exportButtonsVisible = TRUE;
		}

		return $code;
	}


	public function addExport(array $properties = array())
	{
		$export_type = '';
		foreach ($properties as $key => $value)
		{
			if( preg_match('/ButtonVisible$/', $key) && gettype($value) == 'boolean' )
			{
				$export_type = substr($key, 0, -1*strlen('ButtonVisible')  );
			}
		}
		if ( $export_type == '' )
		{
			throw new \Exception('addExport-method does not set the required parameter ...ButtonVisible or can not determine the type of the method by prefix of this parameter');
		}

		$notFoundKeys = array_diff( [$export_type.'ButtonVisible', $export_type.'ButtonText', $export_type.'Icon'], array_keys($properties));
		if ( count($notFoundKeys) > 0 )
		{
			throw new \Exception('addExport-method does not set the required parameters: '.implode(', ', $notFoundKeys));
		}

		$this->exportButtonsOptions = array_merge($this->exportButtonsOptions, $properties);

		return $this;

	}
}

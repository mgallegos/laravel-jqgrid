<?php
/**
 * @file
 * JqGrid Render Interface.
 *
 * All LaravelJqGrid code is copyright by the original authors and released under the MIT License.
 * See LICENSE.
 */

namespace Mgallegos\LaravelJqgrid\Renders;

interface RenderInterface {

	/**
	 * Set an identifier to the grid.
	 *
	 * @param  string $id
	 * 	An id to interact with the grid through javascript
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setGridId($id);

	/**
	 * Add a column at the last position in the columns model.
	 *
	 * @param  array $properties
	 * 	An array of valid jqGrid column model property, the index key of the array must correspond to a column model property.
	 * 	Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:colmodel_options
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function addColumn(array $columnProperties);

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
	public function addGroupHeader(array $properties = array());

	/**
	* Add a X dimension. Use this method only when working with pivot grids.
	*
	* @param  array $properties
	* 	An array of valid xDimension options.
	* 	Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:pivotsettings
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function addXDimension(array $properties = array());

	/**
	* Add a Y dimension. Use this method only when working with pivot grids.
	*
	* @param  array $properties
	* 	An array of valid yDimension options.
	* 	Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:pivotsettings
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function addYDimension(array $properties = array());


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
	public function setGridOption($option, $value);

	/**
	 * Set a jqGrid event.
	 *
	 * @param  string $event
	 * 	Valid grid event, online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:events&s[]=event
	 * @param  array $code
	 * 	Javascript code which will be executed when the event raises
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setGridEvent($event, $code);

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
	public function setNavigatorOptions($module, array $options);

	/**
	 * Set an event in the navigator or in the diffrent modules add,edit,del,view, search. Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:navigator
	 *
	 * @param  string $module
	 * 	Can be navigator, add, edit, del, view, search.
	 * @param  string $event
	 * 	Valid event for the particular module
	 * @param  string $code
	 * 	Javascript code which will be executed when the event raises
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setNavigatorEvent($module, $event, $code);

	/**
	 * Set options for the toolbar filter when enabled. Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:toolbar_searching
	 *
	 * @param  array $options
	 * 	Options that are applicable to the filter toolbar
	 * @return $this
	 *  Returns an object, allowing the calls to be chained together in a single statement
	 */
	public function setFilterToolbarOptions(array $options);

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
	public function setFilterToolbarEvent($event, $code);

	/**
	* When this method is called the grid will be treated as Pivot Grid (differents javascript methods are used to generate the grid) according to the official documentation. Online documentation available at http://www.trirand.com/jqgridwiki/doku.php?id=wiki:pivotdescription.
	*
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function setGridAsPivot();

	/**
	* Hide XLS Navigator button.
	*
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function hideXlsExporter();

	/**
	* Hide CSV Navigator button.
	*
	* @return $this
	*  Returns an object, allowing the calls to be chained together in a single statement
	*/
	public function hideCsvExporter();

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
	public function setFileProperty($option, $value);

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
	public function setSheetProperty($option, $value);

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
	public function enableFilterToolbar($createToggleButton = null, $createClearButton = null);

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
	public function renderGrid($script = true, $createTableElement = true, $createPagerElement = true, $echo = true);
	
	 /**
   	* Add a export-type at the last position in exportButtonsOptions .
   	*
   	* @param  array $properties
   	*  An array with required parameters [ typeButtonVisible, typeButtonText, typeIcon]
     	* @return $this
     	*  Returns an object, allowing the calls to be chained together in a single statement
     	*/
	public function addExport(array $exportProperties);

}

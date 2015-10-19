<?php
/**
 * @file
 * Repository Interface.
 *
 * All LaravelJqGrid code is copyright by the original authors and released under the MIT License.
 * See LICENSE.
 */

namespace Mgallegos\LaravelJqgrid\Repositories;

interface RepositoryInterface {

	/**
	 * Calculate the number of rows. It's used for paging the result
	 *
	 * @param  array $filters
	 *	An array of filters, example: array(array('field'=>'column index/name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column index/name 2','op'=>'operator','data'=>'searched string column 2'))
	 *	The 'field' key will contain the 'index' column property if is set, otherwise the 'name' column property.
	 *	The 'op' key will contain one of the following operators: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
	 *	when the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
	 *	The 'data' key will contain the string searched by the user.
	 * @return integer
	 *	Total number of rows
	 */
	public function getTotalNumberOfRows(array $filters = array());


	/**
	 * Get the rows data to be shown in the grid.
	 *
	 * @param  integer $limit
	 *	Number of rows to be shown into the grid
	 * @param  integer $offset
	 *	Start position
	 * @param  string $orderBy
	 *	Column name to order by.
	 * @param  string $sord
	 *	Sorting order
	 * @param  array $filters
	 *	An array of filters, example: array(array('field'=>'column index/name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column index/name 2','op'=>'operator','data'=>'searched string column 2'))
	 *	The 'field' key will contain the 'index' column property if is set, otherwise the 'name' column property.
	 *	The 'op' key will contain one of the following operators: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
	 *	when the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
	 *	The 'data' key will contain the string searched by the user.
	 * @param  string $nodeId
	 *	Node id (used only when the treeGrid option is set to true)
	 * @param  string $nodeLevel
	 *	Node level (used only when the treeGrid option is set to true)
	 * @param  boolean $exporting
	 *	Flag that determines if the data will be exported (used only when the treeGrid option is set to true)
	 * @return array
	 *	An array of array, each array will have the data of a row.
	 *  Example: array(array("column1" => "1-1", "column2" => "1-2"), array("column1" => "2-1", "column2" => "2-2"))
	 */
	public function getRows($limit, $offset, $orderBy = null, $sord = null, array $filters = array(), $nodeId = null, $nodeLevel = null, $exporting);

}

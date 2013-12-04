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
	 *	An array of filters, example: array(array('field'=>'column name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column name 2','op'=>'operator','data'=>'searched string column 2'))
	 *	The operators are: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
	 *	When the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
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
	 * @param  array $sord
	 *	Sorting order
	 * @param  array $filters
	 *	An array of filters, example: array(array('field'=>'column name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column name 2','op'=>'operator','data'=>'searched string column 2'))
	 *	The operators are: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
	 *	When the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
	 * @return array
	 *	An array of array, each array will have the data of a row.
	 *	Example: array(array('row 1 col 1','row 1 col 2'), array('row 2 col 1','row 2 col 2'))
	 */
	public function getRows($limit, $offset, $orderBy = null, $sord = null, array $filters = array());

	
}

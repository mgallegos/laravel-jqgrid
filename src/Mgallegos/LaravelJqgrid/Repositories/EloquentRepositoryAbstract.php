<?php
/**
 * @file
 * Repository Interface.
 *
 * All LaravelJqGrid code is copyright by the original authors and released under the MIT License.
 * See LICENSE.
 */

namespace Mgallegos\LaravelJqgrid\Repositories;

abstract class EloquentRepositoryAbstract implements RepositoryInterface{


	/**
	 * Database
	 *
	 * @var Illuminate\Database\Eloquent\Model or Illuminate\Database\Query
	 *
	 */
	protected $Database;

	/**
	 * Visible columns
	 *
	 * @var Array
	 *
	 */
	protected $visibleColumns;

	/**
	 * OrderBy
	 *
	 * @var array
	 *
	 */
	protected $orderBy = array(array());


	/**
	 * Calculate the number of rows. It's used for paging the result.
	 *
	 * @param  array $filters
	 *  An array of filters, example: array(array('field'=>'column index/name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column index/name 2','op'=>'operator','data'=>'searched string column 2'))
	 *  The 'field' key will contain the 'index' column property if is set, otherwise the 'name' column property.
	 *  The 'op' key will contain one of the following operators: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
	 *  when the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
	 *  The 'data' key will contain the string searched by the user.
	 * @return integer
	 *  Total number of rows
	 */
	public function getTotalNumberOfRows(array $filters = array())
	{
		return  intval($this->Database->whereNested(function($query) use ($filters)
		{
			foreach ($filters as $filter)
			{
				if($filter['op'] == 'is in')
				{
					$query->whereIn($filter['field'], explode(',',$filter['data']));
					continue;
				}

				if($filter['op'] == 'is not in')
				{
					$query->whereNotIn($filter['field'], explode(',',$filter['data']));
					continue;
				}

				$query->where($filter['field'], $filter['op'], $filter['data']);
			}
		})
		->count());
	}


	/**
	 * Get the rows data to be shown in the grid.
	 *
	 * @param  integer $limit
	 *  Number of rows to be shown into the grid
	 * @param  integer $offset
	 *  Start position
	 * @param  string $orderBy
	 *  Column name to order by.
	 * @param  array $sordvisibleColumns
	 *  Sorting order
	 * @param  array $filters
	 *  An array of filters, example: array(array('field'=>'column index/name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column index/name 2','op'=>'operator','data'=>'searched string column 2'))
	 *  The 'field' key will contain the 'index' column property if is set, otherwise the 'name' column property.
	 *  The 'op' key will contain one of the following operators: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
	 *  when the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
	 *  The 'data' key will contain the string searched by the user.
	 * @return array
	 *  An array of array, each array will have the data of a row.
	 *  Example: array(array("column1" => "1-1", "column2" => "1-2"), array("column1" => "2-1", "column2" => "2-2"))
	 */
	public function getRows($limit, $offset, $orderBy = null, $sord = null, array $filters = array())
	{
		if(!is_null($orderBy) || !is_null($sord))
		{
			$this->orderBy = array(array($orderBy, $sord));
		}

		if($limit == 0)
		{
			$limit = 1;
		}

		$orderByRaw = array();

		foreach ($this->orderBy as $orderBy)
		{
			array_push($orderByRaw, implode(' ',$orderBy));
		}

		$orderByRaw = implode(',',$orderByRaw);

		$rows = $this->Database->whereNested(function($query) use ($filters)
		{
			foreach ($filters as $filter)
			{
				if($filter['op'] == 'is in')
				{
					$query->whereIn($filter['field'], explode(',',$filter['data']));
					continue;
				}

				if($filter['op'] == 'is not in')
				{
					$query->whereNotIn($filter['field'], explode(',',$filter['data']));
					continue;
				}

				$query->where($filter['field'], $filter['op'], $filter['data']);
			}
		})
		->take($limit)
		->skip($offset)
		->orderByRaw($orderByRaw)
		->get($this->visibleColumns);

		if(!is_array($rows))
		{
			$rows = $rows->toArray();
		}

		foreach ($rows as &$row)
		{
			$row = (array) $row;
		}

		return $rows;
	}

}

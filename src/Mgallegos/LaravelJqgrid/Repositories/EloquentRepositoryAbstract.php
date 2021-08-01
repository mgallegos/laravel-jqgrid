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
	 * Tree Grid Flag
	 *
	 * @var Boolean
	 *
	 */
	protected $treeGrid;

	/**
	 * Parent Column Name (TreeGrid Property)
	 *
	 * @var String
	 *
	 */
	protected $parentColumn;

	/**
	 * Leaf Column Name (TreeGrid Property)
	 *
	 * @var String
	 *
	 */
	protected $leafColumn;


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

				if($filter['op'] == 'is null')
				{
					$query->whereNull($filter['field']);
					continue;
				}

				if($filter['op'] == 'is not null')
				{
					$query->whereNotNull($filter['field']);
					continue;
				}

				if($filter['op'] == 'between')
				{
					if(strpos($filter['data'], ' - ') !== false)
					{
						list($from, $to) = explode(' - ', $filter['data'], 2);

						if(!$from or !$to)
						{
							throw new \Exception('Invalid between format');
						}
					}
					else
					{
						throw new \Exception('Invalid between format');
					}

					if( $from == $to)
					{
						$query->where($filter['field'], $from);
					}else
					{
						//$query->whereBetween($filter['field'], array($from, $to));
						$query->where($filter['field'], '>=', $from);
						$query->where($filter['field'], '<=', $to);
					}

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
	public function getRows($limit, $offset, $orderBy = null, $sord = null, array $filters = array(), $nodeId = null, $nodeLevel = null, $exporting)
	{
		$orderByRaw = null;

		if(!is_null($orderBy) || !is_null($sord))
		{
			$found = false;
			$pos = strpos($orderBy, 'desc');

			if ($pos !== false)
			{
				$found = true;
			}
			else
			{
				$pos = strpos($orderBy, 'asc');

				if ($pos !== false)
				{
					$found = true;
				}
			}

			if($found)
			{
				$orderBy = rtrim($orderBy);

				if(substr($orderBy, -1) == ',')
				{
					$orderBy = substr($orderBy, 0, -1);
				}
				else
				{
					$orderBy .= " $sord";
				}

				$orderByRaw = $orderBy;
			}
			else
			{
				$this->orderBy = array(array($orderBy, $sord));
			}
		}

		if($limit == 0)
		{
			$limit = 1;
		}

		if(empty($orderByRaw))
		{
			$orderByRaw = array();

			foreach ($this->orderBy as $orderBy)
			{
				array_push($orderByRaw, implode(' ',$orderBy));
			}

			$orderByRaw = implode(',',$orderByRaw);
		}

		$rows = $this->Database->whereNested(function($query) use ($filters, $nodeId, $exporting)
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

				if($filter['op'] == 'is null')
				{
					$query->whereNull($filter['field']);
					continue;
				}

				if($filter['op'] == 'is not null')
				{
					$query->whereNotNull($filter['field']);
					continue;
				}

				if($filter['op'] == 'between')
				{
					if(strpos($filter['data'], ' - ') !== false)
					{
						list($from, $to) = explode(' - ', $filter['data'], 2);

						if(!$from or !$to)
						{
							throw new \Exception('Invalid between format');
						}
					}
					else
					{
						throw new \Exception('Invalid between format');
					}

					if( $from == $to)
					{
						$query->where($filter['field'], $from);
					}else
					{
						//$query->whereBetween($filter['field'], array($from, $to));
						$query->where($filter['field'], '>=', $from);
						$query->where($filter['field'], '<=', $to);
					}
					continue;
				}

				$query->where($filter['field'], $filter['op'], $filter['data']);
			}

			if($this->treeGrid && !$exporting)
			{
				if(empty($nodeId))
				{
					$query->whereNull($this->parentColumn);
				}
				else
				{
					$query->where($this->parentColumn, '=', $nodeId);
				}
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

			if($this->treeGrid && !$exporting)
			{
				if(is_null($nodeLevel))
				{
					$row['level'] = 0;
				}
				else
				{
					$row['level'] = (int)$nodeLevel + 1;
				}

				if($row[$this->leafColumn] == 0)
				{
					$row[$this->leafColumn] = false;
				}
				else
				{
					$row[$this->leafColumn] = true;
				}
			}
		}

		return $rows;
	}

}

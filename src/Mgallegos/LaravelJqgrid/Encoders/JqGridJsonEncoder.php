<?php
/**
 * @file
 * JqGrid JSON Encoder.
 *
 * All LaravelJqGrid code is copyright by the original authors and released under the MIT License.
 * See LICENSE.
 */

namespace Mgallegos\LaravelJqgrid\Encoders;

use Mgallegos\LaravelJqgrid\Repositories\RepositoryInterface;
use Maatwebsite\Excel\Excel;
use Exception;

class JqGridJsonEncoder implements RequestedDataInterface {

	/**
	* Maatwebsite\Excel\Excel
	* @var Excel
	*/
	protected $Excel;

	/**
	* Construct Excel
	* @param  Maatwebsite\Excel\Excel $Excel
	*/
	public function __construct(Excel $Excel)
	{
			$this->Excel = $Excel;
	}

	/**
	 * Echo in a jqGrid compatible format the data requested by a grid.
	 *
	 * @param RepositoryInterface $dataRepository
	 *	An implementation of the RepositoryInterface
	 * @param  array $postedData
	 *	All jqGrid posted data
	 * @return string
	 *	String of a jqGrid compatible data format: xml, json, jsonp, array, xmlstring, jsonstring.
	 */
	public function encodeRequestedData(RepositoryInterface $Repository,  $postedData)
	{
		// $page = $postedData['page']; // get the requested page
		// $limit = $postedData['rows']; // get how many rows we want to have into the grid
		// $sidx = $postedData['sidx']; // get index row - i.e. user click to sort
		// $sord = $postedData['sord']; // get the direction

		if(isset($postedData['page']))
		{
			$page = $postedData['page']; // get the requested page
		}
		else
		{
			$page = 1;
		}

		if(isset($postedData['rows']))
		{
			$limit = $postedData['rows']; // get how many rows we want to have into the grid
		}
		else
		{
			$limit = null;
		}

		if(isset($postedData['sidx']))
		{
			$sidx = $postedData['sidx']; // get index row - i.e. user click to sort
		}
		else
		{
			$sidx = null;
		}

		if(isset($postedData['sord']))
		{
			$sord = $postedData['sord']; // get the direction
		}

		if(isset($postedData['filters']) && !empty($postedData['filters']))
		{
			$filters = json_decode(str_replace('\'','"',$postedData['filters']), true);
		}

		if(!$sidx || empty($sidx))
		{
			$sidx = null;
			$sord = null;
		}

		if(isset($filters['rules']) && is_array($filters['rules']))
		{
			foreach ($filters['rules'] as &$filter)
			{
				switch ($filter['op'])
				{
					case 'eq': //equal
						$filter['op'] = '=';
						break;
					case 'ne': //not equal
						$filter['op'] = '!=';
						break;
					case 'lt': //less
						$filter['op'] = '<';
						break;
					case 'le': //less or equal
						$filter['op'] = '<=';
						break;
					case 'gt': //greater
						$filter['op'] = '>';
						break;
					case 'ge': //greater or equal
						$filter['op'] = '>=';
						break;
					case 'bw': //begins with
						$filter['op'] = 'like';
						$filter['data'] = $filter['data'] . '%';
						break;
					case 'bn': //does not begin with
						$filter['op'] = 'not like';
						$filter['data'] = $filter['data'] . '%';
						break;
					case 'in': //is in
						$filter['op'] = 'is in';
						break;
					case 'ni': //is not in
						$filter['op'] = 'is not in';
						break;
					case 'ew': //ends with
						$filter['op'] = 'like';
						$filter['data'] = '%' . $filter['data'];
						break;
					case 'en': //does not end with
						$filter['op'] = 'not like';
						$filter['data'] = '%' . $filter['data'];
						break;
					case 'cn': //contains
						$filter['op'] = 'like';
						$filter['data'] = '%' . $filter['data'] . '%';
						break;
					case 'nc': //does not contains
						$filter['op'] = 'not like';
						$filter['data'] = '%' . $filter['data'] . '%';
						break;
				}
			}
		}
		else
		{
			$filters['rules'] = array();
		}

		$count = $Repository->getTotalNumberOfRows($filters['rules']);

		if(empty($limit))
		{
			$limit = $count;
		}

		if(!is_int($count))
		{
			throw new Exception('The method getTotalNumberOfRows must return an integer');
		}

		if( $count > 0 )
		{
			$totalPages = ceil($count/$limit);
		}
		else
		{
			$totalPages = 0;
		}

		if ($page > $totalPages)
		{
			$page = $totalPages;
		}

		if ($limit < 0 )
		{
			$limit = 0;
		}

		$start = $limit * $page - $limit;

		if ($start < 0)
		{
			$start = 0;
		}

		$limit = $limit * $page;

		if(empty($postedData['pivotRows']))
		{
			$rows = $Repository->getRows($limit, $start, $sidx, $sord, $filters['rules']);
		}
		else
		{
			$rows = json_decode($postedData['pivotRows'], true);
		}

		if(!is_array($rows) || (isset($rows[0]) && !is_array($rows[0])))
		{
			throw new Exception('The method getRows must return an array of arrays, example: array(array("column1"  =>  "1-1", "column2" => "1-2"), array("column1" => "2-1", "column2" => "2-2"))');
		}

		if(isset($postedData['exportFormat']))
		{
			$this->Excel->create($postedData['name'], function($Excel) use ($rows, $postedData)
			{
				foreach (json_decode($postedData['fileProperties'], true) as $key => $value)
				{
					$method = 'set' . ucfirst($key);

					$Excel->$method($value);
				}

				$Excel->sheet($postedData['name'], function($Sheet) use ($rows, $postedData)
				{
					$columnCounter = 0;

					foreach (json_decode($postedData['model'], true) as $a => $model)
					{
						if(isset($model['hidden']) && $model['hidden'] !== true)
						{
							$columnCounter++;
						}

						if(isset($model['hidedlg']) && $model['hidedlg'] === true)
						{
							continue;
						}

						if(empty($postedData['pivot']))
						{
							foreach ($rows as $b => &$row)
							{
								if(isset($model['hidden']) && $model['hidden'] === true)
								{
									unset($row[$model['name']]);
								}
								else
								{
									if(isset($model['label']))
									{
										$row = array_add($row, $model['label'], $row[$model['name']]);
										unset($row[$model['name']]);
									}
									else
									{
										$temp = $row[$model['name']];
										unset($row[$model['name']]);
										$row = array_add($row, $model['name'], $temp);
									}
								}
							}
						}

						if(isset($model['align']) && isset($model['hidden']) && $model['hidden'] !== true)
						{
							$Sheet->getStyle($this->num_to_letter($columnCounter, true))->getAlignment()->applyFromArray(
									array('horizontal' => $model['align'])
							);
						}
					}

					foreach (json_decode($postedData['sheetProperties'], true) as $key => $value)
					{
						$method = 'set' . ucfirst($key);

						$Sheet->$method($value);
					}

					$Sheet->fromArray($rows);

					$Sheet->row(1, function($Row) {
					  $Row->setFontWeight('bold');
					});
				});
			})->export($postedData['exportFormat']);
		}
		else
		{
				echo json_encode(array('page' => $page, 'total' => $totalPages, 'records' => $count, 'rows' => $rows));
		}
	}

	/**
	* Takes a number and converts it to a-z,aa-zz,aaa-zzz, etc with uppercase option
	*
	* @access	public
	* @param	int	number to convert
	* @param	bool	upper case the letter on return?
	* @return	string	letters from number input
	*/
	protected function num_to_letter($num, $uppercase = FALSE)
	{
		$num -= 1;

		$letter = 	chr(($num % 26) + 97);
		$letter .= 	(floor($num/26) > 0) ? str_repeat($letter, floor($num/26)) : '';
		return 		($uppercase ? strtoupper($letter) : $letter);
	}
}

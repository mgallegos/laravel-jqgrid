<?php
/**
 * @file
 * JqGrid JSON Encoder.
 *
 * All LaravelJqGrid code is copyright by the original authors and released under the MIT License.
 * See LICENSE.
 */

namespace Mgallegos\LaravelJqgrid\Encoders;

use Mgallegos\LaravelJqgrid\Exceptions\JsonEncodingMaxDepthException;
use Mgallegos\LaravelJqgrid\Exceptions\JsonEncodingStateMismatchException;
use Mgallegos\LaravelJqgrid\Exceptions\JsonEncodingSyntaxErrorException;
use Mgallegos\LaravelJqgrid\Exceptions\JsonEncodingUnexpectedControlCharException;
use Mgallegos\LaravelJqgrid\Exceptions\JsonEncodingUnknownException;
use Mgallegos\LaravelJqgrid\Repositories\RepositoryInterface;
use Maatwebsite\Excel\Excel;
use Carbon\Carbon;
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
	public function encodeRequestedData(RepositoryInterface $Repository,  $postedData, $encodeRowsToUtf8 = false)
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

		if($page == 0)
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

		if(isset($postedData['nodeid']))
		{
			$nodeId = $postedData['nodeid'];
		}
		else
		{
			$nodeId = null;
		}

		if(isset($postedData['n_level']))
		{
			$nodeLevel = $postedData['n_level'];
		}
		else
		{
			$nodeLevel = null;
		}

		if(isset($postedData['exportFormat']))
		{
			$exporting = true;
		}
		else
		{
			$exporting = false;
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
						$filter['data'] = '%' . str_replace(' ', '%', $filter['data']) . '%';
						break;
					case 'cnpg': //contains PostgreSQL
						$filter['op'] = 'ilike';
						$filter['data'] = '%' . str_replace(' ', '%', $filter['data']) . '%';
						break;
					case 'nc': //does not contains
						$filter['op'] = 'not like';
						$filter['data'] = '%' . $filter['data'] . '%';
						break;
					case 'nu': //is null
            $filter['op'] = 'is null';
            $filter['data'] = '';
            break;
      		case 'nn': //is not null
            $filter['op'] = 'is not null';
            $filter['data'] = '';
           	break;
           				case 'btw': //between
						$filter['op'] = 'between';
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
			$rows = $Repository->getRows($limit, $start, $sidx, $sord, $filters['rules'], $nodeId, $nodeLevel, $exporting);

			if($encodeRowsToUtf8)
			{
				$rows = self::utf8ize($rows);
			}

			if($count < count($rows))
			{
				$count = count($rows);
			}
		}
		else
		{
			$rows = json_decode($postedData['pivotRows'], true);
		}

		if(!is_array($rows) || (isset($rows[0]) && !is_array($rows[0])))
		{
			throw new Exception('The method getRows must return an array of arrays, example: array(array("column1"  =>  "1-1", "column2" => "1-2"), array("column1" => "2-1", "column2" => "2-2"))');
		}

		if($exporting)
		{
			$method_name = 'export_to_'.$postedData['exportFormat'];

			if(method_exists($Repository, $method_name) )
			{
				return $Repository->$method_name(
					array_merge(
						['rows'=> $rows],
						['postedData'=> $postedData]
		      )
			 	);
			}

			$this->Excel->create($postedData['name'], function($Excel) use ($rows, $postedData)
			{
				foreach (json_decode($postedData['fileProperties'], true) as $key => $value)
				{
					$method = 'set' . ucfirst($key);

					$Excel->$method($value);
				}

				$Excel->sheet($postedData['name'], function($Sheet) use ($rows, $postedData)
				{
					$groupingView = json_decode($postedData['groupingView'], true);

					$groupHeaders = json_decode($postedData['groupHeaders'], true);

					$columnsPositions = $summaryTypes = $modelLabels = $modelSelectFormattersValues = $modelNumberFormatters = $modelDateFormatters = $numericColumns = array();

					$groupFieldName = '';

					$columnCounter = 0;

					foreach (json_decode($postedData['model'], true) as $a => $model)
					{
						if(!empty($groupingView) && $groupingView['groupField'][0] == $model['name'])
						{
							if(isset($model['hidden']) && $model['hidden'] === true)
							{
								$groupFieldHidden = true;
							}
							else
							{
								$groupFieldHidden = false;
							}

							$groupFieldName = $model['name'];

							if(isset($model['label']))
							{
								$groupFieldLabel = $model['label'];
							}
							else
							{
								$groupFieldLabel = $model['name'];
							}
						}

						if(isset($model['hidden']) && $model['hidden'] !== true)
						{
							$columnCounter++;

							$columnsPositions[$model['name']] = $columnCounter;
						}

						if(isset($model['hidedlg']) && $model['hidedlg'] === true)
						{
							continue;
						}

						if(isset($model['summaryType']))
						{
							$summaryTypes[isset($model['label'])?$model['label']:$model['name']] = $model['summaryType'];
						}

						if($model['hidden'] === false || $model['name'] == $groupFieldName)
						{
							if(isset($model['label']))
							{
								$modelLabels[$model['name']] = $model['label'];
							}
							else
							{
								$modelLabels[$model['name']] = $model['name'];
							}
						}

						if(isset($model['formatter']))
						{
							switch ($model['formatter'])
							{
								case 'select':
									if(isset($model['editoptions']['value']))
									{
										foreach (explode(';', $model['editoptions']['value']) as $index => $value)
										{
											$temp = explode(':', $value);

											$modelSelectFormattersValues[isset($model['label'])?$model['label']:$model['name']][$temp[0]] = $temp[1];
										}
									}

									break;
								case 'integer':
									$modelNumberFormatters[$model['name']] = '0';

									array_push($numericColumns, isset($model['label'])?$model['label']:$model['name']);

									break;
								case 'number':
								case 'currency':
									if(isset($model['formatoptions']['prefix']))
									{
										$prefix = $model['formatoptions']['prefix'];
									}
									else
									{
										$prefix = '';
									}

									$modelNumberFormatters[$model['name']] = '"' . $prefix . '"#,##0.00';

									array_push($numericColumns, isset($model['label'])?$model['label']:$model['name']);

									break;
								case 'date':
									if((isset($model['formatoptions']['srcformat']) || $postedData['srcDateFormat']) && (isset($model['formatoptions']['newformat']) || $postedData['newDateFormat']))
									{
										if(isset($model['formatoptions']['srcformat']))
										{
											$srcformat = $model['formatoptions']['srcformat'];
										}
										else
										{
											$srcformat = $postedData['srcDateFormat'];
										}

										if(isset($model['formatoptions']['newformat']))
										{
											$newformat = $model['formatoptions']['newformat'];
										}
										else
										{
											$newformat = $postedData['newDateFormat'];
										}

										// $modelDateFormatters[$model['name']] = array('srcformat' => $srcformat, 'newformat' => $newformat);
										$modelDateFormatters[isset($model['label'])?$model['label']:$model['name']] = array('srcformat' => $srcformat, 'newformat' => $newformat);
									}

									break;
							}
						}

						if(isset($model['align']) && isset($model['hidden']) && $model['hidden'] !== true)
						{
							$Sheet->getStyle($this->numToLetter($columnCounter, true))->getAlignment()->applyFromArray(
									array('horizontal' => $model['align'])
							);
						}
					}

					if(empty($postedData['pivot']))
					{
						foreach ($rows as $index => &$row)
						{
							$currentRow = array();

							foreach ($modelLabels as $columnName => $value)
							{
								$currentRow[$value] = isset($row[$columnName])?$row[$columnName]:'';
							}

							foreach ($modelSelectFormattersValues as $label => $modelSelectFormatterValue)
							{
								if(isset($currentRow[$label]))
								{
									$currentRow[$label] = isset($modelSelectFormatterValue[$currentRow[$label]])?$modelSelectFormatterValue[$currentRow[$label]]:$currentRow[$label];
								}
							}

							foreach ($modelDateFormatters as $label => $modelDateFormatter)
							{
								if(isset($currentRow[$label]) && !empty($currentRow[$label]))
								{
									$currentRow[$label] = Carbon::createFromFormat($modelDateFormatter['srcformat'], $currentRow[$label])->format($modelDateFormatter['newformat']);
								}
							}

							foreach ($numericColumns as $index => $label)
							{
								if(isset($currentRow[$label]))
								{
									$currentRow[$label] = (float) $currentRow[$label];
								}
							}

							$row = $currentRow;
						}
					}

					foreach (json_decode($postedData['sheetProperties'], true) as $key => $value)
					{
						$method = 'set' . ucfirst($key);

						$Sheet->$method($value);
					}

					$subTotalGroupedRowsNumber = array();

					if(!empty($groupingView))
					{
						$groupedRows = $groupedRowsNumbers = $subTotalGroupedRow = $currentSubTotalGroupedRow = array();

						$rowCounter = 0;

						foreach ($rows as $index => &$row)
						{
							if($rowCounter == 0)
							{
								$currentgroupFieldValue = $row[$groupFieldLabel];

								if($groupFieldHidden)
								{
									unset($row[$groupFieldLabel]);
								}

								$firstColumnName = key($row);

								$groupedRow = $row;

								foreach ($groupedRow as $label => &$cell)
								{
									if($firstColumnName == $label)
									{
										$cell = $currentgroupFieldValue;
									}
									else
									{
										$cell = '';
									}

									$subTotalGroupedRow[$label] = '';
								}

								$currentSubTotalGroupedRow = $subTotalGroupedRow;

								$rowCounter = 2;

								if(!empty($groupHeaders))
								{
									$rowCounter++;
								}

								array_push($groupedRows, $groupedRow);
								array_push($groupedRowsNumbers, $rowCounter);
							}
							else
							{
								if($row[$groupFieldLabel] != $currentgroupFieldValue)
								{
									$currentgroupFieldValue = $groupedRow[$firstColumnName]  = $row[$groupFieldLabel];

									$rowCounter++;

									if(!empty($summaryTypes))
									{
										foreach ($summaryTypes as $column => $summaryType)
										{
											switch ($summaryType) {
												case 'count':
													$currentSubTotalGroupedRow[$column] = "($currentSubTotalGroupedRow[$column]) total";
													break;
											}
										}

										array_push($groupedRows, $currentSubTotalGroupedRow);
										array_push($subTotalGroupedRowsNumber, $rowCounter);

										$currentSubTotalGroupedRow = $subTotalGroupedRow;

										$rowCounter++;
									}

									array_push($groupedRows, $groupedRow);
									array_push($groupedRowsNumbers, $rowCounter);
								}

								if($groupFieldHidden)
								{
									unset($row[$groupFieldLabel]);
								}
							}

							if(!empty($summaryTypes))
							{
								foreach ($summaryTypes as $label => $summaryType)
								{
									switch ($summaryType) {
										case 'sum':
											if(empty($currentSubTotalGroupedRow[$label]))
											{
												$currentSubTotalGroupedRow[$label] = $row[$label] + 0;
											}
											else
											{
												$currentSubTotalGroupedRow[$label] += $row[$label];
											}

											break;
										case 'count':
											if($currentSubTotalGroupedRow[$label] != 0 && empty($currentSubTotalGroupedRow[$label]))
											{
												$currentSubTotalGroupedRow[$label] = 0;
											}
											else
											{
												$currentSubTotalGroupedRow[$label] ++;
											}

											break;
									}
								}
							}

							array_push($groupedRows, $row);

							$rowCounter++;
						}

						if(!empty($summaryTypes))
						{
							foreach ($summaryTypes as $column => $summaryType)
							{
								switch ($summaryType) {
									case 'count':
										$currentSubTotalGroupedRow[$column] = "($currentSubTotalGroupedRow[$column]) total";
										break;
								}
							}

							array_push($groupedRows, $currentSubTotalGroupedRow);
							array_push($subTotalGroupedRowsNumber, ++$rowCounter);
						}

						$lastCellLetter = $this->numToLetter($columnCounter, true);

						foreach ($groupedRowsNumbers as $index => $groupedRowsNumber)
						{
							$Sheet->mergeCells("A$groupedRowsNumber:$lastCellLetter$groupedRowsNumber");
						}

						$rows = $groupedRows;
					}

					$columnFormats = array();

					foreach ($modelNumberFormatters as $columnName => $format)
					{
						if(isset($columnsPositions[$columnName]))
						{
							$columnFormats[$this->numToLetter($columnsPositions[$columnName], true)] = $format;
						}
					}

					$Sheet->setColumnFormat($columnFormats);

					// var_dump($rows);die();
					$footerRow = json_decode($postedData['fotterRow'], true);

					if(!empty($footerRow))
					{
						array_push($rows, $footerRow);
					}

					$headers = $firstHeader = array();

					if(empty($groupHeaders))
					{
						$Sheet->fromArray($rows, null, 'A1', true, true);
					}
					else
					{
						for ($i = 0; $i < count($columnsPositions); $i++)
						{
							$firstHeader[$i] = '';
						}

						foreach ($groupHeaders as $index => $groupHeader)
						{
							$firstHeader[$columnsPositions[$groupHeader['startColumnName']] - 1] = $groupHeader['titleText'];

							$Sheet->mergeCells($this->numToLetter($columnsPositions[$groupHeader['startColumnName']], true) . '1:' . $this->numToLetter($columnsPositions[$groupHeader['startColumnName']] + $groupHeader['numberOfColumns'] - 1, true) . '1');
						}

						array_push($headers, $firstHeader);
						array_push($headers, array_keys($rows[0]));

						$Sheet->fromArray(array_merge($headers, $rows), null, 'A1', true, false);

						$Sheet->row(2, function($Row)
						{
						  $Row->setFontWeight('bold');
						});
					}

					$Sheet->row(1, function($Row)
					{
					  $Row->setFontWeight('bold');
					});

					foreach ($subTotalGroupedRowsNumber as $index => $number)
					{
						$Sheet->row($number, function($Row)
						{
						  $Row->setFontWeight('bold');
						});
					}

					if(!empty($footerRow))
					{
						if(!empty($headers))
						{
							$footerRowNumber = count($rows) + count($headers);
						}
						else
						{
							$footerRowNumber = count($rows) + 1;
						}

						$Sheet->row($footerRowNumber, function($Row)
						{
							$Row->setFontWeight('bold');
						});
					}

				});
			})->export($postedData['exportFormat']);
		}
		else
		{
				echo $this->safe_json_encode(array('page' => $page, 'total' => $totalPages, 'records' => $count, 'rows' => $rows));
		}
	}

	/**
	* Takes a number and converts it to a-z,aa-zz,aaa-zzz, etc with uppercase option
	*
	* @access	protected
	* @param	int	number to convert
	* @param	bool	upper case the letter on return?
	* @return	string	letters from number input
	*/
	protected function numToLetter($num, $uppercase = FALSE)
	{
		$num -= 1;

		$letter = 	chr(($num % 26) + 97);
		$letter .= 	(floor($num/26) > 0) ? str_repeat($letter, floor($num/26)) : '';
		return 		($uppercase ? strtoupper($letter) : $letter);
	}

    /**
     * Safe JSON_ENCODE function that tries to deal with UTF8 chars or throws a valid exception.
     *
     * Lifted from http://stackoverflow.com/questions/10199017/how-to-solve-json-error-utf8-error-in-php-json-decode
     * Based on: http://php.net/manual/en/function.json-last-error.php#115980
     * @param $value
     * @return string
     */
    protected function safe_json_encode($value){
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            $encoded = json_encode($value, JSON_PRETTY_PRINT);
        } else {
            $encoded = json_encode($value);
        }
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_DEPTH:
                throw new JsonEncodingMaxDepthException('Maximum stack depth exceeded');
            case JSON_ERROR_STATE_MISMATCH:
                throw new JsonEncodingStateMismatchException('Underflow or the modes mismatch');
            case JSON_ERROR_CTRL_CHAR:
                throw new JsonEncodingUnexpectedControlCharException('Unexpected control character found');
            case JSON_ERROR_SYNTAX:
                throw new JsonEncodingSyntaxErrorException('Syntax error, malformed JSON');
            case JSON_ERROR_UTF8:
                $clean = self::utf8ize($value);
                return self::safe_json_encode($clean);
            default:
                throw new JsonEncodingUnknownException('Unknown error');

        }
    }

    /**
     * Clean the array passed in from UTF8 chars.
     *
     * Lifted from http://stackoverflow.com/questions/10199017/how-to-solve-json-error-utf8-error-in-php-json-decode
     * Based on: http://php.net/manual/en/function.json-last-error.php#115980
     *
     * @param $mixed
     * @return array|string
     */
    protected function utf8ize($mixed) {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = self::utf8ize($value);
            }
        } else if (is_string ($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }

}

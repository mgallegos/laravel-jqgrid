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

class JqGridJsonEncoder implements RequestedDataInterface {
	
	/**
	 * Encode in a jqGrid compatible data format the data requested by a grid.
	 *
	 * @param RepositoryInterface $dataRepository
	 *	An implementation of the RepositoryInterface
	 * @param  array $postedData
	 *	All jqGrid posted dRata
	 * @return string
	 *	String of a jqGrid compatible data format: xml, json, jsonp, array, xmlstring, jsonstring.
	 */
	public function encodeRequestedData(RepositoryInterface $Repository,  $postedData)
	{
		$page = $postedData['page']; // get the requested page
		$limit = $postedData['rows']; // get how many rows we want to have into the grid
		$sidx = $postedData['sidx']; // get index row - i.e. user click to sort
		$sord = $postedData['sord']; // get the direction
		
		if(isset($postedData['filters']))
		{
			$filters = json_decode($postedData['filters'], true);
		}
	
		if(!$sidx || empty($sidx))
		{
			$sidx = null;
			$sord = null;
		}
			
		if(isset($postedData['_search']) && $postedData['_search']=="true" && $postedData['filters'])
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
		
		$rows = $Repository->getRows($limit, $start, $sidx, $sord, $filters['rules']);
		
		if(!is_array($rows) || !is_array($rows[0]))
		{
			throw new Exception("The method getTotalNumberOfRows must return an array of arrays, example: array(array('row 1 col 1','row 1 col 2'), array('row 2 col 1','row 2 col 2'))");
		}
				
		echo json_encode(array('page'=>$page, 'total'=>$totalPages, 'records'=>$count, 'rows'=>$rows));				
	}	
}
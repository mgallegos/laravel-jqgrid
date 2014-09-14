<?php
/**
 * @file
 * Property Validator Interface
 *
 * All LaravelJqGrid code is copyright by the original authors and released under the MIT License.
 * See LICENSE.
 */

namespace Mgallegos\LaravelJqgrid\Renders\Validations\ColModel;

use Mgallegos\LaravelJqgrid\Renders\Validations\PropertyValidatorInterface;

class NameValidation implements PropertyValidatorInterface {
	
	/**
	 * Validate a JqGrid property, an exception will be thrown in case the property does not pass the validation.
	 *
	 * @param  array $properties
	 * 	Data representing the column properties
	 * @return void
	 * 	
	 */
	public function validate($properties)
	{				
		if (isset($properties['name']) && in_array($properties['name'], array("subgrid", "cb", "rn")))
		{
			throw new Exception("The reserved words subgrid, cb and rn cannot be used as column names.");
		}
		
		
	}
	
}
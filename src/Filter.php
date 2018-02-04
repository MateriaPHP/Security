<?php

namespace Materia\Security;

/**
 * Input filter interface
 *
 * @package	Materia.Security
 * @author	Filippo Bovo
 * @link	https://lab.alchemica.io/projects/materia/
 **/

interface Filter {

	/**
	 * Sanitize input
	 *
	 * @param	string	$input
	 * @return	string
	 **/
	public function sanitize( string $input, string $encoding = 'UTF-8' ) : string;

}

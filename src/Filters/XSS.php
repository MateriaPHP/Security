<?php

namespace Materia\Security\Filters;

/**
 * XSS Input filter
 *
 * @package	Materia.Security.Filters
 * @author	Filippo Bovo
 * @link	https://lab.alchemica.io/projects/materia/
 **/

class XSS implements \Materia\Security\Filter {

	protected $_patterns = [
		// Fix &entity\n
		'!(&#0+[0-9]+)!' => '$1;',
		'/(&#*\w+)[\x00-\x20]+;/u' => '$1;>',
		'/(&#x*[0-9A-F]+);*/iu' => '$1;',
		// Any attribute starting with "on" or xml name space
		'#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu' => '$1>',
		// javascript: and VB script: protocols
		'#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu' => '$1=$2nojavascript...',
		'#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu' => '$1=$2novbscript...',
		'#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u' => '$1=$2nomozbinding...',
		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		'#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i' => '$1>',
		'#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu' => '$1>',
		// Namespace elements
		'#</*\w+:\w[^>]*+>#i' => '',
		// Unwanted tags
		'#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i' => ''
	];

	/**
	 * @see	\Materia\Framework\Security\Filter::sanitize()
	 **/
	public function sanitize( string $input, string $encoding = 'UTF-8' ) : string {

		$input = html_entity_decode( $input, ENT_NOQUOTES, $encoding );

		foreach ( $this->_patterns as $pattern => $replacement ) {

			$input = preg_replace( $pattern, $replacement, $input );

		}

		return $input;

	}

}

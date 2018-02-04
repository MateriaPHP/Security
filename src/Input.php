<?php

namespace Materia\Security;

/**
 * Input filter class
 *
 * @package	Materia.Security
 * @author	Filippo Bovo
 * @link	https://lab.alchemica.io/projects/materia/
 **/

class Input extends \ArrayObject {

	protected $_cache   = [];
	protected $_filters = [];

	/**
	 * Constructor
	 *
	 * @param	mixed	$data
	 * @throws	\InvalidArgumentException
	 **/
	public function __construct( $data = [] ) {

		foreach ( $data as $key => &$value ) {

			if ( is_array( $value ) || ( $value instanceof \Traversable ) ) {

				$value = new static( $value );

			}

		}

		parent::__construct( $data, \ArrayObject::ARRAY_AS_PROPS );

	}

	/**
	 * @see	\ArrayObject::offsetGet()
	 **/
	public function offsetGet( $offset ) {

		// Apply filters
		if ( !isset( $this->_cache[$offset] ) && isset( $this->_filters[$offset] ) ) {

			// if ( $this->offsetExists( $offset ) )
			foreach ($this->_filters[$offset] as &$filter ) {

				parent::offsetSet( $offset, $filter( parent::offsetGet( $offset ) ) );

			}

			$this->_cache[$offset] = TRUE;

			// Garbage collect
			unset( $this->_filters[$offset] );

		}

		return isset( $this->_cache[$offset] ) ? parent::offsetGet( $offset ) : NULL;

	}

	/**
	 * @see	\ArrayObject::offsetUnset()
	 **/
	public function offsetUnset( $offset ) {

		if ( isset( $this->_cache[$offset] ) ) {

			unset( $this->_cache[$offset] );

		}

		if ( isset( $this->_filters[$offset] ) ) {

			unset( $this->_filters[$offset] );

		}

		return parent::offsetUnset( $offset );

	}

	/**
	 * Set input filter
	 *
	 * @param	string		$offset
	 * @param	callable	$callback
	 * @return	self
	 **/
	public function setFilter( string $offset, callable $callback ) : self {

		if( isset( $this->_cache[$offset] ) ) {

			unset( $this->_cache[$offset] );

		}

		$this->_filters[$offset][] = $callback;

		return $this;

	}

}

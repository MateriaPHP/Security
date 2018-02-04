<?php

namespace Materia\Security;

/**
 * Cookie filter interface
 *
 * @package	Materia.Security
 * @author	Filippo Bovo
 * @link	https://lab.alchemica.io/projects/materia/
 **/

class Cookie extends Input {

	protected $_domain    = NULL;
	protected $_expire    = 0;
	protected $_path      = '/';
	protected $_secure    = FALSE;
	protected $_http_only = TRUE;

	/**
	 * Constructor
	 *
	 * @param	mixed	$data
	 * @throws	\InvalidArgumentException
	 **/
	public function __construct( $data = [] ) {

		parent::__construct( $data, \ArrayObject::ARRAY_AS_PROPS );

	}

	/**
	 * Gets the domain that the cookie is available to
	 *
	 * @return	string
	 **/
	public function getDomain() {

		return $this->_domain;

	}

	/**
	 * Set the domain that the cookie is available to
	 *
	 * @param	string	$domain
	 * @return	self
	 **/
	public function setDomain( string $domain = NULL ) : self {

		$this->_domain = $domain;

		return $this;

	}

	/**
	 * Gets the time the cookie expires
	 *
	 * @return	int
	 **/
	public function getExpire() {

		return $this->_expire;

	}

	/**
	 * Set the time the cookie expires.
	 *
	 * @param 	mixed	$expire				if int, the TTL of this cookie, 0 if session cookie
	 * @return	self
	 * @throws	\InvalidArgumentException
	 **/
	public function setExpire( $expire ) : self {

		// Convert expiration time to a Unix timestamp
		if ( $expire instanceof \DateTime ) {

			$expire = $expire->format('U');

		}
		else if ( !is_numeric( $expire ) || !is_int( $expire ) ) {

			throw new \InvalidArgumentException( sprintf(
				"The cookie expiration time is not valid with type '%s'",
				gettype($expire)
			));

		}
		else if ( $expire > 0 ) {

			$expire = time() + $expire;

		}

		$this->_expire = $expire;

		return $this;

	}

	/**
	 * Whether this cookie is about to be cleared
	 *
	 * @return	bool
	 **/
	public function isExpired() : bool {

		return $this->_expire < time();

	}

	/**
	 * Gets the path on the server in which the cookie will be available on
	 *
	 * @return	string
	 **/
	public function getPath() {

		return $this->_path;

	}

	/**
	 * Set the path on the server in which the cookie will be available on.
	 *
	 * @param	string	$path
	 * @return	self
	 **/
	public function setPath( string $path ) : self {

		$this->_path = $path;

		return $this;

	}

	/**
	 * Checks whether the cookie should only be transmitted over a secure HTTPS connection from the client
	 *
	 * @return	bool
	 **/
	public function isSecure() {

		return $this->_secure;

	}

	/**
	 * Set if the cookie should only be transmitted over a secure HTTPS connection from the client
	 *
	 * @param	bool	$secure
	 * @return	self
	 */
	public function setSecure( bool $secure ) : self {

		$this->_secure = $secure;

		return $this;

	}

	/**
	 * Checks whether the cookie will be made accessible only through the HTTP protocol
	 *
	 * @return	bool
	 **/
	public function isHTTPOnly() : bool {

		return $this->_http_only;

	}

	/**
	 * Set if the cookie will be made accessible only through the HTTP protocol
	 *
	 * @param	bool	$http_only
	 * @return	self
	 **/
	public function setHTTPOnly( bool $http_only ) : self {

		$this->_http_only = $http_only;

		return $this;

	}

}

<?php

namespace Materia\Security;

/**
 * Test input filtering class
 *
 * @package Materia.Test
 * @author  Filippo Bovo
 * @link    https://lab.alchemica.io/projects/materia/
 **/

class InputTest extends \PHPUnit\Framework\TestCase {

	public function setUp() {

		// Setting up the router
		$_GET = new Input( [ 'test' => 'lorem ipsum', 'bad' => 'article.php?title=<meta%20http-equiv="refresh"%20content="0;">' ] );

	}

	public function tearDown() {}

	public function testFiltering() {

		$this->assertEquals( NULL, $_GET['test'] );

        $_GET->setFilter( 'test', function( $input ) { return $input; } );

		$this->assertEquals( 'lorem ipsum', $_GET['test'] );

        $_GET->setFilter( 'bad', function( $input ) { return ( new Filters\XSS() )->sanitize( $input ); } );

		$this->assertEquals( 'article.php?title=', $_GET['bad'] );

	}

}


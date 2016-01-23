<?php

namespace RedUNIT\Postgres;

use RedUNIT\Postgres as Postgres;
use RedBeanPHP\Facade as R;

/**
 * Setget
 *
 * @file    RedUNIT/Postgres/Setget.php
 * @desc    Tests whether values are correctly stored.
 * @author  Gabor de Mooij and the RedBeanPHP Community
 * @license New BSD/GPLv2
 *
 * (c) G.J.G.T. (Gabor) de Mooij and the RedBeanPHP Community.
 * This source file is subject to the New BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
class Setget extends Postgres
{
	/**
	 * Test support for Postgres JSON columns.
	 */
	public function testJSON()
	{
		asrt( setget( json_encode( array( 'can' => 'store json?' ) ) ), json_encode( array( 'can' => 'store json?' ) ) );
		R::nuke();
		$bean = R::dispense( 'blob' );
		$bean->content = json_encode( array( 'hello' => 'world' ) );
		R::store( $bean );
		$columns = R::inspect( 'blob' );
		asrt( $columns['content'], 'json' );
		$bean = R::load( 'blob', $bean->id );
		asrt( $bean->content, json_encode( array( 'hello' => 'world' ) ) );
		$data = R::getCell( 'SELECT json_extract_path(content, \'hello\') FROM blob LIMIT 1' );
		asrt( $data, '"world"' );
	}

	/**
	 * Test numbers.
	 *
	 * @return void
	 */
	public function testNumbers()
	{
		asrt( setget( "-1" ), "-1" );
		asrt( setget( -1 ), "-1" );

		asrt( setget( "1.0" ), "1" );
		asrt( setget( 1.0 ), "1" );

		asrt( setget( "-0.25" ), "-0.25" );
		asrt( setget( -0.25 ), "-0.25" );

		asrt( setget( "3.20" ), "3.20" );
		asrt( setget( "13.20" ), "13.20" );
		asrt( setget( "134.20" ), "134.20" );
		asrt( setget( 3.21 ), '3.21' );

		asrt( setget( "0.12345678" ), "0.12345678" );
		asrt( setget( 0.12345678 ), "0.12345678" );

		asrt( setget( "-0.12345678" ), "-0.12345678" );
		asrt( setget( -0.12345678 ), "-0.12345678" );

		asrt( setget( "2147483647" ), "2147483647" );
		asrt( setget( 2147483647 ), "2147483647" );

		asrt( setget( -2147483647 ), "-2147483647" );
		asrt( setget( "-2147483647" ), "-2147483647" );

		asrt( setget( "2147483648" ), "2147483648" );
		asrt( setget( "-2147483648" ), "-2147483648" );

		asrt( setget( "199936710040730" ), "199936710040730" );
		asrt( setget( "-199936710040730" ), "-199936710040730" );
	}

	/**
	 * Test dates.
	 *
	 * @return void
	 */
	public function testDates()
	{
		asrt( setget( "2010-10-11" ), "2010-10-11" );

		asrt( setget( "2010-10-11 12:10" ), "2010-10-11 12:10" );

		asrt( setget( "2010-10-11 12:10:11" ), "2010-10-11 12:10:11" );

		asrt( setget( "x2010-10-11 12:10:11" ), "x2010-10-11 12:10:11" );
	}

	/**
	 * Test strings.
	 *
	 * @return void
	 */
	public function testStrings()
	{
		asrt( setget( "a" ), "a" );

		asrt( setget( "." ), "." );

		asrt( setget( "\"" ), "\"" );

		asrt( setget( "just some text" ), "just some text" );
	}

	/**
	 * Test booleans.
	 *
	 * @return void
	 */
	public function testBool()
	{
		asrt( setget( TRUE ), "1" );
		asrt( setget( FALSE ), "0" );

		asrt( setget( "TRUE" ), "TRUE" );
		asrt( setget( "FALSE" ), "FALSE" );
	}

	/**
	 * Test NULL.
	 *
	 * @return void
	 */
	public function testNull()
	{
		asrt( setget( "NULL" ), "NULL" );
		asrt( setget( "NULL" ), "NULL" );

		asrt( setget( NULL ), NULL );

		asrt( ( setget( 0 ) == 0 ), TRUE );
		asrt( ( setget( 1 ) == 1 ), TRUE );

		asrt( ( setget( TRUE ) == TRUE ), TRUE );
		asrt( ( setget( FALSE ) == FALSE ), TRUE );
	}
}

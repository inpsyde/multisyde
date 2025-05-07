<?php
/**
 * Generic Test Class
 *
 * @package multisite-improvements-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultisiteImprovementsUnitTests;

use Mockery;
use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

/**
 * Generic unit test case for Multisite Improvements.
 */
class UnitTestCase extends TestCase {

	/**
	 * Set up the test case.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		Monkey\setUp();

		Functions\when( '__' )->returnArg();
		Functions\when( 'esc_attr' )->returnArg();
		Functions\when( 'esc_html' )->returnArg();
		Functions\when( 'esc_html__' )->returnArg();
		Functions\when( 'esc_url' )->returnArg();
		Functions\when( 'wp_kses' )->returnArg();
		Functions\when( 'wp_kses_post' )->returnArg();
		Functions\when( 'sanitize_text_field' )->returnArg();
		Functions\when( 'wp_kses_allowed_html' )->justReturn( array() );
	}

	/**
	 * Tear down the test case.
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		Mockery::close();
		Monkey\tearDown();

		parent::tearDown();
	}
}

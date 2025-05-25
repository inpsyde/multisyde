<?php
/**
 * Generic Test Class
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultisydeUnitTests;

use Brain\Monkey;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * Class UnitTestCase
 */
class UnitTestCase extends TestCase {

	use MockeryPHPUnitIntegration;

	/**
	 * Set up the test case.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Monkey\Functions\stubTranslationFunctions();
		Monkey\Functions\stubEscapeFunctions();
	}

	/**
	 * Tear down the test case.
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		Monkey\tearDown();
		Mockery::close();

		parent::tearDown();
	}
}

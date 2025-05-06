<?php

namespace Syde\MultisiteImprovementsTests;

use Syde\MultisiteImprovements\Loader;

class TestLoader extends UnitTestCase {

	public function test_init(): void {
		$this->expectNotToPerformAssertions();

		Loader::init();
	}
}

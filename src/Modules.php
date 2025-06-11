<?php
/**
 * The Modules class provides a way to load and manage all the available features.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde;

/**
 * Class Modules
 */
class Modules {

	/**
	 * Configuration array that maps loadable features to their shareable information classes.
	 *
	 * @var array<class-string<LoadableFeature>, class-string<ShareableInformation>> $config
	 */
	private array $config = array();

	/**
	 * Constructor for the Modules class.
	 *
	 * This constructor loads the configuration from the specified path.
	 */
	public function __construct() {
		$this->config = require __DIR__ . '/../modules/config.php';
	}

	/**
	 * Loads the loadable improvements
	 *
	 * @return self
	 */
	public function load(): self {
		array_walk( $this->config, fn ( $info, $module ) => $module::init() );

		return $this;
	}

	/**
	 * Gets the list of all presentable feature classes.
	 *
	 * @return array<class-string<LoadableFeature>, class-string<ShareableInformation>>
	 */
	public function get_presentable_features(): array {
		return $this->config;
	}
}

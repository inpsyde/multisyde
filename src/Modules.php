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
	private array $config;

	/**
	 * Initializes the Modules class with the configuration from the modules config file.
	 *
	 * @return self
	 */
	public static function init(): self {
		$config = apply_filters( 'syde_multisyde_modules_init', require __DIR__ . '/../modules/config.php' );

		return new self( $config );
	}

	/**
	 * Constructor for the Modules class.
	 *
	 * @param array<class-string<LoadableFeature>, class-string<ShareableInformation>> $config Configuration array that maps loadable features to their shareable information classes.
	 */
	public function __construct( array $config ) {
		$this->config = array_filter( $config, array( __CLASS__, 'filter' ), ARRAY_FILTER_USE_BOTH );
	}

	/**
	 * Filters the configuration to ensure that only valid loadable features and shareable information classes are included.
	 *
	 * @param mixed $info Shareable information class.
	 * @param mixed $module Loadable feature class.
	 *
	 * @return bool
	 */
	public static function filter( $info, $module ): bool {
		return is_string( $module ) && is_subclass_of( $module, LoadableFeature::class ) && is_string( $info ) && is_subclass_of( $info, ShareableInformation::class );
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
	public function features(): array {
		return $this->config;
	}
}

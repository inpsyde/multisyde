<?php
/**
 * Throwaway mu-plugin for the rest-sites-blackbox skill. Never commit into wp-content.
 *
 * @package multisyde
 */

add_action(
	'init',
	static function () {
		$keys = array(
			'rest_test_string'  => array( 'type' => 'string' ),
			'rest_test_boolean' => array( 'type' => 'boolean' ),
			'rest_test_integer' => array( 'type' => 'integer' ),
			'rest_test_array'   => array(
				'type'         => 'array',
				'show_in_rest' => array( 'schema' => array( 'items' => array( 'type' => 'string' ) ) ),
			),
			'rest_test_multi'   => array(
				'type'   => 'string',
				'single' => false,
			),
			'rest_test_hidden'  => array(
				'type'         => 'string',
				'show_in_rest' => false,
			),
		);

		foreach ( $keys as $key => $args ) {
			register_meta(
				'blog',
				$key,
				$args + array(
					'single'       => true,
					'show_in_rest' => true,
				)
			);
		}
	}
);

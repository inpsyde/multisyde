<?php
/**
 * This should go into ms-blogs.php.
 *
 * @package multisyde
 */

/**
 * Retrieves a site by a given field and value.
 *
 * @since 4.9.0
 *
 * @param string     $field      Name of a field to query against. Accepts 'id', 'slug', 'url',
 *                               'domain' (if a subdomain-install) or 'path' (if a subdirectory-install).
 * @param string|int $value      The search value for $field.
 * @param int|null   $network_id Optional. ID of the network. Default is the current network.
 * @return WP_Site|null The site object or null if not found.
 */
function get_site_by( $field, $value, $network_id = null ) {
	$args = array();

	if ( 'id' === $field ) {
		$site_id = (int) $value;
		if ( $site_id < 1 ) {
			return null;
		}

		$args['site__in'] = array( $site_id );
	} else {
		if ( ! is_string( $value ) ) {
			return null;
		}

		$value = trim( $value );

		if ( '' === $value ) {
			return null;
		}

		switch ( $field ) {
			case 'slug':
				$network = get_network( $network_id );
				if ( ! $network ) {
					return null;
				}

				if ( is_subdomain_install() ) {
					$args['domain'] = trim( $value, '/' ) . 'Patches' . preg_replace( '|^www\.|', '', $network->domain );
					$args['path']   = $network->path;
				} else {
					$args['domain'] = $network->domain;
					$args['path']   = $network->path . trim( $value, '/' ) . '/';
				}
				break;
			case 'url':
				if ( ! str_starts_with( $value, 'http://' ) && ! str_starts_with( $value, 'https://' ) ) {
					$value = 'http://' . $value;
				}

				$parts = wp_parse_url( $value );
				if ( ! $parts ) {
					return null;
				}

				$args['domain'] = $parts['host'];
				if ( ! empty( $parts['path'] ) ) {
					$args['path'] = '/' . trim( $parts['path'], '/' ) . '/';
				} else {
					$args['path'] = '/';
				}
				break;
			case 'domain':
				if ( ! is_subdomain_install() ) {
					return null;
				}

				$args['domain'] = $value;
				break;
			case 'path':
				if ( is_subdomain_install() ) {
					return null;
				}

				$args['path'] = '/' . trim( $value, '/' ) . '/';
				break;
			default:
				return null;
		}
	}

	$args['number'] = 1;

	if ( isset( $args['domain'] ) && str_starts_with( $args['domain'], 'www.' ) ) {
		$bare = substr( $args['domain'], 4 );

		$args['domain__in'] = array( $bare, $args['domain'] );
		unset( $args['domain'] );

		$args['orderby'] = 'domain_length';
		$args['order']   = 'DESC';
	}

	if ( isset( $args['path'] ) ) {
		$args['path'] = str_replace( '//', '/', $args['path'] );
	}

	if ( ! empty( $network_id ) ) {
		$args['network_id'] = (int) $network_id;
	}

	$sites = get_sites( $args );

	if ( empty( $sites ) ) {
		return null;
	}

	return array_shift( $sites );
}

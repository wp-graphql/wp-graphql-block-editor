<?php
/**
 * Plugin Name: WPGraphQL Block Editor
 * Author: Jason Bahl, WPGraphQL
 * Author URI: http://www.wpgraphql.com
 * Plugin URI: https://github.com/wp-graphql/wp-graphql-block-editor
 * Github Plugin URI: https://github.com/wp-graphql/wp-graphql-block-editor
 * Description: Experimental plugin to work toward compatiblity between the WordPress Gutenberg Block Editor and WPGraphQL, based on Server Side registration of Gutenberg Blocks
 * Version: 0.0.1
 * Text Domain: wp-graphql-block-editor
 * Domain Path: /languages/
 * Requires at least: 5.4
 * Requires PHP: 7.1
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

use WPGraphQL\Model\Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPGraphQLBlockEditor' ) ) {
	require_once __DIR__ . '/src/WPGraphQLBlockEditor.php';
}

if ( ! function_exists( 'graphql_block_editor_init' ) ) {
	/**
	 * Function that instantiates the plugins main class
	 *
	 * @return object
	 */
	function graphql_block_editor_init() {
		/**
		 * Return an instance of the action
		 */
		return \WPGraphQLBlockEditor::instance();
	}
}

add_action( 'plugins_loaded', 'graphql_block_editor_init', 15 );

add_filter( 'wp_insert_post_data', function( array $data, array $post ) {

	$restful_post_types = get_post_types([ 'show_in_rest' => true ]);

	if ( empty( $restful_post_types ) || ! is_array( $restful_post_types ) ) {
		return $data;
	}

	$gutenberg_post_types = [];

	foreach ( $restful_post_types as $restful_post_type ) {
		if ( post_type_supports( $restful_post_type, 'editor' ) ) {
			$gutenberg_post_types[] = $restful_post_type;
		}
	}

	if ( empty( $gutenberg_post_types ) ) {
		return $data;
	}

	if ( ! isset( $post['post_type'] ) || ! in_array( $post['post_type'], $gutenberg_post_types, true ) ) {
		return $data;
	}

	if ( isset( $data['post_content'] ) ) {
		$content = $data['post_content'];
	}

	if ( empty( $content ) ) {
		return $data;
	}

	// Parse the blocks from HTML comments to an array of blocks
	$parsed_blocks = parse_blocks( $content );

	if ( empty( $parsed_blocks ) ) {
		return $data;
	}

	// Filter out blocks that have no name
	$parsed_blocks = array_filter( $parsed_blocks, function( $parsed_block, $k ) {
		return isset( $parsed_block['blockName'] ) && ! empty( $parsed_block['blockName'] );
	}, ARRAY_FILTER_USE_BOTH );

	$parsed_blocks = array_map( function( $parsed_block ) {
		if ( ! isset( $parsed_block['nodeId'] ) ) {
			$parsed_block['nodeId'] = uniqid();
		}
//		wp_send_json( $parsed_block );
		return $parsed_block;
	}, $parsed_blocks );

//	wp_send_json( serialize_blocks( $parsed_blocks ) );

	$data['post_content'] = serialize_blocks( $parsed_blocks );

//	wp_send_json( $data );

	return $data;

}, 10, 2 );

add_filter('register_block_type_args', function( $args, $block_name ) {

	$args['attributes']['nodeId'] = [
		'type' => "string",
		'default' => null,
	];

	return $args;

}, 10, 2 );


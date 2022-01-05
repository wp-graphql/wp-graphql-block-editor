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

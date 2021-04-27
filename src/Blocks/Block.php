<?php

namespace WPGraphQL\BlockEditor\Blocks;

use GraphQL\Type\Definition\ResolveInfo;
use WP_Block_Type;
use WPGraphQL\AppContext;
use WPGraphQL\BlockEditor\Registry\Registry;
use WPGraphQL\Utils\Utils;

class Block {

	/**
	 * @var WP_Block_Type
	 */
	protected $block;

	/**
	 * @var string
	 */
	protected $type_name;

	/**
	 * @var Registry
	 */
	protected $block_registry;

	/**
	 * Block constructor.
	 *
	 * @param WP_Block_Type $block
	 * @param Registry      $block_registry
	 */
	public function __construct( WP_Block_Type $block, Registry $block_registry ) {

		$this->block          = $block;
		$this->block_registry = $block_registry;

		// Format the type name for showing in the GraphQL Schema
		// @todo: WPGraphQL utility function should handle removing the '/' by default.
		$type_name       = lcfirst( ucwords( $block->name, '/' ) );
		$type_name = preg_replace( '/\//', '', lcfirst( ucwords( $type_name, '/' ) ) );
		$type_name = Utils::format_type_name( $type_name );
		$this->type_name = Utils::format_type_name( $type_name );

		$this->register_fields();
		$this->register_type();

	}

	/**
	 * Register fields to the Block
	 *
	 * @return void
	 */
	public function register_fields() {

	}

	/**
	 * Register the Type for the block
	 *
	 * @return void
	 */
	public function register_type() {

		/**
		 * Register the Block Object Type to the Schema
		 */
		register_graphql_object_type( $this->type_name, [
			'description' => __( 'A block used for editing the site', 'wp-graphql-block-editor' ),
			'interfaces'  => [ 'EditorBlock' ],
			'fields'      => [
				'name' => [
					'type'        => [ 'non_null' => 'String' ],
					'description' => __( 'The name of the block', 'wp-graphql-block-editor' ),
					'resolve'     => function( $block, array $args, AppContext $context, ResolveInfo $info ) {
						return $this->resolve( $block, $args, $context, $info );
					}
				],
			]
		] );

	}

	public function resolve( $block, array $args, AppContext $context, ResolveInfo $info ) {
//						wp_send_json( $block );
//						wp_send_json( $context->config['registered_editor_blocks'] );
//						wp_send_json( wp_list_pluck( $context->config['registered_editor_blocks'], "render_callback" ) );
		return isset( $block['blockName'] ) ? $block['blockName'] : '';
	}

}

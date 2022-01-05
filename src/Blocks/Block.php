<?php

namespace WPGraphQL\BlockEditor\Blocks;

use DOMDocument;
use GraphQL\Type\Definition\ResolveInfo;
use WP_Block_Type;
use WPGraphQL\AppContext;
use WPGraphQL\BlockEditor\Registry\Registry;
use WPGraphQL\Utils\Utils;

/**
 * Class Block
 *
 * Handles mapping a WP_Block_Type to the WPGraphQL Schema
 *
 * @package WPGraphQL\BlockEditor\Blocks
 */
class Block {

	/**
	 * The Block Type
	 *
	 * @var WP_Block_Type
	 */
	protected WP_Block_Type $block;

	/**
	 * @var string
	 */
	protected string $type_name;

	/**
	 * @var Registry
	 */
	protected Registry $block_registry;

	/**
	 * The attributes of the block
	 *
	 * @var array|null
	 */
	protected ?array $block_attributes;

	/**
	 * Block constructor.
	 *
	 * @param WP_Block_Type $block
	 * @param Registry      $block_registry
	 */
	public function __construct( WP_Block_Type $block, Registry $block_registry ) {

		$this->block          = $block;
		$this->block_registry = $block_registry;
		$this->block_attributes = $this->block->attributes;

		// Format the type name for showing in the GraphQL Schema
		// @todo: WPGraphQL utility function should handle removing the '/' by default.
		$type_name       = lcfirst( ucwords( $block->name, '/' ) );
		$type_name = preg_replace( '/\//', '', lcfirst( ucwords( $type_name, '/' ) ) );
		$type_name = Utils::format_type_name( $type_name );
		$this->type_name = Utils::format_type_name( $type_name );

		$this->register_block_attributes_as_fields();
		$this->register_fields();
		$this->register_type();

	}

	public function register_block_attributes_as_fields() {

		if ( isset( $this->block->attributes ) ) {

			$block_attribute_fields = [];
			foreach ( $this->block_attributes as $attribute_name => $attribute_config ) {

//				wp_send_json( $attribute_name );'
				$graphql_type = null;

				if ( ! isset( $attribute_config['type'] ) ) {
					return;
				}

				if ( ! isset( $attribute_config['type'] ) ) {
					return;
				}

				switch ( $attribute_config['type'] ) {
					case 'string':
						$graphql_type = 'String';
						break;
					case 'number':
						$graphql_type = 'Int';
						break;
				}


				if ( empty( $graphql_type ) ) {
					continue;
				}


				$block_attribute_fields[ Utils::format_field_name( $attribute_name ) ] = [
					'type' => $graphql_type,
					'description' => __( sprintf( 'The "%1$s" field on the "%2$s" block', $attribute_name, $this->type_name ), 'wp-graphql' ),
					'resolve' => function( $block, $args, $context, $info ) use ( $attribute_name, $attribute_config ) {

						if ( isset( $attribute_config['selector'], $attribute_config['source'] ) ) {

							$rendered_block = wp_unslash( render_block( $block ) );

							$value = null;

							switch ( $attribute_config['source'] ) {
								case 'attribute':

									if ( empty( $rendered_block ) ) {
										$value = null;
										break;
									}

									$doc = new DOMDocument('1.0', 'UTF-8');
									$doc->loadHTML( $rendered_block );
									$node = $doc->getElementsByTagName( $attribute_config['selector'] );
									$value = $node[0] ? $node[0]->getAttribute( $attribute_config['attribute'] ) : null;
									break;
								case 'html':

									if ( empty( $rendered_block ) ) {
										$value = null;
										break;
									}

									$doc = new DOMDocument('1.0', 'UTF-8');
									$doc->loadHTML( $rendered_block );
									$node = $doc->getElementsByTagName( $attribute_config['selector'] );

									$inner_html = '';
									foreach ( $node as $elem ) {
										$children = $elem->childNodes;
										foreach ( $children as $child ) {
											$inner_html .= $doc->saveHTML( $child );
										}

									}
									return $inner_html;

							}

							return $value;

						}

						return $block['attrs'][ $attribute_name ] ?? null;


					}
				];

			}

			if ( ! empty( $block_attribute_fields ) ) {

				$block_attribute_type_name = $this->type_name . 'Attributes';

				register_graphql_object_type( $block_attribute_type_name, [
					'description' => __( 'Attributes of the %s Block Type', 'wp-graphql-block-editor' ),
					'fields' => $block_attribute_fields,
				]);

				register_graphql_field( $this->type_name, 'attributes', [
					'type' => $block_attribute_type_name,
					'description' => __( 'Attributes of the %s Block Type', 'wp-graphql-block-editor' ),
					'resolve' => function( $block ) {
						return $block;
					}
				]);

			}

		}

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
			'eagerlyLoadType' => true,
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

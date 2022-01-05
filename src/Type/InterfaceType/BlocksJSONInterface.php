<?php
namespace WPGraphQL\BlockEditor\Type\InterfaceType;

use Exception;
use WPGraphQL\Registry\TypeRegistry;

class BlocksJSONInterface {

	/**
	 * @param TypeRegistry $type_registry
	 *
	 * @throws Exception
	 */
	public static function register_type( TypeRegistry $type_registry ) {

		register_graphql_interface_type( 'NodeWithBlocksJSON', [
			'description' => __( 'Node that has editor blocks queryable via JSON', 'wp-graphql-block-editor' ),
			'eagerlyLoadType' => true,
			'fields' => [
				'blocksJSON' => [
					'type' => 'String',
					'description' => __( 'Block editor blocks, output as JSON 🤮', 'wp-graphql-block-editor' ),
				]
			],
		]);

	}
}

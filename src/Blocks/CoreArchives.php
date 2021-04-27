<?php
namespace WPGraphQL\BlockEditor\Blocks;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;

class CoreArchives extends Block {

	public function register_fields() {
		register_graphql_fields( $this->type_name, [
			'displayAsDropdown' => [
				'type' => 'Boolean',
				'resolve' => function( $block, array $args, AppContext $context, ResolveInfo $info ) {
					return isset( $block['attrs']['displayAsDropdown'] ) ? (bool) $block['attrs']['displayAsDropdown'] : false;
				},
			],
			'showPostCounts' => [
				'type' => 'Boolean',
				'resolve' => function( $block, array $args, AppContext $context, ResolveInfo $info ) {
					return isset( $block['attrs']['showPostCounts'] ) ? (bool) $block['attrs']['showPostCounts'] : false;
				},
			]
		]);
	}

}

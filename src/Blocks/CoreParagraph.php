<?php
namespace WPGraphQL\BlockEditor\Blocks;

class CoreParagraph extends Block {

	public function register_fields() {
		$this->block_registry->type_registry->register_fields( $this->type_name, [
			'test' => [
				'type' => 'String',
				'description' => __( 'Testing', 'wp-graphql-block-editor' ),
				'resolve' => function() {
					return 'test value';
				}
			]
		]);
	}

}

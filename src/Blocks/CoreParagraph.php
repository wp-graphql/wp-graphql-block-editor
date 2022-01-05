<?php
namespace WPGraphQL\BlockEditor\Blocks;

class CoreParagraph extends Block {

	public function register_fields() {

		return;
		$this->block_registry->type_registry->register_fields( $this->type_name, [
			'align' => [
				'type' => 'String',
				'description' => __( 'Testing', 'wp-graphql-block-editor' ),
				'resolve' => function($block) {
					return $block['attrs']['align'] ?? null;
				}
			],
			'content' => [
				'type' => 'String',
				'description' => __( 'Testing', 'wp-graphql-block-editor' ),
				'resolve' => function() {
					return 'test value';
				}
			],
			'dropCap' => [
				'type' => 'String',
				'description' => __( 'Testing', 'wp-graphql-block-editor' ),
				'resolve' => function() {
					return 'test value';
				}
			],
			'placeholder' => [
				'type' => 'String',
				'description' => __( 'Testing', 'wp-graphql-block-editor' ),
				'resolve' => function() {
					return 'test value';
				}
			],
			'direction' => [
				'type' => 'String',
				'description' => __( 'Testing', 'wp-graphql-block-editor' ),
				'resolve' => function() {
					return 'test value';
				}
			]
		]);
	}

}

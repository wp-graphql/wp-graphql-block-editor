<?php
namespace WPGraphQL\BlockEditor\Blocks;

class CoreImage extends Block {

	public function register_fields() {
		return;
		$this->block_registry->type_registry->register_fields( $this->type_name, [
			'align' => [
				'type' => 'String',
				'description' => __( 'The alignment of the image', 'wp-graphql-block-editor' ),
				'resolve' => function($block) {
					// wp_send_json( $block );
					return $block['attrs']['align'] ?? null;
				}
			],
			'url' => [
				'type' => 'String',
				'description' => __( 'The url of the image', 'wp-graphql-block-editor' ),
				'resolve' => function($block) {

					$rendered_block = wp_unslash( render_block( $block ) );
					$doc = new \DOMDocument('1.0', 'UTF-8');
					$doc->loadHTML( $rendered_block );
					$img = $doc->getElementsByTagName( 'img' );
					$src = null;
					if ( $img[0] && $img[0]->getAttribute('src') ) {
						$src = $img[0]->getAttribute('src');
					}
					return $src;
				}
			]
		]);
	}

}

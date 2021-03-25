<?php

/**
 * Test WP_Theme_JSON class.
 *
 * @package Gutenberg
 */

class WP_Theme_JSON_Test extends WP_UnitTestCase {

	function test_get_settings_v0() {
		$root_name = WP_Theme_JSON::ROOT_BLOCK_NAME;
		$theme_json = new WP_Theme_JSON(
			array(
				'settings' => array(
					$root_name => array(
						'color'       => array(
							'custom' => false,
						),
						'invalid/key' => 'value',
					),
				),
				'styles'   => array(
					$root_name => array(
						'color' => array(
							'link' => 'blue',
						),
					),
				),
			)
		);

		$actual = $theme_json->get_settings();

		$expected = array(
			'color' => array(
				'custom' => false,
			),
		);

		$this->assertEqualSetsWithIndex( $expected, $actual );
	}

	function test_get_settings_v1() {
		$root_name = WP_Theme_JSON::ROOT_BLOCK_NAME;
		$theme_json = new WP_Theme_JSON(
			array(
				'version'  => 1,
				'settings' => array(
					'color'       => array(
						'custom' => false,
					),
					'invalid/key' => 'value',
					'blocks' => array(
						'core/group' => array(
							'color'       => array(
								'custom' => false,
							),
							'invalid/key' => 'value',
						),
					),
				),
				'styles'   => array(
					$root_name => array(
						'color' => array(
							'link' => 'blue',
						),
					),
				),
			)
		);

		$actual = $theme_json->get_settings();

		$expected = array(
			'color' => array(
				'custom' => false,
			),
			'blocks' => array(
				'core/group' => array(
					'color' => array(
						'custom' => false,
					),
				),
			),
		);

		$this->assertEqualSetsWithIndex( $expected, $actual );
	}

	function test_get_stylesheet_v0() {
		$root_name       = WP_Theme_JSON::ROOT_BLOCK_NAME;
		$all_blocks_name = WP_Theme_JSON::ALL_BLOCKS_NAME;

		$theme_json = new WP_Theme_JSON(
			array(
				'settings' => array(
					$all_blocks_name => array(
						'color'      => array(
							'text'    => 'value',
							'palette' => array(
								array(
									'slug'  => 'grey',
									'color' => 'grey',
								),
							),
						),
						'typography' => array(
							'fontFamilies' => array(
								array(
									'slug'       => 'small',
									'fontFamily' => '14px',
								),
								array(
									'slug'       => 'big',
									'fontFamily' => '41px',
								),
							),
						),
						'misc'       => 'value',
					),
					'core/group'     => array(
						'custom' => array(
							'base-font'   => 16,
							'line-height' => array(
								'small'  => 1.2,
								'medium' => 1.4,
								'large'  => 1.8,
							),
						),
					),
				),
				'styles'   => array(
					$root_name   => array(
						'color' => array(
							'link' => '#111',
							'text' => 'var:preset|color|grey',
						),
						'misc'  => 'value',
					),
					'core/group' => array(
						'color'   => array(
							'link' => '#333',
						),
						'spacing' => array(
							'padding' => array(
								'top'    => '12px',
								'bottom' => '24px',
							),
						),
					),
				),
				'misc'     => 'value',
			)
		);

		$this->assertEquals(
			':root{--wp--preset--color--grey: grey;--wp--preset--font-family--small: 14px;--wp--preset--font-family--big: 41px;}.wp-block-group{--wp--custom--base-font: 16;--wp--custom--line-height--small: 1.2;--wp--custom--line-height--medium: 1.4;--wp--custom--line-height--large: 1.8;}:root{--wp--style--color--link: #111;color: var(--wp--preset--color--grey);}.wp-block-group{--wp--style--color--link: #333;padding-top: 12px;padding-bottom: 24px;}.has-grey-color{color: grey !important;}.has-grey-background-color{background-color: grey !important;}',
			$theme_json->get_stylesheet()
		);
		$this->assertEquals(
			':root{--wp--style--color--link: #111;color: var(--wp--preset--color--grey);}.wp-block-group{--wp--style--color--link: #333;padding-top: 12px;padding-bottom: 24px;}.has-grey-color{color: grey !important;}.has-grey-background-color{background-color: grey !important;}',
			$theme_json->get_stylesheet( 'block_styles' )
		);
		$this->assertEquals(
			':root{--wp--preset--color--grey: grey;--wp--preset--font-family--small: 14px;--wp--preset--font-family--big: 41px;}.wp-block-group{--wp--custom--base-font: 16;--wp--custom--line-height--small: 1.2;--wp--custom--line-height--medium: 1.4;--wp--custom--line-height--large: 1.8;}',
			$theme_json->get_stylesheet( 'css_variables' )
		);
	}

	function test_get_stylesheet() {
		$theme_json = new WP_Theme_JSON(
			array(
				'version'  => 1,
				'settings' => array(
					'color'      => array(
						'text'    => 'value',
						'palette' => array(
							array(
								'slug'  => 'grey',
								'color' => 'grey',
							),
						),
					),
					'typography' => array(
						'fontFamilies' => array(
							array(
								'slug'       => 'small',
								'fontFamily' => '14px',
							),
							array(
								'slug'       => 'big',
								'fontFamily' => '41px',
							),
						),
					),
					'misc'       => 'value',
					'blocks' => array(
						'core/group'     => array(
							'custom' => array(
								'base-font'   => 16,
								'line-height' => array(
									'small'  => 1.2,
									'medium' => 1.4,
									'large'  => 1.8,
								),
							),
						),
					),
				),
				'styles'   => array(
					'color' => array(
						'text' => 'var:preset|color|grey',
					),
					'misc'  => 'value',
					'elements' => array(
						'link' => array(
							'color' => array(
								'text' => '#111',
							),
						),
					),
					'blocks' => array(
						'core/group' => array(
							'elements' => array(
								'link'   => array(
									'color' => array(
										'text' => '#333',
									),
								),
							),
							'spacing' => array(
								'padding' => array(
									'top'    => '12px',
									'bottom' => '24px',
								),
							),
						),
					),
				),
				'misc'     => 'value',
			)
		);

		$this->assertEquals(
			':root{--wp--preset--color--grey: grey;--wp--preset--font-family--small: 14px;--wp--preset--font-family--big: 41px;}.wp-block-group{--wp--custom--base-font: 16;--wp--custom--line-height--small: 1.2;--wp--custom--line-height--medium: 1.4;--wp--custom--line-height--large: 1.8;}:root{--wp--style--color--link: #111;color: var(--wp--preset--color--grey);}.wp-block-group{--wp--style--color--link: #333;padding-top: 12px;padding-bottom: 24px;}.has-grey-color{color: grey !important;}.has-grey-background-color{background-color: grey !important;}',
			$theme_json->get_stylesheet()
		);
		$this->assertEquals(
			':root{--wp--style--color--link: #111;color: var(--wp--preset--color--grey);}.wp-block-group{--wp--style--color--link: #333;padding-top: 12px;padding-bottom: 24px;}.has-grey-color{color: grey !important;}.has-grey-background-color{background-color: grey !important;}',
			$theme_json->get_stylesheet( 'block_styles' )
		);
		$this->assertEquals(
			':root{--wp--preset--color--grey: grey;--wp--preset--font-family--small: 14px;--wp--preset--font-family--big: 41px;}.wp-block-group{--wp--custom--base-font: 16;--wp--custom--line-height--small: 1.2;--wp--custom--line-height--medium: 1.4;--wp--custom--line-height--large: 1.8;}',
			$theme_json->get_stylesheet( 'css_variables' )
		);
	}

	function test_get_stylesheet_preset_rules_come_after_block_rules() {
		$theme_json = new WP_Theme_JSON(
			array(
				'version'  => 1,
				'settings' => array(
					'blocks' => array(
						'core/group' => array(
							'color' => array(
								'palette' => array(
									array(
										'slug'  => 'grey',
										'color' => 'grey',
									),
								),
							),
						),
					)
				),
				'styles'   => array(
					'blocks' => array(
						'core/group' => array(
							'color' => array(
								'text' => 'red',
							),
						),
					),
				),
			)
		);

		$this->assertEquals(
			'.wp-block-group{--wp--preset--color--grey: grey;}.wp-block-group{color: red;}.wp-block-group.has-grey-color{color: grey !important;}.wp-block-group.has-grey-background-color{background-color: grey !important;}',
			$theme_json->get_stylesheet()
		);
		$this->assertEquals(
			'.wp-block-group{color: red;}.wp-block-group.has-grey-color{color: grey !important;}.wp-block-group.has-grey-background-color{background-color: grey !important;}',
			$theme_json->get_stylesheet( 'block_styles' )
		);
	}

	public function test_get_stylesheet_preset_values_are_marked_as_important() {
		$theme_json = new WP_Theme_JSON(
			array(
				'version'  => 1,
				'settings' => array(
					'color' => array(
						'palette' => array(
							array(
								'slug'  => 'grey',
								'color' => 'grey',
							),
						),
					),
				),
				'styles'   => array(
					'blocks' => array(
						'core/heading/h2' => array(
							'color'      => array(
								'text'       => 'red',
								'background' => 'blue',
							),
							'typography' => array(
								'fontSize'   => '12px',
								'lineHeight' => '1.3',
							),
						),
					),
				),
			)
		);

		$this->assertEquals(
			':root{--wp--preset--color--grey: grey;}h2{background-color: blue;color: red;font-size: 12px;line-height: 1.3;}.has-grey-color{color: grey !important;}.has-grey-background-color{background-color: grey !important;}',
			$theme_json->get_stylesheet()
		);
	}

	public function test_merge_incoming_data() {
		$initial   = array(
			'version'  => 1,
			'settings' => array(
				'color' => array(
					'custom'  => false,
					'palette' => array(
						array(
							'slug'  => 'red',
							'color' => 'red',
						),
						array(
							'slug'  => 'green',
							'color' => 'green',
						),
					),
				),
				'blocks' => array(
					'core/paragraph' => array(
						'color' => array(
							'custom' => false,
						),
					),
				),
			),
			'styles'   => array(
				'typography' => array(
					'fontSize' => '12',
				),
			),
		);

		$add_new_block = array(
			'version'  => 1,
			'settings' => array(
				'blocks' => array(
					'core/list' => array(
						'color' => array(
							'custom' => false,
						),
					),
				),
			),
			'styles'   => array(
				'blocks' => array(
					'core/list' => array(
						'typography' => array(
							'fontSize' => '12',
						),
						'color'      => array(
							'background' => 'brown',
						),
					),
				),
			),
		);

		$add_key_in_settings = array(
			'version'  => 1,
			'settings' => array(
				'color' => array(
					'customGradient' => true,
				),
			),
		);

		$update_key_in_settings = array(
			'version'  => 1,
			'settings' => array(
				'color' => array(
					'custom' => true,
				),
			),
		);

		$add_styles = array(
			'version' => 1,
			'styles'  => array(
				'blocks' => array(
					'core/group' => array(
						'spacing' => array(
							'padding' => array(
								'top' => '12px',
							),
						),
					),
				),
			),
		);

		$add_key_in_styles = array(
			'version' => 1,
			'styles'  => array(
				'blocks' => array(
					'core/group' => array(
						'spacing' => array(
							'padding' => array(
								'bottom' => '12px',
							),
						),
					),
				),
			),
		);

		$add_invalid_context = array(
			'version' => 1,
			'styles'  => array(
				'blocks' => array(
					'core/para' => array(
						'typography' => array(
							'lineHeight' => '12',
						),
					),
				),
			),
		);

		$update_presets = array(
			'version'  => 1,
			'settings' => array(
				'color'      => array(
					'palette'   => array(
						array(
							'slug'  => 'blue',
							'color' => 'blue',
						),
					),
					'gradients' => array(
						array(
							'slug'     => 'gradient',
							'gradient' => 'gradient',
						),
					),
				),
				'typography' => array(
					'fontSizes'    => array(
						array(
							'slug' => 'fontSize',
							'size' => 'fontSize',
						),
					),
					'fontFamilies' => array(
						array(
							'slug'       => 'fontFamily',
							'fontFamily' => 'fontFamily',
						),
					),
				),
			),
		);

		$expected = array(
			'version'  => 1,
			'settings' => array(
				'color'      => array(
					'custom'         => true,
					'customGradient' => true,
					'palette'        => array(
						array(
							'slug'  => 'blue',
							'color' => 'blue',
						),
					),
					'gradients'      => array(
						array(
							'slug'     => 'gradient',
							'gradient' => 'gradient',
						),
					),
				),
				'typography' => array(
					'fontSizes'    => array(
						array(
							'slug' => 'fontSize',
							'size' => 'fontSize',
						),
					),
					'fontFamilies' => array(
						array(
							'slug'       => 'fontFamily',
							'fontFamily' => 'fontFamily',
						),
					),
				),
				'blocks' => array(
					'core/paragraph' => array(
						'color' => array(
							'custom' => false,
						),
					),
					'core/list'      => array(
						'color' => array(
							'custom' => false,
						),
					),
				),
			),
			'styles'   => array(
				'typography' => array(
					'fontSize' => '12',
				),
				'blocks' => array(
					'core/group' => array(
						'spacing' => array(
							'padding' => array(
								'top'    => '12px',
								'bottom' => '12px',
							),
						),
					),
					'core/list'  => array(
						'typography' => array(
							'fontSize' => '12',
						),
						'color'      => array(
							'background' => 'brown',
						),
					),
				),
			),
		);

		$theme_json = new WP_Theme_JSON( $initial );
		$theme_json->merge( new WP_Theme_JSON( $add_new_block ) );
		$theme_json->merge( new WP_Theme_JSON( $add_key_in_settings ) );
		$theme_json->merge( new WP_Theme_JSON( $update_key_in_settings ) );
		$theme_json->merge( new WP_Theme_JSON( $add_styles ) );
		$theme_json->merge( new WP_Theme_JSON( $add_key_in_styles ) );
		$theme_json->merge( new WP_Theme_JSON( $add_invalid_context ) );
		$theme_json->merge( new WP_Theme_JSON( $update_presets ) );
		$actual = $theme_json->get_raw_data();

		$this->assertEqualSetsWithIndex( $expected, $actual );
	}

	function test_remove_insecure_properties_removes_unsafe_styles() {
		$theme_json = new WP_Theme_JSON(
			array(
				'version' => 1,
				'styles'  => array(
					'color' => array(
						'gradient' => 'url(\'data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxMCcgaGVpZ2h0PScxMCc+PHNjcmlwdD5hbGVydCgnb2snKTwvc2NyaXB0PjxsaW5lYXJHcmFkaWVudCBpZD0nZ3JhZGllbnQnPjxzdG9wIG9mZnNldD0nMTAlJyBzdG9wLWNvbG9yPScjRjAwJy8+PHN0b3Agb2Zmc2V0PSc5MCUnIHN0b3AtY29sb3I9JyNmY2MnLz4gPC9saW5lYXJHcmFkaWVudD48cmVjdCBmaWxsPSd1cmwoI2dyYWRpZW50KScgeD0nMCcgeT0nMCcgd2lkdGg9JzEwMCUnIGhlaWdodD0nMTAwJScvPjwvc3ZnPg==\')',
						'text'     => 'var:preset|color|dark-red',
					),
					'elements' => array(
						'link' => array(
							'color' => array(
								'gradient' => 'url(\'data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxMCcgaGVpZ2h0PScxMCc+PHNjcmlwdD5hbGVydCgnb2snKTwvc2NyaXB0PjxsaW5lYXJHcmFkaWVudCBpZD0nZ3JhZGllbnQnPjxzdG9wIG9mZnNldD0nMTAlJyBzdG9wLWNvbG9yPScjRjAwJy8+PHN0b3Agb2Zmc2V0PSc5MCUnIHN0b3AtY29sb3I9JyNmY2MnLz4gPC9saW5lYXJHcmFkaWVudD48cmVjdCBmaWxsPSd1cmwoI2dyYWRpZW50KScgeD0nMCcgeT0nMCcgd2lkdGg9JzEwMCUnIGhlaWdodD0nMTAwJScvPjwvc3ZnPg==\')',
								'text'     => 'var:preset|color|dark-yellow',
							),
						),
					),
					'blocks'   => array(
						'core/group'  => array(
							'color' => array(
								'gradient' => 'url(\'data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxMCcgaGVpZ2h0PScxMCc+PHNjcmlwdD5hbGVydCgnb2snKTwvc2NyaXB0PjxsaW5lYXJHcmFkaWVudCBpZD0nZ3JhZGllbnQnPjxzdG9wIG9mZnNldD0nMTAlJyBzdG9wLWNvbG9yPScjRjAwJy8+PHN0b3Agb2Zmc2V0PSc5MCUnIHN0b3AtY29sb3I9JyNmY2MnLz4gPC9saW5lYXJHcmFkaWVudD48cmVjdCBmaWxsPSd1cmwoI2dyYWRpZW50KScgeD0nMCcgeT0nMCcgd2lkdGg9JzEwMCUnIGhlaWdodD0nMTAwJScvPjwvc3ZnPg==\')',
								'text'     => 'var:preset|color|dark-gray',
							),
							'elements' => array(
								'link' => array(
									'color' => array(
										'gradient' => 'url(\'data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxMCcgaGVpZ2h0PScxMCc+PHNjcmlwdD5hbGVydCgnb2snKTwvc2NyaXB0PjxsaW5lYXJHcmFkaWVudCBpZD0nZ3JhZGllbnQnPjxzdG9wIG9mZnNldD0nMTAlJyBzdG9wLWNvbG9yPScjRjAwJy8+PHN0b3Agb2Zmc2V0PSc5MCUnIHN0b3AtY29sb3I9JyNmY2MnLz4gPC9saW5lYXJHcmFkaWVudD48cmVjdCBmaWxsPSd1cmwoI2dyYWRpZW50KScgeD0nMCcgeT0nMCcgd2lkdGg9JzEwMCUnIGhlaWdodD0nMTAwJScvPjwvc3ZnPg==\')',
										'text'     => 'var:preset|color|dark-pink',
									),
								),
							),
						),
						'invalid/key' => array(
							'background' => 'green',
						),
					),
				),
			),
			true
		);
		$theme_json->remove_insecure_properties();
		$actual   = $theme_json->get_raw_data();
		$expected = array(
			'version' => 1,
			'styles'  => array(
				'color' => array(
					'text' => 'var:preset|color|dark-red',
				),
				'elements' => array(
					'link' => array(
						'color' => array(
							'text' => 'var:preset|color|dark-yellow',
						),
					),
				),
				'blocks' => array(
					'core/group' => array(
						'color' => array(
							'text' => 'var:preset|color|dark-gray',
						),
						'elements' => array(
							'link' => array(
								'color' => array(
									'text' => 'var:preset|color|dark-pink',
								),
							)
						)
					),
				),
			),
		);
		$this->assertEqualSetsWithIndex( $expected, $actual );
	}

	function test_remove_insecure_properties_removes_unsafe_styles_sub_properties() {
		$theme_json = new WP_Theme_JSON(
			array(
				'version' => 1,
				'styles'  => array(
					'spacing' => array(
						'padding' => array(
							'top'    => '1px',
							'right'  => '1px',
							'bottom' => 'var(--unsafe-var-toplevel)',
							'left'   => '1px',
						),
					),
					'elements' => array(
						'link' => array(
							'spacing' => array(
								'padding' => array(
									'top'    => '1px',
									'right'  => '1px',
									'bottom' => 'var(--unsafe-var-elements)',
									'left'   => '1px',
								),
							),
						),
					),
					'blocks' => array(
						'core/group' => array(
							'spacing' => array(
								'padding' => array(
									'top'    => '1px',
									'right'  => '1px',
									'bottom' => 'var(--unsafe-var-block)',
									'left'   => '1px',
								),
							),
							'elements' => array(
								'link' => array(
									'spacing' => array(
										'padding' => array(
											'top'    => '1px',
											'right'  => '1px',
											'bottom' => 'var(--unsafe-var-block-elements)',
											'left'   => '1px',
										),
									),
								),
							),
						),
					),
				),
			),
			true
		);
		$theme_json->remove_insecure_properties();
		$actual   = $theme_json->get_raw_data();
		$expected = array(
			'version' => 1,
			'styles'  => array(
				'spacing' => array(
					'padding' => array(
						'top'   => '1px',
						'right' => '1px',
						'left'  => '1px',
					),
				),
				'elements' => array(
					'link' => array(
						'spacing' => array(
							'padding' => array(
								'top'   => '1px',
								'right' => '1px',
								'left'  => '1px',
							),
						),
					),
				),
				'blocks' => array(
					'core/group' => array(
						'spacing' => array(
							'padding' => array(
								'top'   => '1px',
								'right' => '1px',
								'left'  => '1px',
							),
						),
						'elements' => array(
							'link' => array(
								'spacing' => array(
									'padding' => array(
										'top'   => '1px',
										'right' => '1px',
										'left'  => '1px',
									),
								),
							),
						),
					),
				),
			),
		);
		$this->assertEqualSetsWithIndex( $expected, $actual );
	}

	function test_remove_insecure_properties_removes_non_preset_settings() {
		$theme_json = new WP_Theme_JSON(
			array(
				'version'  => 1,
				'settings' => array(
					'color'   => array(
						'custom'  => true,
						'palette' => array(
							array(
								'name'  => 'Red',
								'slug'  => 'red',
								'color' => '#ff0000',
							),
							array(
								'name'  => 'Green',
								'slug'  => 'green',
								'color' => '#00ff00',
							),
							array(
								'name'  => 'Blue',
								'slug'  => 'blue',
								'color' => '#0000ff',
							),
						),
					),
					'spacing' => array(
						'customPadding' => false,
					),
					'blocks' => array(
						'core/group' => array(
							'color'   => array(
								'custom'  => true,
								'palette' => array(
									array(
										'name'  => 'Yellow',
										'slug'  => 'yellow',
										'color' => '#ff0000',
									),
									array(
										'name'  => 'Pink',
										'slug'  => 'pink',
										'color' => '#00ff00',
									),
									array(
										'name'  => 'Orange',
										'slug'  => 'orange',
										'color' => '#0000ff',
									),
								),
							),
							'spacing' => array(
								'customPadding' => false,
							),
						),
					),
				),
			),
			true
		);
		$theme_json->remove_insecure_properties();
		$result   = $theme_json->get_raw_data();
		$expected = array(
			'version'  => 1,
			'settings' => array(
				'color' => array(
					'palette' => array(
						array(
							'name'  => 'Red',
							'slug'  => 'red',
							'color' => '#ff0000',
						),
						array(
							'name'  => 'Green',
							'slug'  => 'green',
							'color' => '#00ff00',
						),
						array(
							'name'  => 'Blue',
							'slug'  => 'blue',
							'color' => '#0000ff',
						),
					),
				),
				'blocks' => array(
					'core/group' => array(
						'color' => array(
							'palette' => array(
								array(
									'name'  => 'Yellow',
									'slug'  => 'yellow',
									'color' => '#ff0000',
								),
								array(
									'name'  => 'Pink',
									'slug'  => 'pink',
									'color' => '#00ff00',
								),
								array(
									'name'  => 'Orange',
									'slug'  => 'orange',
									'color' => '#0000ff',
								),
							),
						),
					),
				)
			),
		);
		$this->assertEqualSetsWithIndex( $expected, $result );
	}

	function test_remove_insecure_properties_removes_unsafe_preset_settings() {
		$theme_json = new WP_Theme_JSON(
			array(
				'version'  => 1,
				'settings' => array(
					'color'      => array(
						'palette' => array(
							array(
								'name'  => 'Red/><b>ok</ok>',
								'slug'  => 'red',
								'color' => '#ff0000',
							),
							array(
								'name'  => 'Green',
								'slug'  => 'a" attr',
								'color' => '#00ff00',
							),
							array(
								'name'  => 'Blue',
								'slug'  => 'blue',
								'color' => 'var(--custom-v1)',
							),
							array(
								'name'  => 'Pink',
								'slug'  => 'pink',
								'color' => '#FFC0CB',
							),
						),
					),
					'typography' => array(
						'fontFamilies' => array(
							array(
								'name'       => 'Helvetica Arial/><b>test</b>',
								'slug'       => 'helvetica-arial',
								'fontFamily' => 'Helvetica Neue, Helvetica, Arial, sans-serif',
							),
							array(
								'name'       => 'Geneva',
								'slug'       => 'geneva#asa',
								'fontFamily' => 'Geneva, Tahoma, Verdana, sans-serif',
							),
							array(
								'name'       => 'Cambria',
								'slug'       => 'cambria',
								'fontFamily' => 'Cambria, Georgia, serif',
							),
							array(
								'name'       => 'Helvetica Arial',
								'slug'       => 'helvetica-arial',
								'fontFamily' => 'var(--custom-var-1)',
							),
						),
					),
					'blocks' => array(
						'core/group' => array(
							'color'      => array(
								'palette' => array(
									array(
										'name'  => 'Red/><b>ok</ok>',
										'slug'  => 'red',
										'color' => '#ff0000',
									),
									array(
										'name'  => 'Green',
										'slug'  => 'a" attr',
										'color' => '#00ff00',
									),
									array(
										'name'  => 'Blue',
										'slug'  => 'blue',
										'color' => 'var(--custom-v1)',
									),
									array(
										'name'  => 'Pink',
										'slug'  => 'pink',
										'color' => '#FFC0CB',
									),
								),
							),
						)
					)
				),
			),
			true
		);
		$theme_json->remove_insecure_properties();
		$result   = $theme_json->get_raw_data();
		$expected = array(
			'version'  => 1,
			'settings' => array(
				'color'      => array(
					'palette' => array(
						array(
							'name'  => 'Pink',
							'slug'  => 'pink',
							'color' => '#FFC0CB',
						),
					),
				),
				'typography' => array(
					'fontFamilies' => array(
						array(
							'name'       => 'Cambria',
							'slug'       => 'cambria',
							'fontFamily' => 'Cambria, Georgia, serif',
						),
					),
				),
				'blocks' => array(
					'core/group' => array(
						'color'      => array(
							'palette' => array(
								array(
									'name'  => 'Pink',
									'slug'  => 'pink',
									'color' => '#FFC0CB',
								),
							),
						),
					),
				)
			),
		);
		$this->assertEqualSetsWithIndex( $expected, $result );
	}

	function test_get_custom_templates() {
		$theme_json = new WP_Theme_JSON(
			array(
				'customTemplates' => array(
					array(
						'name'  => 'page-home',
						'title' => 'Homepage template',
					),
				),
			)
		);

		$page_templates = $theme_json->get_custom_templates();

		$this->assertEqualSetsWithIndex(
			$page_templates,
			array(
				'page-home' => array(
					'title'     => 'Homepage template',
					'postTypes' => array( 'page' ),
				),
			)
		);
	}

	function test_get_template_parts() {
		$theme_json = new WP_Theme_JSON(
			array(
				'templateParts' => array(
					array(
						'name' => 'small-header',
						'area' => 'header',
					),
				),
			)
		);

		$template_parts = $theme_json->get_template_parts();

		$this->assertEqualSetsWithIndex(
			$template_parts,
			array(
				'small-header' => array(
					'area' => 'header',
				),
			)
		);
	}
}

<?php

namespace MXP\LayoutWidths\Core;

final class App extends Plugin {

	public ?array $settings = null;
	public ?array $customWidths = null;
	public ?array $customLabels = null;

	/**
	 * Load the plugin
	 *
	 * @return void
	 */
	public function load() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Init the plugin
	 *
	 * @return void
	 */
	public function init(){
		add_action('init', [ $this, 'loadTranslations' ]);
		add_action('init',  [ $this, 'hydrate' ]);
		add_action('init',  [ $this, 'hookCustomizations' ]);
	}

	/**
	 * Hook customizations
	 *
	 * @return void
	 */
	public function hookCustomizations(){

		// verify if we have custom widths
		if( ! is_array( $this->customWidths ) || empty( $this->customWidths ) ){
			return ;
		}

		add_action('wp_head', [ $this, 'enqueueCustomStylesAsInlineStyles' ], 1);
		add_action( 'enqueue_block_editor_assets', [ $this, 'editorEnqueues' ] );
		add_action ('render_block', [ $this, 'renderGroupBlockWrapper' ], 10, 2 );
	}


	/**
	 * Get Translations
	 *
	 * @return void
	 */
	public function loadTranslations(){
		$locale = get_user_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'layout-widths' );
		load_textdomain( 'layout-widths', WP_LANG_DIR . '/plugins/layout-widths-' . $locale . '.mo' );
		load_plugin_textdomain( 'layout-widths', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/*
	 * Add custom styles as inline styles in the head, before wp global styles
	 *
	 * @return void
	 */
	public function enqueueCustomStylesAsInlineStyles() {
		$css_content = $this->getInlinedCustomWidthsStyles();
		if ( ! empty( $css_content ) ) {
			echo '<style id="layout-widths-inline-styles">' . $css_content . '</style>';
		}
	}

	/**
	 * Enqueue block editor assets.
	 */
	public function editorEnqueues(){

		if( ! is_admin() ){
			return ;
		}

		$plugin_path = untrailingslashit( $this->directoryPath );
		$plugin_url  = untrailingslashit( $this->pluginUrl );
		$asset_file = include untrailingslashit( $this->directoryPath ) . '/dist/layout-widths/editor.asset.php';

		wp_enqueue_script(
			'editor-layout-widths-scripts',
			$plugin_url . '/dist/layout-widths/editor.js',
			$asset_file['dependencies'],
			$asset_file['version'],
		);

		wp_localize_script( 'editor-layout-widths-scripts', 'customWidths', $this->settings ?? [] );

		wp_set_script_translations(
			'editor-layout-widths-scripts',
			'crt',
			$plugin_path . '/languages',
		);

		wp_enqueue_style(
			'editor-layout-widths-styles',
			$plugin_url . '/dist/layout-widths/editor.css',
		);

		wp_add_inline_style( 'editor-layout-widths-styles', $this->getInlinedCustomWidthsStyles() );

	}


	/**
	 * Get inlined custom widths styles
	 *
	 * @return void
	 */
	public function getInlinedCustomWidthsStyles() {
		
		if( $this->customWidths === null ){
			$this->hydrateWidths();
		}

		$css_content = '';

		if ( is_array( $this->customWidths ) && ! empty( $this->customWidths ) ) {

			$selector_prefix  = [
				'.block-editor-block-list__layout.is-root-container > ',
				'.is-layout-constrained > '
			];

			foreach ( $this->customWidths as $key => $data ) {
				if ( isset( $data['value'] ) && ! empty( $data['value'] ) ) {
					$css_content .= implode( '', array_map( fn( $selector ) => "{$selector}.align{$key} { max-width: {$data['value']}; }\n", $selector_prefix ) );
				}
			}
		}

		return  $css_content ;
	}


	/**
	 * Hydrate custom widths and labels from JSON file
	 *
	 * @return void
	 */
	public function hydrate() {

		$this->hydrateSettingsFromJSON();
		$this->hydrateWidths();
		$this->hydrateLabels();
	}


	/**
	 * Hydrate settings from JSON
	 *
	 * @return void
	 */
	public function hydrateSettingsFromJSON(): void {

		// Retrieve custom settings from theme json
		$data = wp_get_global_settings( path : [ 'custom','layoutWidths' ] ) ;
		if( ! is_array( $data ) || ( ! isset( $data['widths'] ) && ! isset( $data['labels'] ) ) ){
			$data = null ;
		}
		$this->settings = $data ?? null ;
	}

	/**
	 * Hydrate custom widths from JSON file
	 *
	 * @return void
	 */
	public function hydrateWidths() {
		$this->customWidths = $this->settings['widths'] ?? [] ;
	}

	/**
	 * Hydrate custom labels from JSON file
	 *
	 * @return void
	 */
	public function hydrateLabels(){
		$this->customLabels = $this->settings['labels'] ?? [] ;
	}

	/**
	 * Wrapper for group block to apply custom width on frontend
	 *
	 * @param $block_content
	 * @param $block
	 * @return void
	 */
	public function renderGroupBlockWrapper( $block_content, $block ) {

		$name = $block['blockName'] ?? '';

		if( $name !== 'core/group' ){
			return $block_content;
		}

		$custom_width = $block['attrs']['customWidth'] ?? '' ;
		if( empty( $custom_width ) ){
			return $block_content;
		}

		if( ! empty( $custom_width ) && ! empty( $block_content ) ){
			$block = new \WP_HTML_Tag_Processor( $block_content );
			if ( $block->next_tag( array( 'class_name' => 'wp-block-group' ) ) ) {
				$block->add_class( 'align'.$custom_width );
			}
			return $block->get_updated_html();
		}
		return $block_content;
	}

}
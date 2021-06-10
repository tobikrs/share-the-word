<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/tobikrs
 * @since      1.0.0
 *
 * @package    Share_The_Word
 * @subpackage Share_The_Word/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Share_The_Word
 * @subpackage Share_The_Word/public
 * @author     Tobias Krause <tobias_krause@akranet.de>
 */
class Share_The_Word_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The prefix of this plugin.
	 * 
	 * @access  private
	 * @var     string      $prefix     The prefix of this plugin.
	 */
	private $prefix;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name   The name of this plugin.
	 * @param      string    $prefix        The prefix of this plugin.
	 * @param      string    $version       The version of this plugin.
	 */
	public function __construct( $plugin_name, $prefix, $version ) {

		$this->plugin_name = $plugin_name;
		$this->prefix = $prefix;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Share_The_Word_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Share_The_Word_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/share-the-word-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Share_The_Word_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Share_The_Word_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/share-the-word-public.js', array( 'jquery' ), $this->version, false );

	}


	public function show_sermon_meta_media( $content ) {
		if ( is_singular( $this->prefix . 'sermon' ) && in_the_loop() && is_main_query() ) {
			$meta_content = "";
			$meta_audio = get_post_meta( get_the_ID(), 'stw_audio', true );

			if ( $meta_audio ) {
				$meta_audio_block = $this->get_meta_audio_block( $meta_audio );

				if ( $meta_audio_block ) {
					$content = sprintf(
						"%s\n%s", render_block( $meta_audio_block ), $content );
				}
			}

			$meta_video = get_post_meta( get_the_ID(), 'stw_video', true );

			if ( $meta_video ) {
				$meta_video_block = $this->get_meta_video_block( $meta_video );

				if ( $meta_video_block ) {
					$content = sprintf(
						"%s\n%s", render_block( $meta_video_block ), $content );
				}
			}

			// DEBUG: Only for getting the encoded (or unparsed) block
			// global $post;
			// $content = sprintf( "<pre>%s</pre>%s", esc_attr( $post->post_content ), $content );
		}

		return $content;
	}


	private function get_meta_audio_block( $meta_audio ) {
		$src = $meta_audio['src'];

		if ( isset( $src ) && $src && isset( $meta_audio['is_file'] ) && isset($meta_audio['is_embed'] ) ) {
			$content = '';

			if ( $meta_audio['is_file'] ) {
				$content = sprintf(
					'<!-- wp:audio -->
					<figure class="wp-block-audio audio-meta"><audio controls src="%s"></audio></figure>
					<!-- /wp:audio -->', esc_url( $src ) );

			} elseif ( $meta_audio['is_embed'] && isset( $meta_audio['provider_slug'] ) ) {
				$args_classes = array();
				$embed_classes = array( "wp-block-embed", "audio-meta", "is-type-rich" );

				switch ( $meta_audio['provider_slug'] ) {
					case 'spotify':
						$embed_classes[] = "is-provider-youtube";
						$embed_classes[] = "wp-block-embed-youtube";
						$args_classes[] = "wp-embed-aspect-16-9";
						$args_classes[] = "wp-has-aspect-ratio";
						
						break;

					default:
						return false;
				}

				$content = sprintf(
					'<!-- wp:embed {"url":"%1$s","type":"rich","providerNameSlug":"%2$s","responsive":true} -->
					<figure class="%3$s %4$s"><div class="wp-block-embed__wrapper">
					%1$s
					</div></figure>
					<!-- /wp:embed -->', $src, $meta_audio['provider_slug'], implode( " ", $embed_classes ), implode( " ", $args_classes )
				);
			}
	
			$blocks = parse_blocks( $content );

			if ( is_array( $blocks ) && sizeof( $blocks ) > 0 ) {
				return $blocks[0];
			}
		}

		return false;
	}

	private function get_meta_video_block( $meta_video ) {
		$src = $meta_video['src'];

		if ( isset( $src ) && $src && isset( $meta_video['is_file'] ) && isset($meta_video['is_embed'] ) ) {
			$content = '';

			if ( $meta_video['is_file'] ) {
				$content = sprintf(
				'<!-- wp:video -->
				<figure class="wp-block-video video-meta"><video controls src="%s"></video></figure>
				<!-- /wp:video -->', esc_url( $src ) );

			} elseif ( $meta_video['is_embed'] && isset( $meta_video['provider_slug'] ) ) {
				$args_classes = array();
				$embed_classes = array( "wp-block-embed", "video-meta", "is-type-video" );

				switch ( $meta_video['provider_slug'] ) {
					case 'youtube':
						$embed_classes[] = "is-provider-youtube";
						$embed_classes[] = "wp-block-embed-youtube";
						$args_classes[] = "wp-embed-aspect-16-9";
						$args_classes[] = "wp-has-aspect-ratio";
						
						break;

					default:
						break;
				}

				$content = sprintf(
					'<!-- wp:embed {"url":"%1$s","type":"video","providerNameSlug":"%2$s","responsive":true,"className":"%4$s"} -->
					<figure class="%3$s %4$s"><div class="wp-block-embed__wrapper">
					%1$s
					</div></figure>
					<!-- /wp:embed -->', $src, $meta_video['provider_slug'], implode( " ", $embed_classes ), implode( " ", $args_classes )
				);
			}

			$blocks = parse_blocks( $content );

			if ( is_array( $blocks ) && sizeof( $blocks ) > 0 ) {
				return $blocks[0];
			}
		}

		return false;
	}
}

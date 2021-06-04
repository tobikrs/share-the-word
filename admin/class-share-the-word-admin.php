<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/tobikrs
 * @since      1.0.0
 *
 * @package    Share_The_Word
 * @subpackage Share_The_Word/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Share_The_Word
 * @subpackage Share_The_Word/admin
 * @author     Tobias Krause <tobias_krause@akranet.de>
 */
class Share_The_Word_Admin {

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
	 * @since   1.0.0
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
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/share-the-word-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/share-the-word-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_edtior_scripts() {
		// wp_enqueue_script( $this->plugin_name . '-editor', plugin_dir_url( __FILE__ ) .)

	}

	/**
	* Register the administration menu for this plugin into the WordPress Dashboard menu.
	*
	* @since 1.0.0
	*/
	public function add_plugin_admin_menu() {
		add_options_page( __( 'Share The Word Settings', $this->plugin_name ), __( 'Share The Word', $this->plugin_name ), 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
	}

	/**
	* Add settings action link to the plugins page.
	*
	* @since 1.0.0
	*/
	public function add_action_links( $links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge( $settings_link, $links );
	}

	/**
	* Render the settings page for this plugin.
	*
	* @since 1.0.0
	*/
	public function display_plugin_setup_page() {
		include_once( 'partials/share-the-word-admin-display.php' );
	}

	/**
	 * Creates a new custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public function new_cpt_sermon() {

		$cpt_name 	= $this->prefix . 'sermon';
		$rewrite_slug = apply_filters( $this->prefix . 'sermons_rewrite_slug', 'sermon' );

		$labels = array(
			'name'               => _x( 'Sermons', 'post type general name', $this->plugin_name ),
			'singular_name'      => _x( 'Sermon', 'post type singular name', $this->plugin_name ),
			'menu_name'          => _x( 'Sermons', 'admin menu', $this->plugin_name ),
			'name_admin_bar'     => _x( 'Sermon', 'add new on admin bar', $this->plugin_name ),
			'add_new'            => _x( 'Add New', 'sermon', $this->plugin_name ),
			'add_new_item'       => __( 'Add New Sermon', $this->plugin_name ),
			'new_item'           => __( 'New Sermon', $this->plugin_name ),
			'edit_item'          => __( 'Edit Sermon', $this->plugin_name ),
			'view_item'          => __( 'View Sermon', $this->plugin_name ),
			'all_items'          => __( 'All Sermons', $this->plugin_name ),
			'search_items'       => __( 'Search Sermons', $this->plugin_name ),
			'parent_item_colon'  => __( 'Parent Sermons:', $this->plugin_name ),
			'not_found'          => __( 'No sermons found.', $this->plugin_name ),
			'not_found_in_trash' => __( 'No sermons found in Trash.', $this->plugin_name ),
		);

		$labels = apply_filters( $this->prefix . 'sermon_labels', $labels);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'has_archive'        => true,
			'rewrite'            => array( 'slug' =>  $rewrite_slug ),
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => 58,
			'menu_icon'          => 'dashicons-format-status',
			'show_in_rest'       => true,
			'rest_base'          => 'sermon',
			'supports'           => array( 'title', 'excerpt', 'editor', 'thumbnail', 'custom-fields', 'register_post_meta')
		);

		$args = apply_filters( $this->prefix . 'sermon_cpt_args', $args );

		register_post_type( $cpt_name, $args );

	}

	/**
	 * Creates a new taxonomy series
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public function new_tax_series() {
		
		$labels = array(
			'name'              => _x( 'Series', 'taxonomy general name', $this->plugin_name),
			'singular_name'     => _x( 'Series', 'taxonomy singular name', $this->plugin_name ),
			'search_items'      => __( 'Search Series', $this->plugin_name ),
			'all_items'         => __( 'All Series', $this->plugin_name ),
			'parent_item'       => __( 'Parent Series', $this->plugin_name ),
			'parent_item_colon' => __( 'Parent Series:', $this->plugin_name ),
			'edit_item'         => __( 'Edit Series', $this->plugin_name ),
			'update_item'       => __( 'Update Series', $this->plugin_name ),
			'add_new_item'      => __( 'Add New Series', $this->plugin_name ),
			'new_item_name'     => __( 'New Series Name', $this->plugin_name ),
			'menu_name'         => __( 'Series', $this->plugin_name ),
		);

		$labels = apply_filters($this->prefix . 'sermon_series_labels', $labels);

		$sermon_rewrite_slug = apply_filters( $this->prefix . 'sermons_rewrite_slug', 'sermon' );


		$args = array(
			'hierarchical'       => true,
			'labels'             => $labels,
			'show_ui'            => true,
			'has_archive'        => true,
			'show_in_rest'       => true,
			'public'             => true,
			'publicly_queryable' => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $sermon_rewrite_slug . '/series', 'with_front' => false ),
		);

		register_taxonomy( 'series', array( $this->prefix . 'sermon' ), $args );
	}


	/**
	 * Creates custom meta tag fields for sermons
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public function new_sermon_meta() {
		// BibleVerse
		register_post_meta( $this->prefix . 'sermon', $this->prefix . 'bibleverse', array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
			'description'  => __( 'The main bible verse of the sermon', $this->plugin_name ),
		) );

		// Preacher
		register_post_meta( $this->prefix . 'sermon', $this->prefix . 'preacher', array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
			'description'  => __( 'The preacher of the sermon', $this->plugin_name ),
		) );

		// Duration
		register_post_meta( $this->prefix . 'sermon', $this->prefix . 'duration', array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
			'description'  => __( 'The duration of the (recorded) sermon. Eather the audio or video version.', $this->plugin_name ),
		) );

		// Audio
		register_post_meta( $this->prefix . 'sermon', $this->prefix . 'audio', array(
			'show_in_rest' => array(
				'schema' => array(
					'type' => 'object',
					'properties' => array(
						'src' => array(
							'type' => 'string',
							'format' => 'uri'
						),
						'is_file' => array(
							'type' => 'boolean'
						),
						'is_embed' => array(
							'type' => 'boolean'
						),
						'provider_slug' => array(
							'type' => 'string'
						),
					),
					'additionalProperties' => array( 'type' => 'boolean' )
				)
			),
			'type'         => 'object',
			'single'       => true,
			'default'      => array(
				'src' => '',
				'is_file' => false,
				'is_embed' => false,
				'provider_slug' => '',
			)
		) );

		// Video
		register_post_meta( $this->prefix . 'sermon', $this->prefix . 'video', array(
			'show_in_rest' => array(
				'schema' => array(
					'type' => 'object',
					'properties' => array(
						'src' => array(
							'type' => 'string',
							'format' => 'uri'
						),
						'is_file' => array(
							'type' => 'boolean'
						),
						'is_embed' => array(
							'type' => 'boolean'
						),
						'provider_slug' => array(
							'type' => 'string'
						),
					),
					'additionalProperties' => array( 'type' => 'boolean' )
				)
			),
			'type'         => 'object',
			'single'       => true,
			'default'      => array(
				'src' => '',
				'is_file' => false,
				'is_embed' => false,
				'provider_slug' => '',
			)
		) );
	}

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 * Behind the scenes, it registers also all assets so they can be enqueued
	 * through the block editor in the corresponding context.
	 *
	 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
	 */
	function new_sermon_meta_block() {
		$plugin_dir = plugin_dir_path( __DIR__ );
		$bibleverse_block = register_block_type_from_metadata( $plugin_dir );

		// Define as default template
		if ( $bibleverse_block ) {
			$post_type_obj = get_post_type_object( $this->prefix . 'sermon' );
			$post_type_obj->template = array(
				array( $bibleverse_block->name ),
				array( "core/audio"),
				array( "core/video"),
			);
		}
	}

}

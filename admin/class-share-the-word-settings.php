<?php

/**
 * The settings of the plugin.
 *
 * @link       https://github.com/DevinVinson/wppb-demo-plugin
 * @since      1.0.0
 *
 * @package    Share_The_Word
 * @subpackage Share_The_Word/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class Share_The_Word_Admin_Settings {

    private $prefix;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $prefix, $version ) {

        $this->plugin_name = $plugin_name;
        $this->prefix = $prefix;
        $this->version = $version;

    }

    /**
     * This function introduces the theme options into the 'Share The Word' menu unter the top-level 'Settings' menu.
     */
    public function add_plugin_admin_menu() {

        add_options_page(
            __( 'Share The Word Settings', 'share-the-word' ),
            __( 'Share The Word', 'share-the-word' ),
            'manage_options',
            $this->prefix . 'options',
            array( $this, 'render_settings_page_content')
        );

    }

    public function allow_options( $allowed_option ) {
        foreach($this->get_tabs() as $tab) {
            foreach($tab['fields'] as $field) {
                $allowed_option[$this->prefix . $tab['id']][] = $field['id'];
            }
        }

        // echo json_encode($allowed_option);

        return $allowed_option;
    }

    protected function get_option( $option_tab ) {
        if ( in_array( $option_tab, array_map( fn($tab) => $tab['id'] , $this->get_tabs() ) ) ) {
            return get_option( $this->prefix . $option_tab );
        }

        return false;
    }

    protected function get_tabs() {
        $default_tabs = array(
            array(
                'id' => 'general_options', 
                'title' => __( 'General', 'share-the-word' ),
                'callback' => array( $this, 'render_general_options_tab' ),
                'group' => 'general',
                'fields' => array(
                    array(
                        'id' => 'sermon_permalink',
                        'label' => __( 'Permalink of the sermons', 'share-the-word' ),
                        'default' => 'sermon',
                        'callback' => array( $this, 'render_textfield_sermon_permalink'),
                        'callback_args' => array()
                    ),
                )
            ) 
        );

        return apply_filters( $this->prefix . 'settings_page_tabs', $default_tabs );
    }

    /**
     * Initializes the plugins options page by registering the Sections,
     * Fields, and Settings.
     *
     * This function is registered with the 'admin_init' hook.
     */
    public function initialize_settings() {

        foreach($this->get_tabs() as $tab) {
            $option = $this->prefix . $tab['id'];
            $options_page = $option;
            $section = $tab['id'] . '_section';

            // delete_option( $option );

            // If the theme options don't exist, create them.
            if( false == get_option( $option ) ) {
                $default_values = [];

                foreach($tab['fields'] as $field) {
                    $default_values[$field['id']] = isset( $field['default'] ) ? $field['default'] : '';
                }

                add_option( $option, $default_values );
            }

            add_settings_section(
                $section,
                $tab['title'] . ' ' . __( 'Options', 'share-the-word' ),
                $tab['callback'],
                $options_page
            );

            foreach($tab['fields'] as $field) {

                add_settings_field(
                    $field['id'],
                    $field['label'],
                    $field['callback'],
                    $options_page,
                    $section,
                    $field['callback_args']
                );

            }

            register_setting(
                $tab['id'],
                $option
            );
        }

    }


    /**
     * Renders a simple page to display for the theme menu defined above.
     */
    public function render_settings_page_content( $active_tab = 'general_options' ) {
        $tabs = $this->get_tabs();

        ?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class="wrap">

            <h2><?php echo esc_html( get_admin_page_title() );?></h2>
            <?php settings_errors(); ?>

            <?php
            
            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = $_GET[ 'tab' ];
            }
            
            if (! in_array( $active_tab, array_map( fn($tab) => $tab['id'], $tabs ), true ) ) {
                $active_tab = 'general_options';
            }
            ?>

            <h2 class="nav-tab-wrapper">

            <?php foreach ( $tabs as $tab ): ?>
                <a
                    href="?page=<?php echo $this->prefix; ?>options&tab=<?php echo $tab['id']; ?>"
                    class="nav-tab <?php echo $active_tab === $tab['id'] ? 'nav-tab-active' : ''; ?>">
                    <?php echo $tab['title']; ?>
                </a>
            <?php endforeach; ?>

            </h2>

            <form method="post" action="options.php">
                <?php

                $option_tab_name = $this->prefix . $active_tab;

                settings_fields( $option_tab_name );
                do_settings_sections( $option_tab_name );

                submit_button();

                ?>
            </form>

        </div><!-- /.wrap -->
    <?php
    }

    /**
     * This function provides a simple description for the General Options page.
     *
     * It's called from the 'initialize_settings' function by being passed as a parameter
     * in the add_settings_section function.
     */
    public function render_general_options_tab() {
        if ( ( $options = $this->get_option( 'general_options' ) ) && WP_DEBUG ) {
            echo '<pre>Options Debugger:<br/><code>' . json_encode($options, JSON_PRETTY_PRINT) . '</code></pre>';
        }
    }


    /**
     * This function renders the interface elements for setting a custom permalink for the sermon post type.
     *
     */
    public function render_textfield_sermon_permalink( $args = array() ) {
        $field = 'sermon_permalink';
        $option = $this->prefix . 'general_options';

        $options = get_option( $option );

        ?>
        <code><?php echo get_home_url(); ?>/</code>
        <input type="text" id="<?php echo esc_attr( $field ); ?>" name="<?php echo esc_attr( $option ); ?>[<?php echo $field; ?>]" value="<?php echo esc_attr( $options[$field] ); ?>" />

        <?php
    }

}

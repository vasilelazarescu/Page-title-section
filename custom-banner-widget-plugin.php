<?php
/**
 * Plugin Name: Custom Banner Widget for Elementor
 * Description: A full-width banner widget with background image, breadcrumbs, title, and subtitle for Elementor page builder
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yoursite.com
 * Text Domain: custom-banner-widget
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Elementor tested up to: 3.18.0
 * Elementor Pro tested up to: 3.18.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Main Custom Banner Widget Class
 *
 * The init class that runs the Custom Banner Widget plugin
 */
final class Custom_Banner_Widget_Plugin {

    /**
     * Plugin Version
     *
     * @var string The plugin version.
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    /**
     * Minimum PHP Version
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Instance
     *
     * @var Custom_Banner_Widget_Plugin The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return Custom_Banner_Widget_Plugin An instance of the class.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * Perform some compatibility checks to make sure basic requirements are met.
     */
    public function __construct() {
        // Hook into plugins_loaded to check compatibility after all plugins are loaded
        add_action('plugins_loaded', [$this, 'on_plugins_loaded'], -1);
    }

    /**
     * On Plugins Loaded
     *
     * Checks plugin compatibility after all plugins are loaded
     */
    public function on_plugins_loaded() {
        if ($this->is_compatible()) {
            $this->init();
        }
    }

    /**
     * Compatibility Checks
     *
     * Checks whether the site meets the plugin requirement.
     */
    public function is_compatible() {
        // Check for required PHP version first
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return false;
        }

        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return false;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return false;
        }

        return true;
    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     */
    public function init() {
        // Add Plugin actions
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_widget_styles']);
        add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_editor_styles']);

        // Register widget categories
        add_action('elementor/elements/categories_registered', [$this, 'register_widget_categories']);

        // Load translation
        add_action('init', [$this, 'i18n']);
    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     */
    public function i18n() {
        load_plugin_textdomain('custom-banner-widget', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register_widgets($widgets_manager) {
        // Include Widget files
        $widget_file = __DIR__ . '/widgets/custom-banner-widget/custom-banner-widget.php';

        if (file_exists($widget_file)) {
            require_once($widget_file);

            // Register widget if class exists
            if (class_exists('Custom_Banner_Widget')) {
                $widgets_manager->register(new \Custom_Banner_Widget());
            }
        }
    }

    /**
     * Register Widget Categories
     *
     * Register custom widget categories.
     *
     * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
     */
    public function register_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'custom-widgets',
            [
                'title' => esc_html__('Custom Widgets', 'custom-banner-widget'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    /**
     * Enqueue Widget Styles
     *
     * Load widget frontend CSS files.
     */
    public function enqueue_widget_styles() {
        wp_enqueue_style(
            'custom-banner-widget-style',
            plugins_url('/widgets/custom-banner-widget/style.css', __FILE__),
            [],
            self::VERSION
        );
    }

    /**
     * Enqueue Editor Styles
     *
     * Load widget editor CSS files.
     */
    public function enqueue_editor_styles() {
        $editor_css_path = plugin_dir_path(__FILE__) . 'widgets/custom-banner-widget/editor.css';
        if (file_exists($editor_css_path)) {
            wp_enqueue_style(
                'custom-banner-widget-editor',
                plugins_url('/widgets/custom-banner-widget/editor.css', __FILE__),
                [],
                self::VERSION
            );
        }
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     */
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'custom-banner-widget'),
            '<strong>' . esc_html__('Custom Banner Widget', 'custom-banner-widget') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'custom-banner-widget') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'custom-banner-widget'),
            '<strong>' . esc_html__('Custom Banner Widget', 'custom-banner-widget') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'custom-banner-widget') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'custom-banner-widget'),
            '<strong>' . esc_html__('Custom Banner Widget', 'custom-banner-widget') . '</strong>',
            '<strong>' . esc_html__('PHP', 'custom-banner-widget') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}

/**
 * Initialize the main plugin
 */
Custom_Banner_Widget_Plugin::instance();

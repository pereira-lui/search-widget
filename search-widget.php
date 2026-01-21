<?php
/**
 * Plugin Name: Search Widget PDA
 * Plugin URI: https://github.com/pereira-lui/search-widget
 * Description: Widget de pesquisa para Elementor. Exibe um ícone de pesquisa que abre um popup com formulário de busca para todo o site.
 * Version: 1.0.5
 * Author: Lui
 * Author URI: https://github.com/pereira-lui
 * Text Domain: search-widget-pda
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: https://github.com/pereira-lui/search-widget
 * GitHub Branch: main
 * Update URI: https://github.com/pereira-lui/search-widget
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('SEARCH_WIDGET_PDA_VERSION', '1.0.5');
define('SEARCH_WIDGET_PDA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SEARCH_WIDGET_PDA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SEARCH_WIDGET_PDA_PLUGIN_FILE', __FILE__);

/**
 * Main Search Widget PDA Class
 */
final class Search_Widget_PDA {

    /**
     * Instance
     */
    private static $_instance = null;

    /**
     * Singleton Instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        // Include GitHub updater
        $this->includes();
        
        // Enqueue frontend styles and scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        
        // Register Elementor Widget
        add_action('elementor/widgets/register', [$this, 'register_elementor_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'register_elementor_category']);
        
        // Flush rewrite rules on activation/deactivation
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        
        // AJAX handler for search
        add_action('wp_ajax_search_widget_pda_search', [$this, 'ajax_search']);
        add_action('wp_ajax_nopriv_search_widget_pda_search', [$this, 'ajax_search']);
        
        // Custom search template
        add_filter('template_include', [$this, 'custom_search_template']);
    }

    /**
     * Include required files
     */
    public function includes() {
        require_once SEARCH_WIDGET_PDA_PLUGIN_DIR . 'includes/class-github-updater.php';
        new Search_Widget_PDA_GitHub_Updater(SEARCH_WIDGET_PDA_PLUGIN_FILE);
    }

    /**
     * Plugin activation
     */
    public function activate() {
        flush_rewrite_rules();
        set_transient('search_widget_pda_activated', true, 60);
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // CSS
        wp_enqueue_style(
            'search-widget-pda-style',
            SEARCH_WIDGET_PDA_PLUGIN_URL . 'assets/css/search-widget-style.css',
            [],
            SEARCH_WIDGET_PDA_VERSION
        );

        // JS
        wp_enqueue_script(
            'search-widget-pda-script',
            SEARCH_WIDGET_PDA_PLUGIN_URL . 'assets/js/search-widget-script.js',
            ['jquery'],
            SEARCH_WIDGET_PDA_VERSION,
            true
        );

        // Localize script
        wp_localize_script('search-widget-pda-script', 'searchWidgetPDA', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('search_widget_pda_nonce'),
            'homeUrl' => home_url(),
            'strings' => [
                'searching' => __('Buscando...', 'search-widget-pda'),
                'noResults' => __('Nenhum resultado encontrado.', 'search-widget-pda'),
                'searchPlaceholder' => __('Digite sua busca...', 'search-widget-pda'),
                'close' => __('Fechar', 'search-widget-pda'),
                'viewAll' => __('Ver todos os resultados', 'search-widget-pda'),
            ]
        ]);
    }

    /**
     * Register Elementor Category
     */
    public function register_elementor_category($elements_manager) {
        $elements_manager->add_category(
            'search-widget-pda',
            [
                'title' => __('Search Widget PDA', 'search-widget-pda'),
                'icon' => 'fa fa-search',
            ]
        );
    }

    /**
     * Register Elementor Widgets
     */
    public function register_elementor_widgets($widgets_manager) {
        require_once SEARCH_WIDGET_PDA_PLUGIN_DIR . 'includes/class-elementor-widget.php';
        $widgets_manager->register(new Search_Widget_PDA_Elementor_Widget());
    }

    /**
     * AJAX Search Handler
     */
    public function ajax_search() {
        check_ajax_referer('search_widget_pda_nonce', 'nonce');

        $search_term = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        
        if (empty($search_term) || strlen($search_term) < 2) {
            wp_send_json_error(['message' => __('Digite pelo menos 2 caracteres.', 'search-widget-pda')]);
        }

        $args = [
            's' => $search_term,
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'orderby' => 'relevance',
        ];

        // Buscar em todos os tipos de post públicos
        $all_public_post_types = get_post_types(['public' => true], 'names');
        $post_types = apply_filters('search_widget_pda_post_types', array_values($all_public_post_types));
        $args['post_type'] = $post_types;

        $query = new WP_Query($args);

        $results = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $thumbnail = '';
                if (has_post_thumbnail()) {
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                }

                $results[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'excerpt' => wp_trim_words(get_the_excerpt(), 15),
                    'thumbnail' => $thumbnail,
                    'post_type' => get_post_type(),
                    'date' => get_the_date('d/m/Y'),
                ];
            }
            wp_reset_postdata();
        }

        wp_send_json_success([
            'results' => $results,
            'total' => $query->found_posts,
            'search_url' => home_url('/?s=' . urlencode($search_term)),
        ]);
    }

    /**
     * Custom Search Template
     */
    public function custom_search_template($template) {
        if (is_search()) {
            $custom_template = SEARCH_WIDGET_PDA_PLUGIN_DIR . 'templates/search-results.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        return $template;
    }
}

/**
 * Initialize the plugin
 */
function search_widget_pda_init() {
    return Search_Widget_PDA::instance();
}

// Initialize
search_widget_pda_init();

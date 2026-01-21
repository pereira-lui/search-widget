<?php
/**
 * Search Widget PDA - Elementor Widget
 * 
 * Widget de pesquisa com ícone e popup
 *
 * @package Search_Widget_PDA
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Search Popup Widget
 */
class Search_Widget_PDA_Elementor_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'search_widget_pda';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Pesquisa PDA', 'search-widget-pda');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-search';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['search-widget-pda', 'general'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['search', 'pesquisa', 'busca', 'popup', 'icon'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // ========================================
        // Content Section - Icon
        // ========================================
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Ícone de Pesquisa', 'search-widget-pda'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'icon_type',
            [
                'label' => __('Tipo de Ícone', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => __('Ícone SVG', 'search-widget-pda'),
                    'text' => __('Texto', 'search-widget-pda'),
                    'icon_text' => __('Ícone + Texto', 'search-widget-pda'),
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Texto do Botão', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Pesquisar', 'search-widget-pda'),
                'condition' => [
                    'icon_type' => ['text', 'icon_text'],
                ],
            ]
        );

        $this->end_controls_section();

        // ========================================
        // Content Section - Popup
        // ========================================
        $this->start_controls_section(
            'popup_section',
            [
                'label' => __('Popup de Pesquisa', 'search-widget-pda'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'popup_title',
            [
                'label' => __('Título do Popup', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('O que você procura?', 'search-widget-pda'),
            ]
        );

        $this->add_control(
            'placeholder_text',
            [
                'label' => __('Placeholder do Campo', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Digite sua busca...', 'search-widget-pda'),
            ]
        );

        $this->add_control(
            'show_live_results',
            [
                'label' => __('Resultados em Tempo Real', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sim', 'search-widget-pda'),
                'label_off' => __('Não', 'search-widget-pda'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Mostra resultados enquanto digita', 'search-widget-pda'),
            ]
        );

        $this->add_control(
            'show_thumbnails',
            [
                'label' => __('Mostrar Thumbnails', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sim', 'search-widget-pda'),
                'label_off' => __('Não', 'search-widget-pda'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'show_live_results' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ========================================
        // Style Section - Icon Button
        // ========================================
        $this->start_controls_section(
            'style_icon_section',
            [
                'label' => __('Botão de Pesquisa', 'search-widget-pda'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => __('Tamanho do Ícone', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 16,
                        'max' => 80,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .search-pda-trigger svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Cor do Ícone', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F1F1F',
                'selectors' => [
                    '{{WRAPPER}} .search-pda-trigger svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .search-pda-trigger' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __('Cor do Ícone (Hover)', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#702F8A',
                'selectors' => [
                    '{{WRAPPER}} .search-pda-trigger:hover svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .search-pda-trigger:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => __('Cor de Fundo', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .search-pda-trigger' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_hover_color',
            [
                'label' => __('Cor de Fundo (Hover)', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(112, 47, 138, 0.1)',
                'selectors' => [
                    '{{WRAPPER}} .search-pda-trigger:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_padding',
            [
                'label' => __('Padding', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '10',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '10',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .search-pda-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __('Border Radius', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .search-pda-trigger' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ========================================
        // Style Section - Popup
        // ========================================
        $this->start_controls_section(
            'style_popup_section',
            [
                'label' => __('Popup', 'search-widget-pda'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'popup_bg_color',
            [
                'label' => __('Cor de Fundo do Popup', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
            ]
        );

        $this->add_control(
            'popup_overlay_color',
            [
                'label' => __('Cor do Overlay', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.7)',
            ]
        );

        $this->add_control(
            'popup_title_color',
            [
                'label' => __('Cor do Título', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F1F1F',
            ]
        );

        $this->add_control(
            'popup_accent_color',
            [
                'label' => __('Cor de Destaque', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#702F8A',
            ]
        );

        $this->add_control(
            'popup_width',
            [
                'label' => __('Largura Máxima do Popup', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 400,
                        'max' => 1200,
                    ],
                    '%' => [
                        'min' => 50,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 700,
                ],
            ]
        );

        $this->end_controls_section();

        // ========================================
        // Style Section - Input
        // ========================================
        $this->start_controls_section(
            'style_input_section',
            [
                'label' => __('Campo de Busca', 'search-widget-pda'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => __('Cor de Fundo', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F5F5F5',
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label' => __('Cor do Texto', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F1F1F',
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => __('Cor da Borda', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E0E0E0',
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label' => __('Cor da Borda (Focus)', 'search-widget-pda'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#702F8A',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        
        // Get settings
        $icon_type = $settings['icon_type'];
        $button_text = $settings['button_text'];
        $popup_title = $settings['popup_title'];
        $placeholder_text = $settings['placeholder_text'];
        $show_live_results = $settings['show_live_results'] === 'yes';
        $show_thumbnails = $settings['show_thumbnails'] === 'yes';
        
        // Style settings
        $popup_bg_color = $settings['popup_bg_color'];
        $popup_overlay_color = $settings['popup_overlay_color'];
        $popup_title_color = $settings['popup_title_color'];
        $popup_accent_color = $settings['popup_accent_color'];
        $popup_width = $settings['popup_width']['size'] . $settings['popup_width']['unit'];
        $input_bg_color = $settings['input_bg_color'];
        $input_text_color = $settings['input_text_color'];
        $input_border_color = $settings['input_border_color'];
        $input_focus_border_color = $settings['input_focus_border_color'];
        ?>
        
        <div class="search-pda-widget" id="search-pda-widget-<?php echo esc_attr($widget_id); ?>" 
             data-live-results="<?php echo $show_live_results ? 'true' : 'false'; ?>"
             data-show-thumbnails="<?php echo $show_thumbnails ? 'true' : 'false'; ?>">
            
            <!-- Botão/Ícone de Pesquisa -->
            <button class="search-pda-trigger" id="search-pda-trigger-<?php echo esc_attr($widget_id); ?>" 
                    aria-label="<?php _e('Abrir pesquisa', 'search-widget-pda'); ?>"
                    data-popup-id="search-pda-popup-<?php echo esc_attr($widget_id); ?>">
                <?php if ($icon_type === 'icon' || $icon_type === 'icon_text') : ?>
                <svg class="search-pda-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="M21 21l-4.35-4.35"></path>
                </svg>
                <?php endif; ?>
                <?php if ($icon_type === 'text' || $icon_type === 'icon_text') : ?>
                <span class="search-pda-trigger-text"><?php echo esc_html($button_text); ?></span>
                <?php endif; ?>
            </button>
            
            <!-- Popup de Pesquisa -->
            <div class="search-pda-popup" id="search-pda-popup-<?php echo esc_attr($widget_id); ?>" aria-hidden="true">
                <div class="search-pda-popup-overlay"></div>
                <div class="search-pda-popup-container">
                    <div class="search-pda-popup-content">
                        <!-- Header -->
                        <div class="search-pda-popup-header">
                            <?php if (!empty($popup_title)) : ?>
                            <h2 class="search-pda-popup-title"><?php echo esc_html($popup_title); ?></h2>
                            <?php endif; ?>
                            <button class="search-pda-popup-close" aria-label="<?php _e('Fechar', 'search-widget-pda'); ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Form -->
                        <form class="search-pda-form" action="<?php echo esc_url(home_url('/')); ?>" method="get">
                            <div class="search-pda-input-wrapper">
                                <svg class="search-pda-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="M21 21l-4.35-4.35"></path>
                                </svg>
                                <input type="search" 
                                       class="search-pda-input" 
                                       name="s" 
                                       placeholder="<?php echo esc_attr($placeholder_text); ?>"
                                       autocomplete="off"
                                       aria-label="<?php _e('Campo de busca', 'search-widget-pda'); ?>">
                                <button type="submit" class="search-pda-submit" aria-label="<?php _e('Buscar', 'search-widget-pda'); ?>">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Live Results -->
                        <?php if ($show_live_results) : ?>
                        <div class="search-pda-results" aria-live="polite">
                            <div class="search-pda-results-loading" style="display: none;">
                                <div class="search-pda-spinner"></div>
                                <span><?php _e('Buscando...', 'search-widget-pda'); ?></span>
                            </div>
                            <div class="search-pda-results-list"></div>
                            <div class="search-pda-results-empty" style="display: none;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M8 15s1.5-2 4-2 4 2 4 2"></path>
                                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                </svg>
                                <span><?php _e('Nenhum resultado encontrado.', 'search-widget-pda'); ?></span>
                            </div>
                            <a href="#" class="search-pda-view-all" style="display: none;">
                                <?php _e('Ver todos os resultados', 'search-widget-pda'); ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            /* Popup Styles for this specific widget */
            #search-pda-popup-<?php echo esc_attr($widget_id); ?> .search-pda-popup-overlay {
                background-color: <?php echo esc_attr($popup_overlay_color); ?>;
            }
            
            #search-pda-popup-<?php echo esc_attr($widget_id); ?> .search-pda-popup-content {
                background-color: <?php echo esc_attr($popup_bg_color); ?>;
                max-width: <?php echo esc_attr($popup_width); ?>;
            }
            
            #search-pda-popup-<?php echo esc_attr($widget_id); ?> .search-pda-popup-title {
                color: <?php echo esc_attr($popup_title_color); ?>;
            }
            
            #search-pda-popup-<?php echo esc_attr($widget_id); ?> .search-pda-input {
                background-color: <?php echo esc_attr($input_bg_color); ?>;
                color: <?php echo esc_attr($input_text_color); ?>;
                border-color: <?php echo esc_attr($input_border_color); ?>;
            }
            
            #search-pda-popup-<?php echo esc_attr($widget_id); ?> .search-pda-input:focus {
                border-color: <?php echo esc_attr($input_focus_border_color); ?>;
            }
            
            #search-pda-popup-<?php echo esc_attr($widget_id); ?> .search-pda-submit,
            #search-pda-popup-<?php echo esc_attr($widget_id); ?> .search-pda-view-all {
                background-color: <?php echo esc_attr($popup_accent_color); ?>;
            }
            
            #search-pda-popup-<?php echo esc_attr($widget_id); ?> .search-pda-result-item:hover {
                border-color: <?php echo esc_attr($popup_accent_color); ?>;
            }
            
            #search-pda-popup-<?php echo esc_attr($widget_id); ?> .search-pda-spinner {
                border-top-color: <?php echo esc_attr($popup_accent_color); ?>;
            }
        </style>
        <?php
    }
}

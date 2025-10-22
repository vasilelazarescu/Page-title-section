<?php
/**
 * Custom Banner Widget for Elementor
 *
 * A full-width banner widget with background image, breadcrumbs, title, and subtitle
 *
 * @package Custom_Banner_Widget
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Custom_Banner_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'custom-banner-widget';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return esc_html__('Custom Banner Widget', 'custom-banner-widget');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-featured-image';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['banner', 'hero', 'header', 'featured', 'image', 'background', 'breadcrumb', 'title'];
    }

    /**
     * Get featured image URL
     */
    private function get_featured_image_url() {
        $post_id = get_the_ID();
        if ($post_id && has_post_thumbnail($post_id)) {
            return get_the_post_thumbnail_url($post_id, 'full');
        }
        return '';
    }

    /**
     * Register widget controls
     */
    protected function _register_controls() {

        // ========================================
        // CONTENT TAB
        // ========================================

        // Background Image Source Section
        $this->start_controls_section(
            'section_background_image',
            [
                'label' => esc_html__('Background Image', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image_source',
            [
                'label' => esc_html__('Image Source', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'featured',
                'options' => [
                    'featured' => esc_html__('Page Featured Image', 'custom-banner-widget'),
                    'custom' => esc_html__('Custom Upload', 'custom-banner-widget'),
                ],
            ]
        );

        // Featured image status/preview
        $this->add_control(
            'featured_image_status',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => esc_html__('Using page featured image. Set a featured image in the page settings, or select Custom Upload to choose a different image.', 'custom-banner-widget'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'image_source' => 'featured',
                ],
            ]
        );

        $this->add_control(
            'custom_image',
            [
                'label' => esc_html__('Choose Image', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'image_source' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'fallback_image',
            [
                'label' => esc_html__('Fallback Image', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'description' => esc_html__('Image to display if featured image is not set', 'custom-banner-widget'),
                'condition' => [
                    'image_source' => 'featured',
                ],
            ]
        );

        $this->add_control(
            'background_size',
            [
                'label' => esc_html__('Background Size', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => esc_html__('Cover', 'custom-banner-widget'),
                    'contain' => esc_html__('Contain', 'custom-banner-widget'),
                    'auto' => esc_html__('Auto', 'custom-banner-widget'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-background' => 'background-size: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_position',
            [
                'label' => esc_html__('Background Position', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    'center center' => esc_html__('Center Center', 'custom-banner-widget'),
                    'center top' => esc_html__('Center Top', 'custom-banner-widget'),
                    'center bottom' => esc_html__('Center Bottom', 'custom-banner-widget'),
                    'left top' => esc_html__('Left Top', 'custom-banner-widget'),
                    'left center' => esc_html__('Left Center', 'custom-banner-widget'),
                    'left bottom' => esc_html__('Left Bottom', 'custom-banner-widget'),
                    'right top' => esc_html__('Right Top', 'custom-banner-widget'),
                    'right center' => esc_html__('Right Center', 'custom-banner-widget'),
                    'right bottom' => esc_html__('Right Bottom', 'custom-banner-widget'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-background' => 'background-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_repeat',
            [
                'label' => esc_html__('Background Repeat', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'no-repeat',
                'options' => [
                    'no-repeat' => esc_html__('No Repeat', 'custom-banner-widget'),
                    'repeat' => esc_html__('Repeat', 'custom-banner-widget'),
                    'repeat-x' => esc_html__('Repeat X', 'custom-banner-widget'),
                    'repeat-y' => esc_html__('Repeat Y', 'custom-banner-widget'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-background' => 'background-repeat: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_attachment',
            [
                'label' => esc_html__('Background Attachment', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'scroll',
                'options' => [
                    'scroll' => esc_html__('Scroll', 'custom-banner-widget'),
                    'fixed' => esc_html__('Fixed (Parallax)', 'custom-banner-widget'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-background' => 'background-attachment: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Overlay Section
        $this->start_controls_section(
            'section_overlay',
            [
                'label' => esc_html__('Background Overlay', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.5)',
                'selectors' => [
                    '{{WRAPPER}} .banner-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_blend_mode',
            [
                'label' => esc_html__('Blend Mode', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => esc_html__('Normal', 'custom-banner-widget'),
                    'multiply' => esc_html__('Multiply', 'custom-banner-widget'),
                    'screen' => esc_html__('Screen', 'custom-banner-widget'),
                    'overlay' => esc_html__('Overlay', 'custom-banner-widget'),
                    'darken' => esc_html__('Darken', 'custom-banner-widget'),
                    'lighten' => esc_html__('Lighten', 'custom-banner-widget'),
                    'color-dodge' => esc_html__('Color Dodge', 'custom-banner-widget'),
                    'color-burn' => esc_html__('Color Burn', 'custom-banner-widget'),
                    'hard-light' => esc_html__('Hard Light', 'custom-banner-widget'),
                    'soft-light' => esc_html__('Soft Light', 'custom-banner-widget'),
                    'difference' => esc_html__('Difference', 'custom-banner-widget'),
                    'exclusion' => esc_html__('Exclusion', 'custom-banner-widget'),
                    'hue' => esc_html__('Hue', 'custom-banner-widget'),
                    'saturation' => esc_html__('Saturation', 'custom-banner-widget'),
                    'color' => esc_html__('Color', 'custom-banner-widget'),
                    'luminosity' => esc_html__('Luminosity', 'custom-banner-widget'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-overlay' => 'mix-blend-mode: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Breadcrumb Section
        $this->start_controls_section(
            'section_breadcrumb',
            [
                'label' => esc_html__('Breadcrumb', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_breadcrumb',
            [
                'label' => esc_html__('Show Breadcrumb', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'custom-banner-widget'),
                'label_off' => esc_html__('Hide', 'custom-banner-widget'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'breadcrumb_type',
            [
                'label' => esc_html__('Breadcrumb Type', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => esc_html__('Auto Generate', 'custom-banner-widget'),
                    'custom' => esc_html__('Custom Text', 'custom-banner-widget'),
                ],
                'condition' => [
                    'show_breadcrumb' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'breadcrumb_custom_text',
            [
                'label' => esc_html__('Custom Breadcrumb', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Home > Page', 'custom-banner-widget'),
                'condition' => [
                    'show_breadcrumb' => 'yes',
                    'breadcrumb_type' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'breadcrumb_separator',
            [
                'label' => esc_html__('Separator', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '>',
                'condition' => [
                    'show_breadcrumb' => 'yes',
                    'breadcrumb_type' => 'auto',
                ],
            ]
        );

        $this->add_control(
            'breadcrumb_home_text',
            [
                'label' => esc_html__('Home Text', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Home', 'custom-banner-widget'),
                'condition' => [
                    'show_breadcrumb' => 'yes',
                    'breadcrumb_type' => 'auto',
                ],
            ]
        );

        $this->end_controls_section();

        // Title Section
        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__('Title', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title_text',
            [
                'label' => esc_html__('Title', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Page Title', 'custom-banner-widget'),
                'placeholder' => esc_html__('Enter your title', 'custom-banner-widget'),
            ]
        );

        $this->add_control(
            'title_auto_pull',
            [
                'label' => esc_html__('Auto Pull Page Title', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'custom-banner-widget'),
                'label_off' => esc_html__('No', 'custom-banner-widget'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Override above text with current page/post title', 'custom-banner-widget'),
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('HTML Tag', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
            ]
        );

        $this->end_controls_section();

        // Subtitle Section
        $this->start_controls_section(
            'section_subtitle',
            [
                'label' => esc_html__('Subtitle', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_subtitle',
            [
                'label' => esc_html__('Show Subtitle', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'custom-banner-widget'),
                'label_off' => esc_html__('Hide', 'custom-banner-widget'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'subtitle_text',
            [
                'label' => esc_html__('Subtitle', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Your subtitle text here', 'custom-banner-widget'),
                'placeholder' => esc_html__('Enter your subtitle', 'custom-banner-widget'),
                'condition' => [
                    'show_subtitle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'subtitle_tag',
            [
                'label' => esc_html__('HTML Tag', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'div',
                'options' => [
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'condition' => [
                    'show_subtitle' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ========================================
        // STYLE TAB
        // ========================================

        // Container Style Section
        $this->start_controls_section(
            'section_container_style',
            [
                'label' => esc_html__('Container', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_min_height',
            [
                'label' => esc_html__('Min Height', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-background' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 60,
                    'right' => 20,
                    'bottom' => 60,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => esc_html__('Margin', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .custom-banner-widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_vertical_align',
            [
                'label' => esc_html__('Vertical Alignment', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'custom-banner-widget'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'custom-banner-widget'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'custom-banner-widget'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .banner-content-wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_horizontal_align',
            [
                'label' => esc_html__('Horizontal Alignment', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'custom-banner-widget'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'custom-banner-widget'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'custom-banner-widget'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .banner-content-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_text_align',
            [
                'label' => esc_html__('Text Alignment', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'custom-banner-widget'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'custom-banner-widget'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'custom-banner-widget'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .banner-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_max_width',
            [
                'label' => esc_html__('Content Max Width', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 10,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-content' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_box_heading',
            [
                'label' => esc_html__('Content Box', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'label' => esc_html__('Background', 'custom-banner-widget'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .banner-content',
            ]
        );

        $this->add_responsive_control(
            'content_box_padding',
            [
                'label' => esc_html__('Padding', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_box_border',
                'label' => esc_html__('Border', 'custom-banner-widget'),
                'selector' => '{{WRAPPER}} .banner-content',
            ]
        );

        $this->add_responsive_control(
            'content_box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .banner-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Breadcrumb Style Section
        $this->start_controls_section(
            'section_breadcrumb_style',
            [
                'label' => esc_html__('Breadcrumb Style', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_breadcrumb' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'breadcrumb_typography',
                'label' => esc_html__('Typography', 'custom-banner-widget'),
                'selector' => '{{WRAPPER}} .banner-breadcrumb',
            ]
        );

        $this->add_control(
            'breadcrumb_color',
            [
                'label' => esc_html__('Color', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .banner-breadcrumb' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .banner-breadcrumb a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'breadcrumb_hover_color',
            [
                'label' => esc_html__('Hover Color', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .banner-breadcrumb a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'breadcrumb_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-breadcrumb' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title Style Section
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title Style', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'custom-banner-widget'),
                'selector' => '{{WRAPPER}} .banner-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .banner-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Hover Color', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .banner-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'label' => esc_html__('Text Shadow', 'custom-banner-widget'),
                'selector' => '{{WRAPPER}} .banner-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'title_text_stroke',
                'selector' => '{{WRAPPER}} .banner-title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__('Spacing', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .banner-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Subtitle Style Section
        $this->start_controls_section(
            'section_subtitle_style',
            [
                'label' => esc_html__('Subtitle Style', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_subtitle' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'label' => esc_html__('Typography', 'custom-banner-widget'),
                'selector' => '{{WRAPPER}} .banner-subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__('Color', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .banner-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'subtitle_text_shadow',
                'label' => esc_html__('Text Shadow', 'custom-banner-widget'),
                'selector' => '{{WRAPPER}} .banner-subtitle',
            ]
        );

        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label' => esc_html__('Spacing', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .banner-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Generate breadcrumb HTML
     */
    private function get_breadcrumb_html($settings) {
        if ($settings['breadcrumb_type'] === 'custom') {
            return '<span>' . esc_html($settings['breadcrumb_custom_text']) . '</span>';
        }

        // Auto-generate breadcrumb
        $breadcrumb = [];
        $separator = ' <span class="separator">' . esc_html($settings['breadcrumb_separator']) . '</span> ';
        $home_text = $settings['breadcrumb_home_text'] ? $settings['breadcrumb_home_text'] : esc_html__('Home', 'custom-banner-widget');

        // Home link
        $breadcrumb[] = '<a href="' . esc_url(home_url('/')) . '">' . esc_html($home_text) . '</a>';

        // Get current page/post
        if (is_singular()) {
            $post = get_post();

            // Add parent pages for hierarchical post types
            if ($post->post_parent) {
                $parent_id = $post->post_parent;
                $parents = [];

                while ($parent_id) {
                    $page = get_post($parent_id);
                    $parents[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html(get_the_title($page->ID)) . '</a>';
                    $parent_id = $page->post_parent;
                }

                $breadcrumb = array_merge($breadcrumb, array_reverse($parents));
            }

            // Add post type archive for non-page post types
            if ($post->post_type !== 'page') {
                $post_type_object = get_post_type_object($post->post_type);
                if ($post_type_object && $post_type_object->has_archive) {
                    $breadcrumb[] = '<a href="' . esc_url(get_post_type_archive_link($post->post_type)) . '">' . esc_html($post_type_object->labels->name) . '</a>';
                }

                // Add categories for posts
                if ($post->post_type === 'post') {
                    $categories = get_the_category($post->ID);
                    if (!empty($categories)) {
                        $category = $categories[0];
                        $breadcrumb[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                    }
                }
            }

            // Current page/post
            $breadcrumb[] = '<span class="current">' . esc_html(get_the_title()) . '</span>';
        } elseif (is_archive()) {
            $breadcrumb[] = '<span class="current">' . esc_html(get_the_archive_title()) . '</span>';
        } elseif (is_search()) {
            $breadcrumb[] = '<span class="current">' . esc_html__('Search Results', 'custom-banner-widget') . '</span>';
        } elseif (is_404()) {
            $breadcrumb[] = '<span class="current">' . esc_html__('404 Not Found', 'custom-banner-widget') . '</span>';
        }

        return implode($separator, $breadcrumb);
    }

    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Get background image URL
        $background_url = '';
        if ($settings['image_source'] === 'featured') {
            $background_url = $this->get_featured_image_url();

            // Use fallback if no featured image
            if (empty($background_url) && !empty($settings['fallback_image']['url'])) {
                $background_url = $settings['fallback_image']['url'];
            }
        } else {
            $background_url = $settings['custom_image']['url'];
        }

        // Get title text
        $title_text = $settings['title_text'];
        if ($settings['title_auto_pull'] === 'yes') {
            $title_text = get_the_title();
        }

        ?>
        <div class="custom-banner-widget">
            <div class="banner-background" style="background-image: url('<?php echo esc_url($background_url); ?>');" data-source="<?php echo esc_attr($settings['image_source']); ?>" role="img" aria-label="<?php echo esc_attr($title_text); ?>">
                <div class="banner-overlay"></div>
                <div class="banner-content-wrapper">
                    <div class="banner-content">
                        <?php if ($settings['show_breadcrumb'] === 'yes') : ?>
                            <nav class="banner-breadcrumb" aria-label="<?php echo esc_attr__('Breadcrumb', 'custom-banner-widget'); ?>">
                                <?php echo $this->get_breadcrumb_html($settings); ?>
                            </nav>
                        <?php endif; ?>

                        <<?php echo esc_attr($settings['title_tag']); ?> class="banner-title">
                            <?php echo esc_html($title_text); ?>
                        </<?php echo esc_attr($settings['title_tag']); ?>>

                        <?php if ($settings['show_subtitle'] === 'yes' && !empty($settings['subtitle_text'])) : ?>
                            <<?php echo esc_attr($settings['subtitle_tag']); ?> class="banner-subtitle">
                                <?php echo wp_kses_post($settings['subtitle_text']); ?>
                            </<?php echo esc_attr($settings['subtitle_tag']); ?>>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render widget output in the editor
     */
    protected function _content_template() {
        ?>
        <#
        var backgroundUrl = '';
        if (settings.image_source === 'featured') {
            // In editor, show placeholder for featured image
            backgroundUrl = settings.fallback_image.url || '<?php echo \Elementor\Utils::get_placeholder_image_src(); ?>';
        } else {
            backgroundUrl = settings.custom_image.url;
        }

        var titleText = settings.title_text;
        if (settings.title_auto_pull === 'yes') {
            titleText = '<?php echo esc_html(get_the_title()); ?>';
        }
        #>
        <div class="custom-banner-widget">
            <div class="banner-background" style="background-image: url('{{{ backgroundUrl }}}');" data-source="{{{ settings.image_source }}}">
                <div class="banner-overlay"></div>
                <div class="banner-content-wrapper">
                    <div class="banner-content">
                        <# if (settings.show_breadcrumb === 'yes') { #>
                            <nav class="banner-breadcrumb" aria-label="Breadcrumb">
                                <# if (settings.breadcrumb_type === 'custom') { #>
                                    <span>{{{ settings.breadcrumb_custom_text }}}</span>
                                <# } else { #>
                                    <a href="#">{{{ settings.breadcrumb_home_text }}}</a>
                                    <span class="separator">{{{ settings.breadcrumb_separator }}}</span>
                                    <span class="current">Current Page</span>
                                <# } #>
                            </nav>
                        <# } #>

                        <{{{ settings.title_tag }}} class="banner-title">
                            {{{ titleText }}}
                        </{{{ settings.title_tag }}}>

                        <# if (settings.show_subtitle === 'yes' && settings.subtitle_text) { #>
                            <{{{ settings.subtitle_tag }}} class="banner-subtitle">
                                {{{ settings.subtitle_text }}}
                            </{{{ settings.subtitle_tag }}}>
                        <# } #>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

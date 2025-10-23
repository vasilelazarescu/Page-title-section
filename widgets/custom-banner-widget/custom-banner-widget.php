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

        // CTA Button Section
        $this->start_controls_section(
            'section_cta_button',
            [
                'label' => esc_html__('CTA Button', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_button',
            [
                'label' => esc_html__('Show Button', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'custom-banner-widget'),
                'label_off' => esc_html__('Hide', 'custom-banner-widget'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Learn More', 'custom-banner-widget'),
                'placeholder' => esc_html__('Enter button text', 'custom-banner-widget'),
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => esc_html__('Link', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'custom-banner-widget'),
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => esc_html__('Icon', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_icon_position',
            [
                'label' => esc_html__('Icon Position', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'before' => esc_html__('Before', 'custom-banner-widget'),
                    'after' => esc_html__('After', 'custom-banner-widget'),
                ],
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_spacing',
            [
                'label' => esc_html__('Top Spacing', 'custom-banner-widget'),
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
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-button' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_button' => 'yes',
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

        $this->add_control(
            'banner_border_radius_heading',
            [
                'label' => esc_html__('Banner Container', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'banner_border_radius',
            [
                'label' => esc_html__('Border Radius', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .custom-banner-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                    '{{WRAPPER}} .banner-background' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        // Button Style Section
        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__('Button Style', 'custom-banner-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => esc_html__('Typography', 'custom-banner-widget'),
                'selector' => '{{WRAPPER}} .banner-button',
            ]
        );

        $this->start_controls_tabs('button_style_tabs');

        // Normal state
        $this->start_controls_tab(
            'button_normal',
            [
                'label' => esc_html__('Normal', 'custom-banner-widget'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .banner-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => esc_html__('Background', 'custom-banner-widget'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .banner-button',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#0073e6',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => esc_html__('Border', 'custom-banner-widget'),
                'selector' => '{{WRAPPER}} .banner-button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'label' => esc_html__('Box Shadow', 'custom-banner-widget'),
                'selector' => '{{WRAPPER}} .banner-button',
            ]
        );

        $this->end_controls_tab();

        // Hover state
        $this->start_controls_tab(
            'button_hover',
            [
                'label' => esc_html__('Hover', 'custom-banner-widget'),
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label' => esc_html__('Text Color', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .banner-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'label' => esc_html__('Background', 'custom-banner-widget'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .banner-button:hover',
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .banner-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow_hover',
                'label' => esc_html__('Box Shadow', 'custom-banner-widget'),
                'selector' => '{{WRAPPER}} .banner-button:hover',
            ]
        );

        $this->add_control(
            'button_hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'separator' => 'before',
                'default' => [
                    'top' => 12,
                    'right' => 24,
                    'bottom' => 12,
                    'left' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 4,
                    'right' => 4,
                    'bottom' => 4,
                    'left' => 4,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_spacing',
            [
                'label' => esc_html__('Icon Spacing', 'custom-banner-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-button .button-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .banner-button .button-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Generate breadcrumb HTML with proper taxonomy support
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

        // Handle different page types
        if (is_singular()) {
            $post = get_post();

            // Add post type archive for non-page post types
            if ($post->post_type !== 'page' && $post->post_type !== 'post') {
                $post_type_object = get_post_type_object($post->post_type);
                if ($post_type_object && $post_type_object->has_archive) {
                    $breadcrumb[] = '<a href="' . esc_url(get_post_type_archive_link($post->post_type)) . '">' . esc_html($post_type_object->labels->name) . '</a>';
                }
            }

            // Add taxonomy terms (categories, tags, custom taxonomies)
            $taxonomies = get_object_taxonomies($post->post_type, 'objects');
            $primary_taxonomy = null;

            // Find the primary taxonomy (category for posts, or first hierarchical taxonomy)
            foreach ($taxonomies as $taxonomy) {
                if (!$taxonomy->public || !$taxonomy->publicly_queryable) {
                    continue;
                }

                // Prefer 'category' for posts
                if ($post->post_type === 'post' && $taxonomy->name === 'category') {
                    $primary_taxonomy = $taxonomy;
                    break;
                }

                // Otherwise use first hierarchical taxonomy
                if (!$primary_taxonomy && $taxonomy->hierarchical) {
                    $primary_taxonomy = $taxonomy;
                }
            }

            // Add taxonomy hierarchy
            if ($primary_taxonomy) {
                $terms = get_the_terms($post->ID, $primary_taxonomy->name);
                if ($terms && !is_wp_error($terms)) {
                    // Get the most specific term (last in hierarchy)
                    $term = array_shift($terms);

                    // Build hierarchy of parent terms
                    $term_hierarchy = [];
                    while ($term) {
                        $term_hierarchy[] = $term;
                        $term = get_term($term->parent, $primary_taxonomy->name);
                        if (is_wp_error($term)) {
                            break;
                        }
                    }

                    // Add terms in correct order (parent to child)
                    foreach (array_reverse($term_hierarchy) as $ancestor_term) {
                        $breadcrumb[] = '<a href="' . esc_url(get_term_link($ancestor_term)) . '">' . esc_html($ancestor_term->name) . '</a>';
                    }
                }
            }

            // Add parent pages for hierarchical post types (like pages)
            if ($post->post_parent) {
                $parent_id = $post->post_parent;
                $parents = [];

                while ($parent_id) {
                    $page = get_post($parent_id);
                    if (!$page) {
                        break;
                    }
                    $parents[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html(get_the_title($page->ID)) . '</a>';
                    $parent_id = $page->post_parent;
                }

                $breadcrumb = array_merge($breadcrumb, array_reverse($parents));
            }

            // Note: Current page/post title is not added to avoid duplication with banner title

        } elseif (is_tax() || is_category() || is_tag()) {
            // Taxonomy archive pages
            $term = get_queried_object();

            if ($term) {
                // Add parent terms
                if ($term->parent) {
                    $parent_term = get_term($term->parent, $term->taxonomy);
                    $parent_hierarchy = [];

                    while ($parent_term && !is_wp_error($parent_term)) {
                        $parent_hierarchy[] = $parent_term;
                        $parent_term = $parent_term->parent ? get_term($parent_term->parent, $term->taxonomy) : null;
                    }

                    foreach (array_reverse($parent_hierarchy) as $ancestor) {
                        $breadcrumb[] = '<a href="' . esc_url(get_term_link($ancestor)) . '">' . esc_html($ancestor->name) . '</a>';
                    }
                }

                // Note: Current term is not added to avoid duplication with banner title
            }

        } elseif (is_post_type_archive()) {
            // Post type archive - no current item added to avoid duplication with banner title

        } elseif (is_search()) {
            // Search results - no current item added to avoid duplication with banner title

        } elseif (is_404()) {
            // 404 page - no current item added to avoid duplication with banner title

        } elseif (is_home() && !is_front_page()) {
            // Blog page - no current item added to avoid duplication with banner title
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

                        <?php if ($settings['show_button'] === 'yes' && !empty($settings['button_text'])) :
                            $button_link = $settings['button_link'];
                            $target = $button_link['is_external'] ? ' target="_blank"' : '';
                            $nofollow = $button_link['nofollow'] ? ' rel="nofollow"' : '';
                            $animation_class = !empty($settings['button_hover_animation']) ? ' elementor-animation-' . esc_attr($settings['button_hover_animation']) : '';
                        ?>
                            <div class="banner-button-wrapper">
                                <a href="<?php echo esc_url($button_link['url']); ?>" class="banner-button<?php echo $animation_class; ?>"<?php echo $target . $nofollow; ?>>
                                    <?php if (!empty($settings['button_icon']['value']) && $settings['button_icon_position'] === 'before') : ?>
                                        <span class="button-icon button-icon-before">
                                            <?php \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="button-text"><?php echo esc_html($settings['button_text']); ?></span>
                                    <?php if (!empty($settings['button_icon']['value']) && $settings['button_icon_position'] === 'after') : ?>
                                        <span class="button-icon button-icon-after">
                                            <?php \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true']); ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </div>
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
                                    <a href="#">Parent Page</a>
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

                        <# if (settings.show_button === 'yes' && settings.button_text) {
                            var target = settings.button_link.is_external ? ' target="_blank"' : '';
                            var nofollow = settings.button_link.nofollow ? ' rel="nofollow"' : '';
                            var animationClass = settings.button_hover_animation ? ' elementor-animation-' + settings.button_hover_animation : '';
                        #>
                            <div class="banner-button-wrapper">
                                <a href="{{{ settings.button_link.url }}}" class="banner-button{{{ animationClass }}}" {{{ target }}} {{{ nofollow }}}>
                                    <# if (settings.button_icon && settings.button_icon.value && settings.button_icon_position === 'before') { #>
                                        <span class="button-icon button-icon-before">
                                            <# if (settings.button_icon.library === 'svg') { #>
                                                {{{ settings.button_icon.value.url }}}
                                            <# } else { #>
                                                <i class="{{{ settings.button_icon.value }}}"></i>
                                            <# } #>
                                        </span>
                                    <# } #>
                                    <span class="button-text">{{{ settings.button_text }}}</span>
                                    <# if (settings.button_icon && settings.button_icon.value && settings.button_icon_position === 'after') { #>
                                        <span class="button-icon button-icon-after">
                                            <# if (settings.button_icon.library === 'svg') { #>
                                                {{{ settings.button_icon.value.url }}}
                                            <# } else { #>
                                                <i class="{{{ settings.button_icon.value }}}"></i>
                                            <# } #>
                                        </span>
                                    <# } #>
                                </a>
                            </div>
                        <# } #>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

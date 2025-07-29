<?php
/**
 * Elementor Ticket Form Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketFormWidget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'altalayi_ticket_form';
    }
    
    public function get_title() {
        return __('Ticket Form', 'altalayi-ticket');
    }
    
    public function get_icon() {
        return 'eicon-form-horizontal';
    }
    
    public function get_categories() {
        return ['altalayi-ticket'];
    }
    
    public function get_keywords() {
        return ['ticket', 'form', 'complaint', 'support'];
    }
    
    protected function _register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'altalayi-ticket'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'form_title',
            [
                'label' => __('Form Title', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Submit New Ticket', 'altalayi-ticket'),
                'placeholder' => __('Enter form title', 'altalayi-ticket'),
            ]
        );
        
        $this->add_control(
            'show_title',
            [
                'label' => __('Show Title', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'altalayi-ticket'),
                'label_off' => __('Hide', 'altalayi-ticket'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'success_message',
            [
                'label' => __('Success Message', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Your ticket has been submitted successfully. Please save your ticket number and phone number for future reference.', 'altalayi-ticket'),
                'placeholder' => __('Enter success message', 'altalayi-ticket'),
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'altalayi-ticket'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .altalayi-form-title' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .altalayi-form-title',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'form_background',
            [
                'label' => __('Form Background', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .altalayi-ticket-form' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'form_border',
                'selector' => '{{WRAPPER}} .altalayi-ticket-form',
            ]
        );
        
        $this->add_control(
            'form_border_radius',
            [
                'label' => __('Border Radius', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .altalayi-ticket-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'form_box_shadow',
                'selector' => '{{WRAPPER}} .altalayi-ticket-form',
            ]
        );
        
        $this->add_responsive_control(
            'form_padding',
            [
                'label' => __('Padding', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .altalayi-ticket-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Button Style Section
        $this->start_controls_section(
            'button_style_section',
            [
                'label' => __('Button Style', 'altalayi-ticket'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'button_background',
            [
                'label' => __('Background Color', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit-btn' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .submit-btn',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Enqueue necessary scripts and styles
        wp_enqueue_style('altalayi-frontend-css');
        wp_enqueue_script('altalayi-frontend-js');
        
        ?>
        <div class="altalayi-elementor-widget">
            <?php if ($settings['show_title'] === 'yes' && !empty($settings['form_title'])): ?>
                <h2 class="altalayi-form-title"><?php echo esc_html($settings['form_title']); ?></h2>
            <?php endif; ?>
            
            <div class="altalayi-ticket-form">
                <?php 
                // Use shortcode to render the form
                echo do_shortcode('[altalayi_ticket_form show_title="false" success_message="' . esc_attr($settings['success_message']) . '"]');
                ?>
            </div>
        </div>
        
        <style>
        .altalayi-elementor-widget {
            width: 100%;
        }
        
        .altalayi-elementor-widget .altalayi-ticket-form {
            max-width: 100%;
        }
        
        .altalayi-elementor-widget .form-container {
            background: transparent;
            box-shadow: none;
            border: none;
            padding: 0;
        }
        
        /* Ensure forms are responsive in Elementor */
        .altalayi-elementor-widget .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        @media (min-width: 768px) {
            .altalayi-elementor-widget .form-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        </style>
        <?php
    }
    
    protected function content_template() {
        ?>
        <#
        var showTitle = settings.show_title === 'yes';
        #>
        <div class="altalayi-elementor-widget">
            <# if (showTitle && settings.form_title) { #>
                <h2 class="altalayi-form-title">{{{ settings.form_title }}}</h2>
            <# } #>
            
            <div class="altalayi-ticket-form">
                <div class="elementor-preview-notice">
                    <i class="eicon-info-circle"></i>
                    <?php _e('Ticket Form - This will display the actual form on the frontend', 'altalayi-ticket'); ?>
                </div>
            </div>
        </div>
        <?php
    }
}

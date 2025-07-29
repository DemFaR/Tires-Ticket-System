<?php
/**
 * Elementor Ticket View Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketViewWidget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'altalayi_ticket_view';
    }
    
    public function get_title() {
        return __('Ticket View', 'altalayi-ticket');
    }
    
    public function get_icon() {
        return 'eicon-document-file';
    }
    
    public function get_categories() {
        return ['altalayi-ticket'];
    }
    
    public function get_keywords() {
        return ['ticket', 'view', 'display', 'support'];
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
            'ticket_source',
            [
                'label' => __('Ticket Source', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto_detect',
                'options' => [
                    'auto_detect' => __('Auto Detect (from session/URL)', 'altalayi-ticket'),
                    'manual' => __('Manual Input', 'altalayi-ticket'),
                ],
            ]
        );
        
        $this->add_control(
            'ticket_number',
            [
                'label' => __('Ticket Number', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('ALT-2025-XXXXX', 'altalayi-ticket'),
                'condition' => [
                    'ticket_source' => 'manual',
                ],
            ]
        );
        
        $this->add_control(
            'phone_number',
            [
                'label' => __('Phone Number', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Customer phone number', 'altalayi-ticket'),
                'condition' => [
                    'ticket_source' => 'manual',
                ],
            ]
        );
        
        $this->add_control(
            'show_title',
            [
                'label' => __('Show Ticket Title', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'altalayi-ticket'),
                'label_off' => __('Hide', 'altalayi-ticket'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'sections_to_show',
            [
                'label' => __('Sections to Show', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => ['customer_info', 'tire_info', 'complaint', 'attachments', 'updates'],
                'options' => [
                    'customer_info' => __('Customer Information', 'altalayi-ticket'),
                    'tire_info' => __('Tire Information', 'altalayi-ticket'),
                    'complaint' => __('Complaint Details', 'altalayi-ticket'),
                    'attachments' => __('Attachments', 'altalayi-ticket'),
                    'employee_responses' => __('Employee Responses', 'altalayi-ticket'),
                    'updates' => __('Ticket Updates', 'altalayi-ticket'),
                    'response_form' => __('Customer Response Form', 'altalayi-ticket'),
                ],
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
            'container_background',
            [
                'label' => __('Container Background', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .altalayi-ticket-view-container' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .altalayi-ticket-view-container',
            ]
        );
        
        $this->add_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .altalayi-ticket-view-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .altalayi-ticket-view-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Header Style Section
        $this->start_controls_section(
            'header_style_section',
            [
                'label' => __('Header Style', 'altalayi-ticket'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'header_background',
            [
                'label' => __('Header Background', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ticket-header' => 'background: {{VALUE}} !important',
                ],
            ]
        );
        
        $this->add_control(
            'header_text_color',
            [
                'label' => __('Header Text Color', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ticket-header' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .ticket-header h1' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Enqueue necessary scripts and styles
        wp_enqueue_style('altalayi-frontend-css');
        wp_enqueue_script('altalayi-frontend-js');
        
        // Prepare shortcode attributes
        $shortcode_atts = [];
        
        if ($settings['ticket_source'] === 'manual') {
            $shortcode_atts[] = 'ticket_number="' . esc_attr($settings['ticket_number']) . '"';
            $shortcode_atts[] = 'phone="' . esc_attr($settings['phone_number']) . '"';
            $shortcode_atts[] = 'auto_detect="false"';
        } else {
            $shortcode_atts[] = 'auto_detect="true"';
        }
        
        $shortcode_atts[] = 'show_title="' . ($settings['show_title'] === 'yes' ? 'true' : 'false') . '"';
        $shortcode_atts[] = 'container_class="altalayi-elementor-widget"';
        
        ?>
        <div class="altalayi-elementor-ticket-view">
            <?php 
            // Use shortcode to render the ticket view
            echo do_shortcode('[altalayi_ticket_view ' . implode(' ', $shortcode_atts) . ']');
            ?>
        </div>
        
        <style>
        .altalayi-elementor-ticket-view {
            width: 100%;
        }
        
        .altalayi-elementor-ticket-view .altalayi-ticket-view-container {
            max-width: 100%;
            margin: 0;
        }
        
        /* Hide sections based on widget settings */
        <?php 
        $sections_to_show = $settings['sections_to_show'];
        $all_sections = ['customer_info', 'tire_info', 'complaint', 'attachments', 'employee_responses', 'updates', 'response_form'];
        
        foreach ($all_sections as $section) {
            if (!in_array($section, $sections_to_show)) {
                $selector = '';
                switch ($section) {
                    case 'customer_info':
                        $selector = '.info-section:has(h2:contains("Customer Information"))';
                        break;
                    case 'tire_info':
                        $selector = '.info-section:has(h2:contains("Tire Information"))';
                        break;
                    case 'complaint':
                        $selector = '.info-section:has(h2:contains("Complaint Details"))';
                        break;
                    case 'attachments':
                        $selector = '.info-section:has(h2:contains("Attachments"))';
                        break;
                    case 'employee_responses':
                        $selector = '.info-section:has(h2:contains("Employee Responses"))';
                        break;
                    case 'updates':
                        $selector = '.info-section:has(h2:contains("Ticket Updates"))';
                        break;
                    case 'response_form':
                        $selector = '.customer-response-section';
                        break;
                }
                if ($selector) {
                    echo "{{WRAPPER}} $selector { display: none !important; }\n";
                }
            }
        }
        ?>
        </style>
        <?php
    }
    
    protected function content_template() {
        ?>
        <div class="altalayi-elementor-ticket-view">
            <div class="elementor-preview-notice">
                <i class="eicon-info-circle"></i>
                <?php _e('Ticket View - This will display the actual ticket details on the frontend', 'altalayi-ticket'); ?>
                <br>
                <small>
                    <# if (settings.ticket_source === 'manual' && settings.ticket_number) { #>
                        <?php _e('Ticket:', 'altalayi-ticket'); ?> {{{ settings.ticket_number }}}
                    <# } else { #>
                        <?php _e('Auto-detecting ticket from session or URL parameters', 'altalayi-ticket'); ?>
                    <# } #>
                </small>
            </div>
        </div>
        <?php
    }
}

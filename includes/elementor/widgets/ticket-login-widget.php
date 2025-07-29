<?php
/**
 * Elementor Ticket Login Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketLoginWidget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'altalayi_ticket_login';
    }
    
    public function get_title() {
        return __('Ticket Login', 'altalayi-ticket');
    }
    
    public function get_icon() {
        return 'eicon-lock-user';
    }
    
    public function get_categories() {
        return ['altalayi-ticket'];
    }
    
    public function get_keywords() {
        return ['ticket', 'login', 'access', 'support'];
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
            'login_title',
            [
                'label' => __('Login Title', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Access Your Ticket', 'altalayi-ticket'),
                'placeholder' => __('Enter login title', 'altalayi-ticket'),
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
            'show_create_link',
            [
                'label' => __('Show "Create New Ticket" Link', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'altalayi-ticket'),
                'label_off' => __('Hide', 'altalayi-ticket'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'redirect_after_login',
            [
                'label' => __('Redirect After Login', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ticket_page',
                'options' => [
                    'ticket_page' => __('Ticket Page', 'altalayi-ticket'),
                    'same_page' => __('Same Page', 'altalayi-ticket'),
                    'custom_url' => __('Custom URL', 'altalayi-ticket'),
                ],
            ]
        );
        
        $this->add_control(
            'custom_redirect_url',
            [
                'label' => __('Custom Redirect URL', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-site.com/ticket-page', 'altalayi-ticket'),
                'condition' => [
                    'redirect_after_login' => 'custom_url',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section (similar to form widget)
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
                    '{{WRAPPER}} .altalayi-login-title' => 'color: {{VALUE}}',
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
                'selector' => '{{WRAPPER}} .altalayi-login-title',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'form_background',
            [
                'label' => __('Background Color', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .altalayi-ticket-login' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'form_border',
                'selector' => '{{WRAPPER}} .altalayi-ticket-login',
            ]
        );
        
        $this->add_responsive_control(
            'form_padding',
            [
                'label' => __('Padding', 'altalayi-ticket'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .altalayi-ticket-login' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        
        ?>
        <div class="altalayi-elementor-widget">
            <?php if ($settings['show_title'] === 'yes' && !empty($settings['login_title'])): ?>
                <h2 class="altalayi-login-title"><?php echo esc_html($settings['login_title']); ?></h2>
            <?php endif; ?>
            
            <div class="altalayi-ticket-login">
                <?php 
                // Use shortcode to render the login form
                $shortcode_atts = 'show_title="false"';
                $shortcode_atts .= ' redirect_after_login="' . esc_attr($settings['redirect_after_login']) . '"';
                
                echo do_shortcode('[altalayi_ticket_login ' . $shortcode_atts . ']');
                ?>
            </div>
        </div>
        
        <style>
        .altalayi-elementor-widget {
            width: 100%;
        }
        
        .altalayi-elementor-widget .altalayi-ticket-login {
            max-width: 100%;
        }
        
        .altalayi-elementor-widget .login-wrapper {
            background: transparent;
            box-shadow: none;
            border: none;
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
            <# if (showTitle && settings.login_title) { #>
                <h2 class="altalayi-login-title">{{{ settings.login_title }}}</h2>
            <# } #>
            
            <div class="altalayi-ticket-login">
                <div class="elementor-preview-notice">
                    <i class="eicon-info-circle"></i>
                    <?php _e('Ticket Login Form - This will display the actual login form on the frontend', 'altalayi-ticket'); ?>
                </div>
            </div>
        </div>
        <?php
    }
}

<?php
/**
 * Page template for Altalayi Ticket System custom pages
 */

get_header(); ?>

<div class="altalayi-ticket-page">
    <div class="container">
        <?php
        $action = get_query_var('altalayi_ticket_action');
        
        switch ($action) {
            case 'view':
                include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-view.php';
                break;
            case 'login':
                include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-login.php';
                break;
            case 'create':
                include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-create.php';
                break;
            default:
                echo '<h1>' . __('Page not found', 'altalayi-ticket') . '</h1>';
                echo '<p>' . __('The requested page could not be found.', 'altalayi-ticket') . '</p>';
        }
        ?>
    </div>
</div>

<style>
.altalayi-ticket-page {
    padding: 20px 0;
}

.altalayi-ticket-page .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Ensure our styles take precedence */
.altalayi-ticket-page * {
    box-sizing: border-box;
}
</style>

<?php get_footer(); ?>

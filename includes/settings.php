<?php
function elementor_calendar_embed_settings_page() {
    add_menu_page(
        'Calendar Embed Settings',
        'Calendar Embed',
        'manage_options',
        'calendar-embed-settings',
        'elementor_calendar_embed_settings_html',
        'dashicons-calendar',
        100
    );
}
add_action('admin_menu', 'elementor_calendar_embed_settings_page');

function elementor_calendar_embed_settings_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Display a notice about GitHub Updater
    echo '<div class="notice notice-info">';
    echo '<p>To enable auto-updates for this plugin, install the <a href="https://github.com/afragen/github-updater" target="_blank">GitHub Updater</a> plugin.</p>';
    echo '</div>';    
    
    
    if (isset($_POST['elementor_calendar_embed_options'])) {
        update_option('google_calendar_api_key', sanitize_text_field($_POST['google_calendar_api_key']));
        update_option('microsoft_calendar_client_id', sanitize_text_field($_POST['microsoft_calendar_client_id']));
        update_option('calendly_api_key', sanitize_text_field($_POST['calendly_api_key']));
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }

    $google_calendar_api_key = get_option('google_calendar_api_key', '');
    $microsoft_calendar_client_id = get_option('microsoft_calendar_client_id', '');
    $calendly_api_key = get_option('calendly_api_key', '');

    ?>
    <div class="wrap">
        <h1>Calendar Embed Settings</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row">Google Calendar API Key</th>
                    <td>
                        <input type="text" name="google_calendar_api_key" value="<?php echo esc_attr($google_calendar_api_key); ?>" class="regular-text">
                        <p class="description">Get your Google Calendar API key from the <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a>.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Microsoft Calendar Client ID</th>
                    <td>
                        <input type="text" name="microsoft_calendar_client_id" value="<?php echo esc_attr($microsoft_calendar_client_id); ?>" class="regular-text">
                        <p class="description">Get your Microsoft Calendar Client ID from the <a href="https://portal.azure.com/" target="_blank">Azure Portal</a>.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Calendly API Key</th>
                    <td>
                        <input type="text" name="calendly_api_key" value="<?php echo esc_attr($calendly_api_key); ?>" class="regular-text">
                        <p class="description">Get your Calendly API key from your <a href="https://calendly.com/integrations/api_webhooks" target="_blank">Calendly Integrations</a> page.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}

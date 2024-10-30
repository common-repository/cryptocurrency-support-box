<?php
/**
 * Plugin Name: Crypto Support Box
 * Plugin URI: https://aiscores.com/crypto-donation-widget-guide
 * Description: A simple, effective plugin to enable cryptocurrency donations on your WordPress site.
 * Version: 1.4
 * Author: AI Scores
 * Author URI: https://aiscores.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
// Include the display function file
require_once plugin_dir_path(__FILE__) . 'shortcode-display.php';

function crypto_support_box_admin_enqueue_scripts($hook) {
    global $csb_page_hook_suffix;  // Ensure you have defined this variable in the scope where you add the admin page.

    // Check if the current page is your plugin's admin page.
    if ($hook === $csb_page_hook_suffix) {
        // Properly enqueue your admin JavaScript file
        wp_enqueue_script('crypto-support-box-admin-js', plugins_url('/admin/admin-script.js', __FILE__), array('jquery'), '1.4', true);
        wp_enqueue_script('crypto-support-box-js', plugins_url('/js/main.js', __FILE__), array('qrcode-js', 'jquery'), '1.4', true);       
        wp_enqueue_style('crypto-support-box-css', plugins_url('/css/style.css', __FILE__), array(), '1.4');        
    }
}

// Enqueue plugin styles and scripts
function crypto_support_box_enqueue_scripts() {
    wp_enqueue_style('crypto-support-box-css', plugins_url('/css/style.css', __FILE__), array(), '1.4');
    wp_enqueue_script('qrcode-js', plugins_url('/js/lib/qrcode.min.js', __FILE__), array(), '1.4', true);
    wp_enqueue_script('crypto-support-box-js', plugins_url('/js/main.js', __FILE__), array('jquery'), '1.4', true);
    $translation_array = array(
        'plugin_url' => plugin_dir_url(__FILE__),
        'bitcoin_address' => get_option('crypto_bitcoin_address'),
        'ethereum_address' => get_option('crypto_ethereum_address'),
        'solana_address' => get_option('crypto_solana_address'),
        'bitcoin_qr_url' => get_option('crypto_bitcoin_qr_url'),
        'ethereum_qr_url' => get_option('crypto_ethereum_qr_url'),
        'solana_qr_url' => get_option('crypto_solana_qr_url'),
        'default_crypto' => get_option('default_crypto', 'solana'),
        'default_theme' => get_option('default_theme', 'light')
    );
    wp_localize_script('crypto-support-box-js', 'crypto_addresses', $translation_array);
}
add_action('wp_enqueue_scripts', 'crypto_support_box_enqueue_scripts');

// Register the shortcode to display the donation box
function crypto_support_box_shortcode($atts) {
    return crypto_support_box_display();
}
add_shortcode('crypto_support_box', 'crypto_support_box_shortcode');

// Admin menu
function crypto_support_box_admin_menu() {
    global $csb_page_hook_suffix;
    $csb_page_hook_suffix = add_menu_page('Cryptocurrency Donation Box Settings', 'Crypto Support Box', 'manage_options', 'crypto-support-box', 'crypto_support_box_admin_page', 'dashicons-money-alt');
    
    // Use the returned hook suffix to add the enqueue
    add_action('admin_enqueue_scripts', 'crypto_support_box_admin_enqueue_scripts');
}
add_action('admin_menu', 'crypto_support_box_admin_menu');

function crypto_support_box_register_settings() {
    // Register a setting for each input field
    register_setting('crypto_support_box_options', 'crypto_title');
    register_setting('crypto_support_box_options', 'crypto_bitcoin_address');
    register_setting('crypto_support_box_options', 'crypto_bitcoin_qr_url');
    register_setting('crypto_support_box_options', 'crypto_ethereum_address');
    register_setting('crypto_support_box_options', 'crypto_ethereum_qr_url');
    register_setting('crypto_support_box_options', 'crypto_solana_address');
    register_setting('crypto_support_box_options', 'crypto_solana_qr_url');
    register_setting('crypto_support_box_options', 'default_crypto');
    register_setting('crypto_support_box_options', 'default_theme');

    // Section to show the shortcode
    add_settings_section('crypto_support_box_shortcode_section', 'Use Shortcode to Display the Box', 'display_shortcode_with_copy_button', 'crypto_support_box');
    
    // Section for the settings
    add_settings_section('crypto_support_box_section', 'Cryptocurrency Settings', 'crypto_support_box_section_callback', 'crypto_support_box');
    
    // Fields for each setting
    add_settings_field('crypto_title', 'Crypto Support Box Title', 'crypto_support_box_field_callback', 'crypto_support_box', 'crypto_support_box_section', array('label_for' => 'crypto_title', 'default' => 'Crypto Donations'));
    add_settings_field('crypto_bitcoin_address', 'Bitcoin Address', 'crypto_support_box_field_callback', 'crypto_support_box', 'crypto_support_box_section', array('label_for' => 'crypto_bitcoin_address'));
    add_settings_field('crypto_bitcoin_qr_url', 'Bitcoin QR Code URL (optional)', 'crypto_support_box_field_callback', 'crypto_support_box', 'crypto_support_box_section', array('label_for' => 'crypto_bitcoin_qr_url'));
    add_settings_field('crypto_ethereum_address', 'Ethereum Address', 'crypto_support_box_field_callback', 'crypto_support_box', 'crypto_support_box_section', array('label_for' => 'crypto_ethereum_address'));
    add_settings_field('crypto_ethereum_qr_url', 'Ethereum QR Code URL (optional)', 'crypto_support_box_field_callback', 'crypto_support_box', 'crypto_support_box_section', array('label_for' => 'crypto_ethereum_qr_url'));
    add_settings_field('crypto_solana_address', 'Solana Address', 'crypto_support_box_field_callback', 'crypto_support_box', 'crypto_support_box_section', array('label_for' => 'crypto_solana_address'));
    add_settings_field('crypto_solana_qr_url', 'Solana QR Code URL (optional)', 'crypto_support_box_field_callback', 'crypto_support_box', 'crypto_support_box_section', array('label_for' => 'crypto_solana_qr_url'));
    add_settings_field('default_crypto', 'Default Support Currency', 'crypto_support_box_currency_field_callback', 'crypto_support_box', 'crypto_support_box_section', array('label_for' => 'default_crypto'));
    add_settings_field('default_theme', 'Default Theme', 'crypto_support_box_theme_field_callback', 'crypto_support_box', 'crypto_support_box_section', array('label_for' => 'default_theme'));
}
add_action('admin_init', 'crypto_support_box_register_settings');

function crypto_support_box_currency_field_callback() {
    $default_crypto = get_option('default_crypto', 'solana');
    $options = [
        'bitcoin' => 'Bitcoin',
        'ethereum' => 'Ethereum',
        'solana' => 'Solana'
    ];
    ?>
    <select name="default_crypto" id="default_crypto">
        <?php foreach ($options as $value => $label) : ?>
            <option value="<?php echo esc_attr($value); ?>" <?php selected($value, $default_crypto); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

function crypto_support_box_theme_field_callback($args) {
    $option = get_option('default_theme', 'light');
    ?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" name="default_theme">
        <option value="light" <?php selected($option, 'light'); ?>>Light</option>
        <option value="dark" <?php selected($option, 'dark'); ?>>Dark</option>
    </select>
    <?php
}

function crypto_support_box_section_callback() {
    echo '<p>Enter your cryptocurrency address details and the title of the widget. If you need any further help check the <a href="https://aiscores.com/crypto-donation-widget-guide" target="_blank">visual guide</a> for this plugin. If you have any questions, suggestions, or feedback, don\'t hesitate to contact the developer using this <a href="https://joelbooks.com/contact/?topic=Crypto%20Support%20Box" target="_blank">link</a>.</p>';
}

function display_shortcode_with_copy_button() {
    echo '<div class="shortcode-container">';
    echo '<code id="crypto-shortcode">[crypto_support_box]</code>';
    echo '<button class="copy-shortcode-button" style="margin-left:10px;" onclick="copyCodeToClipboard(this, \'crypto-shortcode\')">';
    echo '<span class="copy-code-icon">📋 Copy</span>';
    echo '<span class="done-code-icon" style="display:none;">✔️ Copied</span>';
    echo '</button>';
    echo '</div>';
}

function crypto_support_box_field_callback($args) {
    $option = get_option($args['label_for']);
    echo "<input type='text' id='" . esc_attr($args['label_for']) . "' name='" . esc_attr($args['label_for']) . "' value='" . esc_attr($option) . "' style='width: 100%;'/>";
}

// Admin page view
function crypto_support_box_admin_page() {
    include_once plugin_dir_path(__FILE__) . 'admin/admin-page.php';
}

// Add settings link on the plugins page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'crypto_support_box_action_links');

function crypto_support_box_action_links($links) {
    $settings_link = '<a href="admin.php?page=crypto-support-box">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
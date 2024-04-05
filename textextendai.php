<?php
/**
 * Plugin Name: TextExtendAI
 * Description: Extend text in the block editor using AI
 * Version: 2.0.1
 * Author: Raymond Lowe
 * License: GPL2
 * URL: https://github.com/raymondclowe/TextExtendAI
  */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function textextendai_enqueue_scripts() {

  wp_enqueue_script( 'textextendai', plugins_url( 'textextendai.js', __FILE__ ) );
  wp_localize_script('textextendai', 'textextendai', array(
    'api_nonce' => wp_create_nonce('wp_rest'),
  ));

}

add_action('enqueue_block_editor_assets', 'textextendai_enqueue_scripts');
add_action('admin_menu', 'textextendai_admin_menu');

function textextendai_admin_menu()
{
  add_options_page('TextExtendAI Settings', 'TextExtendAI', 'manage_options', 'textextendai', 'textextendai_settings_page');
}
function textextendai_settings_page()
{
  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
  }
  echo '<div class="wrap">';
  echo '<h1>TextExtendAI Settings</h1>';
  echo '<form method="post" action="options.php">';
  settings_fields('textextendai_options');
  do_settings_sections('textextendai');
  submit_button();
  echo '</form>';
  echo '</div>';
}

add_action('admin_init', 'textextendai_admin_init');

function textextendai_admin_init()
{
  register_setting('textextendai_options', 'apikey', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field','default' => 'sk-YOURAPIKEY',));  
  register_setting('textextendai_options', 'aimodel', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => 'mistral-tiny', ) );
  add_settings_section(
    'textextendai_main',
    'Main Settings',
    'textextendai_section_text',
    'textextendai'
  );
  add_settings_field(
    'apikey',
    'API Key',
    'textextendai_apikey_callback',
    'textextendai',
    'textextendai_main'
  );

  add_settings_field(
    'aimodel',
    'AI Model Name',
    'textextendai_aimodel_callback',
    'textextendai', 
    'textextendai_main'
  );
}

function textextendai_section_text()
{
  echo '<p>Configure TextExtendAI settings</p>';
}
function textextendai_apikey_callback()
{
  $value = get_option('apikey');
  echo '<input type="text" id="apikey" name="apikey" value="' . esc_attr($value) . '" size="40" />';
}

function textextendai_aimodel_callback() {
  $value = get_option('aimodel');
  $options = array(
    'open-mistral-7b' => 'Mistral 7B ($0.25/1M tokens Input, $0.25/1M tokens Output)',  
    'open-mixtral-8x7b' => 'Mixtral 8x7B ($0.7/1M tokens Input, $0.7/1M tokens Output)',
    'mistral-small-latest' => 'Mistral Small ($2/1M tokens Input, $6/1M tokens Output)',
    'mistral-medium-latest' => 'Mistral Medium ($2.7/1M tokens Input, $8.1/1M tokens Output)',
    'mistral-large-latest' => 'Mistral Large ($8/1M tokens Input, $24/1M tokens Output)' 
  );
  echo '<select name="aimodel" id="aimodel">';
  foreach ($options as $key => $label) {
    echo '<option value="' . $key . '"';
    if ($key == $value) echo ' selected="selected"';
    echo '>' . $label . '</option>';
  }
  echo '</select>';
}

// Add REST API route
add_action('rest_api_init', function () {
  register_rest_route('textextendai/v1', '/apikey', array (
    'methods' => 'GET',
    'callback' => 'textextendai_get_api_key',
    'permission_callback' => function ($request) {
      return current_user_can('edit_posts');
    },
  )
  );
});

// API key callback  
function textextendai_get_api_key()
{
  return array(
    'apikey' => htmlspecialchars(get_option('apikey')),    
    'aimodel' => htmlspecialchars(get_option('aimodel')),
  );
}

// Add action for after textextendai settings are saved
add_action( 'admin_notices', 'textextendai_admin_notice' );

function textextendai_admin_notice() {

  if( isset($_GET['page']) && $_GET['page'] == 'textextendai' ) {

    if( isset($_GET['settings-updated']) ) {

      echo '<div class="notice notice-success">';
      echo '<p>Settings saved! Please reload any open editor windows (Ctrl-Refresh) for changes to take effect.</p>';  
      echo '</div>';

    }

  }

}

// allow REST password authentication even with no ssl (so dev environment works)
// add_filter( 'wp_is_application_passwords_available', '__return_true' );

?>
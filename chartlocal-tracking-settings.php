<?php

add_action('admin_menu', 'clt_add_admin_menu');
add_action('admin_init', 'clt_settings_init');

function clt_add_admin_menu() {
  add_menu_page('ChartLocal', 'ChartLocal', 'administrator', __FILE__, 'clt_options_page', 'dashicons-chart-pie');
}

function clt_settings_init() {
  register_setting('clt_settings', 'clt_tracking_code_id', 'clt_validate_tracking_code_id');

  add_settings_section(
    'clt_tracking_code_section',
    __('Chartlocal Tracking', 'wordpress'), 
    'clt_settings_section_callback',
    'clt_settings'
  );

  add_settings_field( 
    'clt_tracking_code_id',
    __('ID', 'wordpress'), 
    'clt_tracking_code_id_render',
    'clt_settings',
    'clt_tracking_code_section'
  );
}


function clt_settings_section_callback() {
?>
  <p>Need help finding your ChartLocal Site ID?</p>
  <ol>
    <li>Sign into ChartLocal account.</li>
    <li>Navigate to Settings tab, and click on 'Tracking Code'.</li>
    <li>Copy the Tracking Code ID out of your tracking code snippet. It should look something like: d4098273-6c87-4672-9f5e-94bcabf5597a <strong>Note:</strong> Do not use the example tracking code id as it will not work properly.</li>
  </ol>
  <p>If you have difficulty with this step or cannot find your Tracking ID, please contact your ChartLocal account representative.</p>
<?php
}

function clt_tracking_code_id_render() {
  echo '<input name="clt_tracking_code_id" id="clt_tracking_code_id" class="regular-text code" type="text" value="' . esc_attr(get_option('clt_tracking_code_id')) . '" />';
}

function clt_options_page() {
?>
  <form action='options.php' method='post'>
    
<?php
    settings_fields('clt_settings');
    settings_errors('general');
    do_settings_sections('clt_settings');
    submit_button();
?>
    
  </form>
<?php
}

function clt_validate_tracking_code_id($guid) {
  if (empty($guid) || preg_match('/^[A-Z0-9]{8}(-[A-Z0-9]{4}){3}-[A-Z0-9]{12}$/i', $guid)) {
    return $guid;
  }

  add_settings_error(
    'general',
    'invalid-tracking_code_id',
    'Tracking code ID is invalid.',
    'error'
  );

  return get_option('clt_tracking_code_id') ? get_option('clt_tracking_code_id' ) : '';
}

<?php
/*
Plugin Name: OneScreen Toolkit for WordPress
Plugin URI: http://wordpress.org/plugins/onescreen-video
Description: Utilize the [onescreen] shortcode to embed OneScreen's video player. *UPDATE: Now requires user generated token from www.mediagraph.com account to work
Version: 1.4
Author: OneScreen Inc.
Author URI: http://onescreen.com
*/

class OS_Shortcode {
    public $options;
    private $token;

    public function __construct() {
        // get plugin options - account id & passthrough playlist id
        $this->options = get_option('os_plugin_options');

        // add a filter so that shortcodes are executed in widget areas
        add_filter('widget_text', 'do_shortcode');

        // add our shortcode so wordpress recognizes "[onescreen]"
        add_shortcode('onescreen', array($this, 'shortcode'));

        // remove our shortcode from RSS2 feeds
        add_action('do_feed_rss2', array($this, 'removeShortcodeFomRss'), 2);

        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_init', array($this, 'register_settings_and_fields'));

        // Initialize token
        $this->token = $this->options['os_mediagraph_token_setting'];
    }

    public function removeShortcodeFomRss($shortcode) {
        remove_shortcode('onescreen');
    }

    private function target_div_counter() { 
        STATIC $count = 0;
        $count++;
        return $count;
    }

    public function shortcode($attributes) {

        if ( empty( $attributes['widget_id']) ) $attributes['widget_id'] = $this->options['os_default_widget_id_setting'];
        if ( empty( $attributes['target_div'] ) ) $attributes['target_div'] = "os_dmp_embed_" . $this->target_div_counter();

        // pulls out all the keys and uses those as variables for the values
        extract( $attributes );

        if (empty($widget_id)) return;

        // Assign correct onescreen js to use (1.9, 2.0, etc..)
        $scripts_to_include = array();
        if ( empty( $apps_js ) ){
            $default_onescreen_js = $this->options['os_default_onescreen_js'];
            if ( $default_onescreen_js == '2.0' ) $scripts_to_include['os_default_onescreen_js'] = 'http://cdn.onescreen.net/os/static/apps/2.0/_onescreen.js';
            else $scripts_to_include['os_default_onescreen_js'] = 'http://cdn.onescreen.net/os/static/apps/s/_onescreen.js';
        }
        else{
            $scripts_to_include['os_default_onescreen_js'] = 'http://cdn.onescreen.net/os/static/apps/s/_onescreen.js';
            if ( $apps_js == '2.0' )  $scripts_to_include['os_default_onescreen_js'] = 'http://cdn.onescreen.net/os/static/apps/2.0/_onescreen.js';
        }

        // if attempting to use passthrough playlist or override "media" property
        if (!empty($item) || !empty($playlist_id)){
            // VERIFICATION
            $token = $this->token;
            $account = $this->os_authenticate_token();
 
            // Error Handler
            if ( empty($token) ) return '<h4><span style="color:red;">ERROR:</span> Please Enter Account Token under OneScreen Account Settings. If you do not have an account yet, you can sign up at <a href="http://www.onescreen.com" target="_blank">www.onescreen.com</a></h4>';
            else if (property_exists($account, 'error_message')) return os_test($account->error_message);
            else if (is_wp_error($account)) return os_test('!OneScreen API Temporarily Unavailable');

            // Set up passthrough uri
            $passthrough_uri = 'http://data.onescreen.net/playlists/' . $account->id . '/' . $account->passthrough_widget_id . '/' . $account->passthrough_destination_id . '/' . $account->passthrough_playlist_id . '.xml?plf=os';

            // set media (either item or playlist)
             if (!empty($item)){
                    $item = str_replace(' ', '', $item);
                    $media = $passthrough_uri . '&itm=' . $item;
                }
                else if (!empty($playlist_id)) $media = str_replace($account->passthrough_playlist_id, $playlist_id, $passthrough_uri);
        }

        if (!empty($helper_js)) {
            $scripts_to_include['os_widget_helper'] = $helper_js;
        }

        foreach ($scripts_to_include as $handler => $script_src) {
            wp_register_script($handler, $script_src);
            wp_enqueue_script($handler, $script_src, false, false, true);
        }

        if (!empty($companion_target)) {
            if (empty($force_companions)) {
                $attributes['force_companions'] = $force_companions = 'true';
            }
            $attributes['companion_target'] = $companion_target = json_encode(array( array("id" => $companion_target, "width" => "300", "height" => "250") ));
        }
 
        // backwards compatibility for widget_id => app_id
        // if app_id exists, then overwrite current widget_id value with app_id value
        if (!empty($app_id)){$attributes['widget_id'] = $widget_id = $app_id;}

        // data attribute media
        if (isset($media)) $passthrough_url = 'data-media="' . $media . '"';
        else $passthrough_url = '';

        $target_div = '<div id="'.$target_div.'" class="load-os-app" '.attributes_to_data($attributes, $passthrough_url).' version="1.4 Beta"></div>';

        return $target_div;
        $count++;
    }

    public function add_menu_page() {
        add_options_page('OneScreen Account', 'OneScreen Account', 'administrator', __FILE__, array($this, 'display_options_page'));
    }

    public function display_options_page() {
        ?>
        <div class="wrap">
        <h2>OneScreen Account Settings</h2>

        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php settings_fields('os_plugin_options'); ?>
            <?php do_settings_sections(__FILE__); ?>

            <p class="submit">
                <input name="submit" type="submit" class="button-primary" value="Save Changes"/>
        </form>
        <?php
    }

    public function register_settings_and_fields() {
        register_setting('os_plugin_options', 'os_plugin_options');
        add_settings_section('os_settings_section', 'Account Settings', array($this, 'os_settings_section_cb'), __FILE__);
        add_settings_field('os_mediagraph_token', 'Enter Account Token:', array($this, 'os_mediagraph_token_setting'), __FILE__, 'os_settings_section');
        add_settings_field('os_default_widget_id', 'Default Widget ID: ', array($this, 'os_default_widget_id_setting'), __FILE__, 'os_settings_section');
        add_settings_field( 'os_default_onescreen_js', 'Default Script to Use: ', array( $this, 'os_default_onescreen_js' ), __FILE__, 'os_settings_section' );
    }

    public function os_settings_section_cb() {
        // optional
    }

    public function os_mediagraph_token_setting(){
        $this->options['os_mediagraph_token_setting'] = empty($this->options['os_mediagraph_token_setting']) ? '' : $this->options['os_mediagraph_token_setting'];
        echo "<input name='os_plugin_options[os_mediagraph_token_setting]' type='text' value='{$this->options['os_mediagraph_token_setting']}' style='width:50%;' />";
    }

    public function os_default_widget_id_setting() {
        $this->options['os_default_widget_id_setting'] = empty($this->options['os_default_widget_id_setting']) ? '' : $this->options['os_default_widget_id_setting'];
        echo "<input name='os_plugin_options[os_default_widget_id_setting]' type='text' value='{$this->options['os_default_widget_id_setting']}' style='width:50%;' />";
    }

    public function os_default_onescreen_js(){
        $this->options['os_default_onescreen_js'] = empty($this->options['os_default_onescreen_js']) ? '' : $this->options['os_default_onescreen_js'];
        echo '<select name="os_plugin_options[os_default_onescreen_js]">
                        <option value="1.9" '. selected( $this->options["os_default_onescreen_js"], "1.9", false )  .' >1.9 Script</option>
                        <option value="2.0" ' . selected( $this->options["os_default_onescreen_js"], "2.0", false ) . ' >2.0 Script</option>
                    </select>';
    }

    public function os_authenticate_token(){
        $token = $this->token;
        // uses curl to do GET request on url
        if (!function_exists('verify_url')){
            function verify_url($url){
                $response = wp_remote_get($url);
                if (is_wp_error($response)) $results = $response;
                elseif (is_array($response) && array_key_exists('body', $response)) {
                    $results = $response['body'];
                    $results = json_decode($results);
                }

                return $results;
            }
        }

        $url = 'http://api2.onescreen.net/v2/accounts/mine.json' . '?token=' . $token;
        $results = verify_url($url);

        return $results;
    }
}

// adds the 'data-' attributes to the target div so that script can grab it for the .load calls
function attributes_to_data($attributes, $passthrough_url){
    $non_overrides = array('item', 'target_div', 'playlist_id', 'app_id');
    $data_string = $passthrough_url . ' ';
    foreach ($attributes as $attribute_key => $attribute_value){
        // remove key if empty value
        if (empty($attribute_value)) {
            unset($attributes[$attribute_key]);
            continue;
        }
        if (stripos($attribute_key, 'widget_id') !== false) $data_string .= 'data-appid' . '="' . $attribute_value . '" ';
        else if (in_array($attribute_key, $non_overrides)) continue;
        else if (stripos($attribute_key, 'companion_target') !== false) $data_string .= "data-".$attribute_key."=".$attribute_value." ";   // must be passed as array, NOT string
        else $data_string .= 'data-' . $attribute_key .'="' . $attribute_value . '" ';
    }
    return $data_string;
}

// main test/printing function
if (!function_exists('os_test')){
    function os_test($array){
      echo '<pre>';
      print_r($array);
      echo '</pre>';
    }
}

new OS_Shortcode();
?>

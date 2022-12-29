<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://softexpert.gr
 * @since      1.0.0
 *
 * @package    Se_xml_feed
 * @subpackage Se_xml_feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Se_xml_feed
 * @subpackage Se_xml_feed/admin
 * @author     Charis Valtzis <charisvaltzis@gmail.com>
 */
class Se_xml_feed_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Se_xml_feed_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Se_xml_feed_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/se_xml_feed-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Se_xml_feed_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Se_xml_feed_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/se_xml_feed-admin.js', array('jquery'), $this->version, false);

    }

    public function se_feed_register_settings()
    {

        register_setting(

            'se_feed_option_group',

            'se_feed_option_group',

            array($this, 'se_validate_entry')

        );


        add_settings_section(

            'se_xml_settings',

            __('Feed Settings', 'se_xml_feed'),

            array($this, 'print_se_settings_info'),

            'xml_feed_options'

        );

        add_settings_field(

            'se_cron_job_feed',

            'Se Cron Job Feed',

            array($this, 'se_cron_job_feed_callback'),

            'xml_feed_options',

            'se_xml_settings'

        );


    }

    public function se_validate_entry($input)
    {
        return sanitize_text_field($input['se_cron_job_feed']);
    }

    function se_cron_job_feed_callback()
    {

        $cron = get_option('se_feed_cron');

        $cron_options = ['hourly' => 'Hourly', 'twicedaily' => 'Twice a day', 'daily' => 'Daily'];

        ?>

        <select id="se_feed_cron" name="se_feed_cron">

            <option value="none"><?php _e('None', 'se_feed_xml') ?></option>

            <?php

            foreach ($cron_options as $key => $cron_option) {

                ?>

                <option value="<?php echo $key ?>"<?php echo $cron == $key ? ' selected' : '' ?>><?php _e($cron_option, 'se_feed_xml') ?></option>

                <?php

            }

            ?>

        </select>

        <?php

    }

    function print_se_settings_info()
    {

        _e('Check if you want to activate cron job', 'se_xml_feed');

    }

    function print_se_info()
    {

        _e('Edit and apply all the settings to match your needs for XML feed', 'se_xml_feed');

    }

    /**
     *
     * @since    1.0.0
     */
    public function se_feed_menu_item()
    {
        add_menu_page('xml feed', 'xml feed', 'manage_options', 'xml_feed_options',

            array(

                $this,

                'se_feed_plugin_settings'

            ), 'dashicons-plugins-checked', 20);
    }

    /**
     *
     * @since    1.0.0
     */
    function se_feed_plugin_settings()
    {

        if (!current_user_can('manage_options')) {

            return;

        }

        // check if the user have submitted the settings
        // WordPress will add the "settings-updated" $_GET parameter to the url
        if (isset($_GET['settings-updated'])) {
            // add settings saved message with the class of "updated"
            add_settings_error('wporg_messages', 'wporg_message', __('Settings Saved', 'wporg'), 'updated');
        }

        // show error/update messages
        settings_errors('wporg_messages');

        ?>

        <div class="wrap">

            <h1><?= esc_html(get_admin_page_title()); ?></h1>

        </div>


        <div class="se_feed_settings">

            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">

                <p><?php _e('Last Update:', 'se_xml_feed') ?>

                    <strong><?php echo esc_attr(get_option('se_xml_last_run')); ?></strong>

                </p>

                <p><?php _e('XML FEED URL', 'se_xml_feed') ?>

                    <br>

                    <em><?php echo wp_upload_dir()['path'] . '/feed.xml' ?></em>

                </p>

                <p>

                    <a target="_blank"
                       href="<?php echo wp_upload_dir()['path'] . '/feed.xml' ?>"><?php _e('View XML feed', 'se_xml_feed') ?></a>

                </p>

                <p class="submit">

                    <input type="submit" name="submit" id="submit" class="button button-primary"
                           value="<?php _e('Update XML feed', 'se_xml_feed') ?>">

                </p>

                <input type="hidden" name="action" value="se_create_feed">

            </form>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

                <?php

                // This prints out all hidden setting fields
                settings_fields('se_feed_option_group');

                do_settings_sections('xml_feed_options');


                submit_button();

                ?>

                <input type="hidden" name="action" value="se_feed_save_settings">

            </form>

        </div>

        <?php

    }

    /**
     * Update hook value
     * and set cron job to run
     * @since    1.0.0
     */
    function se_feed_save_settings()
    {
        $se_feed_cron = sanitize_text_field($_POST['se_feed_cron']);

        if (get_option('se_feed_cron') != $se_feed_cron) {


            wp_clear_scheduled_hook('se_feed_hook');

            if ($se_feed_cron !== 'none') {
                if (!wp_next_scheduled('se_feed_hook')) {

                    wp_schedule_event(time(), $se_feed_cron, 'se_feed_hook');

                }
            }

        }

        update_option('se_feed_cron', $se_feed_cron);

        wp_redirect(admin_url('admin.php?page=xml_feed_options'));

        exit;

    }

    /**
     * Feed Generator
     * @since    1.0.0
     */
    function se_create_feed()
    {

        $xml = new DOMDocument('1.0', 'UTF-8');

        $products = $xml->createElement('products');


        $xml->appendChild($products);

        $created_at = $xml->createElement('created_at', date('Y-m-d H:i'));


        $products->appendChild($created_at);

        $args = array(

            'post_type' => 'product',

            'posts_per_page' => -1,

            'post_status' => 'publish',

            'fields' => 'ids'

        );

        $loop = new WP_Query(apply_filters('se_feed_custom_args', $args));

        while ($loop->have_posts()) : $loop->the_post();

            $wc_product = wc_get_product(get_the_ID());

            if ($wc_product->is_type('simple')) {

                $this->create_product_feed_element($wc_product, $xml, $products);

            } else {
                $product = wc_get_product($wc_product);
                $current_products = $product->get_children();

                foreach ($current_products as $variation):
                    $wc_product = wc_get_product($variation);

                    $this->create_product_feed_element($wc_product, $xml, $products);
                endforeach;
            }


        endwhile;

        $xml->encoding = 'UTF-8';

        $xml->preserveWhiteSpace = false;

        $xml->formatOutput = true;


        $result = $xml->save(wp_upload_dir()['path'] . '/feed.xml');

        $tz = 'Europe/Athens';

        $dt = new DateTime("now", new DateTimeZone($tz));

        update_option('se_xml_last_run', $dt->format('d/m/Y H:i:s'));

        wp_mail(get_bloginfo('admin_email'), 'Xml Feed Updated', 'Xml feed has been successfully updated');

        wp_redirect(admin_url('admin.php?page=xml_feed_options'));
    }

    private function create_product_feed_element($wc_product, $xml, $products)
    {

        $product = $xml->createElement('product');

        $products->appendChild($product);

        //id
        $product_id = $xml->createElement('id', $wc_product->get_id());

        $product->appendChild(apply_filters('se_feed_override_id', $product_id));

        //sku
        $sku = $product->appendChild($xml->createElement('code'));

        $sku->appendChild($xml->createCDATASection(esc_html(apply_filters('se_feed_custom_sku', $wc_product->get_sku()))));

        //name
        $name = $product->appendChild($xml->createElement('name'));

        $name->appendChild($xml->createCDATASection($wc_product->name));

        //image
        $image = $product->appendChild($xml->createElement('image'));

        $image->appendChild($xml->createCDATASection(apply_filters('se_feed_custom_image', wp_get_attachment_url($wc_product->get_image_id()))));

        //price
        $price = $xml->createElement('price_with_vat', $wc_product->get_price());

        $product->appendChild($price);

        //category
        $category = $product->appendChild($xml->createElement('category'));

        $category->appendChild($xml->createCDATASection($this->get_categories_for_xml($wc_product)));

    }

    private function get_categories_for_xml($wc_product)
    {

        $categories_list = '';

        $categories_list = get_the_terms(($wc_product->is_type('variation') || $wc_product->is_type('variable') ? $wc_product->get_parent_id() : $wc_product->get_id()), 'product_cat');

        if ($categories_list) {

            $last_category = end($categories_list);

            $categories_list = array();

            $ancestors = get_ancestors($last_category->term_id, 'product_cat', 'taxonomy');

            $ancestors = array_reverse($ancestors);

            foreach ($ancestors as $parent) {

                $term = get_term_by('id', $parent, 'product_cat');

                array_push($categories_list, $term->name);

            }

            array_push($categories_list, $last_category->name);

        }


        return implode(' > ', $categories_list);

    }


}

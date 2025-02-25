<?php

/*
Plugin Name:  Product category custom fields
Plugin URI:   https://www.google.com
Description:  Product category custom fields
Version:      1.0
Author:       swarnatek
Author URI:   https://www.swarnatek.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/


if (!defined('ABSPATH')) {
    exit;
}

function pluginincludes()
{
    wp_register_style('pluginincludes', plugins_url('/css/style.css?' . date("h:i:sa"), __FILE__), false, '1.0', 'all'); // Inside a plugin
    wp_enqueue_style('pluginincludes');
    wp_register_script('pluginincludes', plugins_url('/js/cat-script.js?' . date("h:i:sa"), __FILE__), array('jquery'), true);
    wp_enqueue_script('pluginincludes');
}

add_action('init', 'pluginincludes');

/************** Edit Category page **************/

/*Printing the box content */
function extra_edit_tax_fields($tag)
{
    $t_id = $tag->term_id;
    $employeeDetails = get_term_meta($t_id, 'employeeDetails', true);

    // Use nonce for verification
    wp_nonce_field(plugin_basename(__FILE__), 'employee_nonce');
    ?>
    <div id="employee_meta_item">
    <div class="tax-custom-fields">
    <label for="title">Title</label>
    <input  type="text" name="employeeDetails[0][0]" class="custom_title" value=<?php echo @$employeeDetails[0][0]; ?> > </input>
    <label for="description">Description</label>
      <input type="text" name="employeeDetails[0][1]" class="custom_description" value=<?php echo @$employeeDetails[0][1]; ?> >
    </input>
    </div>

<?php 
$count =   0;
    foreach($employeeDetails as $value){
        if($count != 0){
      echo  $data = '<div class="tax-custom-fields"><label for="title">Title</label>
        <input  type="text" name="employeeDetails['.$count.'][0]" class="custom_title" value='.$value[0].'> </input>
        <label for="description">Description</label>
        <input type="text" name="employeeDetails['.$count.'][1]" class="custom_description" value='.$value[1].' >
        </input><a href="#" class="remove-package">Remove</a></div>';
    }
    $count++;
}
?>
        <span id="output-package"></span>
        <div class="add-field-btn">
            <a href="#" class="add_package"><?php _e('Add Employee Details'); ?></a>
        </div>
        <script>
            var $ = jQuery.noConflict();
            jQuery(document).ready(function() {
                jQuery(".add_package").click(function() {
                var numItems = jQuery('.custom_title').length;
                     jQuery('#output-package').append('<div class="tax-custom-fields"><label for="title">Title</label><input class="custom_title" type="text" name="employeeDetails[' + numItems + '][0]" value="" />  <label for="description">Description</label> <input class="custom_description" name="employeeDetails[' + numItems + '][1]"  ></input><a href="#" class="remove-package">Remove</a></div>');
                    });

                    
                jQuery(document.body).on('click', '.remove-package', function() {
                    jQuery(this).parent().remove();
                    var count = 0;
                    var count2 = 0;
                    jQuery('.custom_title').each(function(i, obj) {
                        jQuery(this).attr('name', 'employeeDetails['+count+'][0]');
                        count++;
                        });

                    jQuery('.custom_description').each(function(i, obj) {
                        jQuery(this).attr('name', 'employeeDetails['+count2+'][1]');
                        count2++;
                        });
                });
            });
        </script>
    </div><?php
}
function save_extra_taxonomy_fields($term_id)
{
    $empDetail = [];
    foreach($_POST['employeeDetails'] as $value){
        if($value['0'] != '' && $value['1'] != ''){
            $empDetail['0'] = $value['0'];
            $empDetail['1'] =  $value['1'];
            $finalArr[] = $empDetail;
        }
    }
    if (isset($finalArr)) {
        $input_value = $finalArr;
        update_term_meta($term_id, 'employeeDetails', $input_value);
    }
}

add_action('edited_product_cat', 'save_extra_taxonomy_fields', 10, 2);
add_action('create_product_cat', 'save_extra_taxonomy_fields', 10, 2);
add_action('product_cat_edit_form_fields', 'extra_edit_tax_fields', 10);


/************** display on category page **************/

function ccf_display_employee_details() {
    $current_category = get_queried_object();
    if (is_product_category()) {
        $employee_details = get_term_meta($current_category->term_id, 'employeeDetails', true);
        echo '<pre>';
        print_r($employee_details);
        if (!empty($employee_details) && is_array($employee_details)) {
            $output = '<div class="employee-buttons">';
            foreach ($employee_details as $detail) {
                $title = isset($detail['0']) ? $detail['0'] : '';
                $description = isset($detail['1']) ? $detail['1'] : '';

                $output .= '<button class="employee-title" data-description="' . esc_attr($description) . '">' . esc_html($title) . '</button>';
            }
            $output .= '</div>';
            echo $output; 
        }
    }
}
add_action('woocommerce_archive_description', 'ccf_display_employee_details');


/******** description popup ********/
function ccf_add_popup_html() {
    ?>
    <div id="description-popup" style="display:none;">
        <div class="popup-content">
            <span class="close-button">&times;</span>
            <div id="popup-description"></div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'ccf_add_popup_html');


function ccf_display_accordion(){
    $current_category = get_queried_object();
    if (is_product_category()) {
        $employee_details = get_term_meta($current_category->term_id, 'employeeDetails', true);
        echo '<pre>';
        print_r($employee_details);
        if (!empty($employee_details) && is_array($employee_details)) {
            $output = '<div class="ccf-accordion">';
            foreach ($employee_details as $detail) {
                $title = isset($detail['0']) ? $detail['0'] : '';
                $description = isset($detail['1']) ? $detail['1'] : '';

                // $output .= '<button class="employee-title" data-description="' . esc_attr($description) . '">' . esc_html($title) . '</button>';
                $output .= '<div class="at-item">
                            <div class="at-title active">
                            <h2>'.esc_html($title).'</h2>
                            </div>
                            <div class="at-tab" style="display: block;">
                            <p>'.esc_html($description).'</p>
                            </div>
                        </div>';
            }
            $output .= '</div>';
            echo $output; 
        }
    }
}

add_action('woocommerce_after_shop_loop','ccf_display_accordion');
<?php
/*
Plugin Name: Partner Custom Options
Description: Setup custom options for partners
Author: Daniel Roth
Version: 0.1
*/

add_action('admin_menu', function() {
  add_options_page( 'Partner Custom Options', 'Partner Custom Options', 'manage_options', 'partner-custom-options', 'partner_custom_options_page' );
});

require_once("apply_settings.php");

function partner_custom_options_page() {
  global $wpdb;
  $query = "SELECT * FROM partner_custom_options";
  $partners = $wpdb->get_results($query);
  $arrPartners = array();
  for ($i = 0; $i < count($partners); $i++) 
    $arrPartners[$partners[$i]->short_name] = $partners[$i];
  
  ?>
    <div class="wrap">
    <form>

      <table>
        <tr>
          <th>Partner name</th>
          <td>
            <select id="partner-selsite">
              <option value="0"></option>
            </select>
          </td>
        </tr>

        <tr>
          <th>Short name</th>
          <td>
            <input type="text" id="partner-txtshortname" readonly  size="50">
          </td>
        </tr>

        <tr>
          <th>Cities</th>
          <td>
            <textarea id="partner-txtcities" rows="5" cols="50" readonly></textarea>
          </td>
        </tr>

        <tr>
          <th>Your gender</th>
          <td>
            <label>
              <input type="radio" name="map_option_4" value="male" <?php echo esc_attr( get_option('map_option_4') ) == 'male' ? 'checked="checked"' : ''; ?> /> Male <br/>
            </label>
            <label>
              <input type="radio" name="map_option_4" value="female" <?php echo esc_attr( get_option('map_option_4') ) == 'female' ? 'checked="checked"' : ''; ?> /> Female
            </label>
          </td>
        </tr>

        <tr>
          <th>Do you love WordPress?</th>
          <td>
            <label>
              <input type="checkbox" id="chk_chk" name="map_option_5" <?php echo esc_attr( get_option('map_option_5') ) == 'on' ? 'checked="checked"' : ''; ?> />Yes, I love WordPress
            </label><br/>
            <label>
              <input type="checkbox" name="map_option_6" <?php echo esc_attr( get_option('map_option_6') ) == 'on' ? 'checked="checked"' : ''; ?> />No, I love WordPress
            </label>
          </td>
        </tr>

        <tr>
          <td><input type="button" id="partner-btnsubmit" class="button button-primary" value="Save Changes"></td>
        </tr>

      </table>

    </form>
    </div>
    <script type="text/javascript">
      var partners = <?php echo json_encode($arrPartners); ?>;
      for (partnerName in partners) {
        jQuery('#partner-selsite').append(new Option(
          partners[partnerName]['name'], partnerName
        ));
      }

      jQuery('#partner-selsite').on('change', function(){
        var key = jQuery(this).val();
        console.log(key);
        jQuery('#partner-txtshortname').val(key);
        jQuery('#partner-txtcities').val(partners[key]['cities']);
      });

      jQuery('#partner-btnsubmit').on('click', function() {
        var postData = {
          'action': 'apply_partner_options',
          'data': { 
            short_name: jQuery('#partner-selsite').val(),
            cities: jQuery('#partner-txtcities').val(),
          },
        };

        jQuery.post(ajaxurl, postData, function(res) {
          console.log(res);
        });
      });
    </script>
  <?php
}

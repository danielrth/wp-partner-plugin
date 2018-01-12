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
  $query = "SELECT * FROM partner_custom_options ORDER BY name";
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
            <select id="partner-sel-site">
              <option value="0"></option>
            </select>
          </td>
        </tr>

        <tr>
          <th>Short name</th>
          <td>
            <input type="text" id="partner-txt-shortname" readonly  size="50">
          </td>
        </tr>

        <tr>
          <th>Table prefix</th>
          <td>
            <input type="text" id="partner-txt-tableprefix" readonly  size="50">
          </td>
        </tr>

        <tr>
          <th>Cities</th>
          <td>
            <textarea id="partner-txt-cities" rows="8" cols="50" readonly></textarea>
          </td>
        </tr>

        <tr>
          <th>Cities headline</th>
          <td>
            <textarea id="partner-txt-citiesheadline" rows="2" cols="50" readonly></textarea>
          </td>
        </tr>

        <tr>
          <th>About us</th>
          <td>
            <textarea id="partner-txt-aboutus" rows="5" cols="50" readonly></textarea>
          </td>
        </tr>

        <tr>
          <th>Footer</th>
          <td>
            <input type="text" id="partner-txt-footer" readonly  size="50">
          </td>
          </td>
        </tr>

        <tr>
          <th>Phone</th>
          <td>
            <input type="text" id="partner-txt-phone" readonly  size="50">
          </td>
          </td>
        </tr>

        <tr>
          <th>Call center hours</th>
          <td>
            <input type="text" id="partner-txt-callcenter-hours" readonly  size="50">
          </td>
          </td>
        </tr>

        <tr>
          <th>Satisfaction</th>
          <td>
            <input type="checkbox" id="partner-chk-satis" name="map_option_5" />
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
        jQuery('#partner-sel-site').append(new Option(
          partners[partnerName]['name'], partnerName
        ));
      }

      jQuery('#partner-sel-site').on('change', function(){
        var key = jQuery(this).val();
        console.log(key);
        jQuery('#partner-txt-shortname').val(key);
        jQuery('#partner-txt-tableprefix').val(partners[key]['table_prefix']);
        jQuery('#partner-txt-cities').val(partners[key]['cities']);
        jQuery('#partner-txt-citiesheadline').val(partners[key]['cities_headline']);
        jQuery('#partner-txt-aboutus').val(partners[key]['about_us']);
        jQuery('#partner-txt-footer').val(partners[key]['footer']);
        jQuery('#partner-txt-phone').val(partners[key]['phone']);
        jQuery('#partner-txt-callcenter-hours').val(partners[key]['callcenter_hours']);
        jQuery('#partner-chk-satis').prop( 'checked', partners[key]['satisfaction'] == 1 ? true : false );
      });

      jQuery('#partner-btnsubmit').on('click', function() {
        var key = jQuery('#partner-sel-site').val();

        if (key == 0)
          return;

        var postData = {
          'action': 'apply_partner_options',
          'data': { 
            short_name:       partners[key]['short_name'],
            table_prefix:     partners[key]['table_prefix'],
            cities:           partners[key]['cities'],
            cities_headline:  partners[key]['cities_headline'],
            about_us:         partners[key]['about_us'],
            footer:           partners[key]['footer'],
            phone:            partners[key]['phone'],
            callcenter_hours: partners[key]['callcenter_hours'],
            satisfaction:     partners[key]['satisfaction'],
          },
        };

        jQuery.post(ajaxurl, postData, function(res) {
          console.log(res);
        });
      });
    </script>
  <?php
}

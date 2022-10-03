<?php
/**
 * @package Floating Social Media popout button
 * @version 2.0
 */
/*
    Plugin Name: Floating Social Media Popout Extended
    Description: Floating Social Media popout allows your webpage to show a Facebook like box, Google Plus badge, and Twiter follow box widget when a visitor mouse hovers the floating Facebook, Google Plus or Twitter icons located on right side of webpage. (Upgrated by <a href="http://facebook.com/IonicaBizauPage">Ionica Bizau</a>.)
    Author: Santosh Padire & Ionica Bizau
    Version: 2.0
    Author URI: http://www.reviewresults.in
*/

add_action('init', 'FFB_facebook_share_init');
add_action('wp_footer', 'FFB_FaceBook_Float_Load', 100);

function FFB_facebook_share_init() {
	// DISABLED IN THE ADMIN PAGES
	if (is_admin()) {
		return;
	}
    wp_enqueue_script( 'jquery' );
	wp_enqueue_style('fsb_style', '/wp-content/plugins/floatingsocialmediapopout/fsb_style.css');
}
function FFB_FaceBook_Float_Load() {
	echo FFB_FaceBook_Float();
}

/* Facebook and Googleplus*/
function FFB_FaceBook_Float() {
    if (is_admin()) {
        return;
    }

    $MG_top = 10;

    /*
        //==========//
        // FACEBOOK //
        //==========//
    */
    $FFB_path = get_option('FF_facebook_path');
    $FBFloatImage = get_plugin_directory().'/Images/FBFloat.png"';

    $FFB_path = preg_replace('/:/','%3A', $FFB_path);
    $FFB_path = preg_replace('#/#','%2F', $FFB_path);

    $str = 'http://www.facebook.com/plugins/likebox.php?href=' .$FFB_path. '&amp;locale=en_GB&amp;width=238&amp;connections=9&amp;stream=&amp;header=false&amp;show_faces=0&amp;height=256';

    $button = '';

    if ($FFB_path != '')
    {
        $button .='
                <div class="FSPMain">
                <div id="FSPfacebook" style="top: '.$MG_top.'px;">
                <div id="FSPfacebookDiv">
                <img class="FSPImage" runat="server" src="'.$FBFloatImage.'';
        $button .='" alt="" />
                <iframe src=
                "'.$str.'';
        $button .='"';
        $button .=' scrolling="no"> </iframe>
                </div>
                </div>
                </div>';

        $MG_top += 105;
    }

    /*
        //=============//
        // GOOGLE PLUS //
        //=============//
    */
    $GplusID = get_option('FF_GplusID');
    $GPFloatImage = get_plugin_directory().'/Images/GPlus.png"';

    if ($GplusID != '')
    {
        $button .='
                <div class="FSPMain">
                <div id="FSPgoogleplus" style="top: '.$MG_top.'px;">
                <div id="FSPGplusDiv">
                <img id="FSPgoogleplusimg" runat="server" src="'.$GPFloatImage.'';
        $button .='" alt="" />
                <div style="float:left;margin:0px 0px 0px 0px;">
                <!-- Place this tag where you want the badge to render. -->
                <div class="g-plus" data-href="https://plus.google.com/'.$GplusID.'" data-rel="publisher"></div>
                <!-- Place this tag after the last badge tag. -->
                <script type="text/javascript">
                    (function() {
                    var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
                    po.src = "https://apis.google.com/js/plusone.js";
                    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
                    })();
                </script>
                </div>';
        $button .='</div>
                </div>
                </div>';
        $MG_top += 105;
    }

    /*
        //==========//
        // TWITTER  //
        //==========//
    */
    $TW_path = get_option('FF_TwitterUsername');
    $TWFloatImage = get_plugin_directory().'/Images/twitter.png"';

    $TW_path = preg_replace('/:/','%3A', $TW_path);
    $TW_path = preg_replace('#/#','%2F', $TW_path);

    if ($TW_path != '')
    {
        $button .='
                <div class="FSPMain">
                <div id="FSPtwitter" style="top: '.$MG_top.'px;">
                <div id="FSPtwitterDiv">
                <img class="FSPImage" style="top: -1.1px; left: -33px;" runat="server" src="'.$TWFloatImage.'';
        // Start building the iframe
        $button .='" alt="" />
                <a class="twitter-timeline" href="https://twitter.com/'.$TW_path.'" data-widget-id="544226682694279168">Tweets by @'.$TW_path.'</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </div>
                </div>
                </div>';
        $MG_top += $MG_delta;
    }

    $button .='<script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery("#FSPfacebook").hover(function () { jQuery(this).stop(true, false).animate({ right: 0 }, 500); },
                                function () { jQuery("#FSPfacebook").stop(true, false).animate({ right: -240 }, 500); });
                jQuery("#FSPgoogleplus").hover(function(){ jQuery(this).stop(true,false).animate({right:  0}, 500); },
                                function(){ jQuery("#FSPgoogleplus").stop(true,false).animate({right: -304}, 500); });
                jQuery("#FSPtwitter").hover(function(){ jQuery(this).stop(true,false).animate({right:  0}, 500); },
                                function(){ jQuery("#FSPtwitter").stop(true,false).animate({right: -240}, 500); });
            });
            </script>';
    return $button;
}

function get_plugin_directory() {
	return WP_PLUGIN_URL . '/floatingsocialmediapopout';
}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'FFB_facebook_install');

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'FFB_facebook_remove' );

function FFB_facebook_install() {
    /* Do Nothing */
}

function FFB_facebook_remove() {
    /* Deletes the database field */
    delete_option('FF_facebook_path');
    delete_option('FF_GplusID');
    delete_option('FF_TwitterUsername');
}
if (is_admin()){
    /* Call the html code */
    add_action('admin_menu', 'floatingFB_admin_menu');

    function floatingFB_admin_menu() {
        add_options_page('Floating SM Popout', 'Floating SM Popout', 'administrator',
        'Floating_SMP', 'floatingFB_html_page');
    }
}

function floatingFB_html_page() {
?>
<div>
    <h2>Floating Sharing Popout Options</h2>

    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>

        <table width="800">
            <tr valign="top">
                <th width="120" scope="row">FaceBook Page URL</th>
                <td width="680">
                <input name="FF_facebook_path" type="text" id="FF_facebook_path"
                value="<?php echo get_option('FF_facebook_path'); ?>" />
                (ex. https://www.facebook.com/IonicaBizauPage)</td>
            </tr>

            <tr valign="top">
                <th width="120" scope="row">GooglePlus Page ID</th>
                <td width="680">
                <input name="FF_GplusID" type="text" id="FF_GplusID"
                value="<?php echo get_option('FF_GplusID'); ?>" />
                (ex. 109274325672508858588)</td>
            </tr>

            <tr valign="top">
                <th width="120" scope="row">Twitter Username</th>
                <td width="680">
                <input name="FF_TwitterUsername" type="text" id="FF_TwitterUsername"
                value="<?php echo get_option('FF_TwitterUsername'); ?>" />
                (ex. IonicaBizau)</td>
            </tr>

        </table>
            <table width="800">
            <tr valign="left">
            <th width="800">Note: Leave the fields blank if you don't have any GooglePlus Page, Facebook, or Twitter page. Only the Field with values will be enabled on the page.</th>
            </tr>
        </table>

        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="FF_facebook_path,FF_GplusID,FF_TwitterUsername" />

        <p>
        <input type="submit" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>
<?php
}
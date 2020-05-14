<?php
/**
 * Plugin Name: Sim Video Player
 * Plugin URI:  https://switchwebdev.com/wordpress-plugins/
 * Description: Sim Video Player is a YouTube and Vimeo player, this plugin will create a beatiful HTML5, YouTube and Vimeo media player for all Youtube and Vimeo Videos using the Plyr library by Sam Potts
 * Author:      SwitchWebdev.com
 * Author URI:  https://switchwebdev.com
 * Version:     0.2.1
 * License:     GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-plyr
 *
 * Requires PHP: 5.6+
 * Tested up to PHP: 7.4
 *
 * Copyright 2020 Uriel Wilson, support@switchwebdev.com
 * License: GNU General Public License
 * GPLv2 Full license details in license.txt
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ----------------------------------------------------------------------------
 * @category  	Plugin
 * @copyright 	Copyright © 2020 Uriel Wilson.
 * @package   	SimVideoPlayer
 * @author    	Uriel Wilson
 * @link      	https://switchwebdev.com
 *  ----------------------------------------------------------------------------
 */

  # deny direct access
    if ( ! defined( 'WPINC' ) ) {
      die;
    }

  # plugin directory
	  define("WPPLYR_VERSION", '0.2.1');

  # plugin directory
    define("WPPLYR_DIR", dirname(__FILE__));

  # plugin url
    define("WPPLYR_URL", plugins_url( "/",__FILE__ ));
#  -----------------------------------------------------------------------------


/**
 *
 */
class Sim_Video_Player {

  function __construct(){
    add_action( 'wp_enqueue_scripts', array( $this , 'simvideo_scripts') );
    add_filter( 'embed_oembed_html', array( $this , 'simplyer_html'), 99, 4 );
  }

  /**
   * load the scripts
   * @return [type] [description]
   */
  public function simvideo_scripts() {

    // load css
    wp_enqueue_style( 'video-plyr-css', plugin_dir_url( __FILE__ ) . 'vendor/plyr/css/plyr.css', array(), WPPLYR_VERSION, 'all' );

  	// load js
  	wp_enqueue_script( 'vid-plyr', plugin_dir_url( __FILE__ ) . 'vendor/plyr/src/js/plyr.js', array(), WPPLYR_VERSION, true );
  	wp_enqueue_script( 'video-player', plugin_dir_url( __FILE__ ) . 'assets/js/plyr-video-player.js', array(), WPPLYR_VERSION , true);
  }

  /**
   * [oembed description]
   * @return [type] [description]
   */
  public function oembed(){
    include_once ABSPATH . WPINC . '/class-wp-oembed.php';
  	$wp_oembed = new WP_oEmbed();
    return $wp_oembed;
  }

  /**
   * simplayer_html
   *
   * Outputs the HTML
   * @param  [type] $html    [description]
   * @param  [type] $url     [description]
   * @param  [type] $attr    [description]
   * @param  [type] $post_id [description]
   * @return string
   * @credit https://wordpress.org/plugins/plyr/
   */
  public function simplyer_html( $html, $url, $attr, $post_id ) {

  	$args = array();
  	$provider = $this->oembed()->get_provider( $url );

    /**
     * [if description]
     * @var [type]
     */
  	if ( !$provider || false === $data = $this->oembed()->fetch( $provider, $url, $args ) )
  		return false;

    /**
     * if not video exit
     * @var [type]
     */
  	if( 'video' !== $data->type )
  		return $html;

    /**
     * make sure this is a video from youtube or vimeo
     * @var [type]
     */
  	if( !in_array( $data->provider_name, array( 'Vimeo', 'YouTube' ) ) )
  		return $html;

    /**
     * get the video ID
     * @var [type]
     */
  	if( $data->provider_name == 'YouTube' ) {
  		$splode = array_reverse( explode('/', $data->thumbnail_url) );
  		$video_id = $splode[1];
  	}else{
  		$video_id = $data->video_id;
  	}

    /**
     * HTML Output
     * @var string
     */
  	$html = simplayer($data->provider_name,$video_id);
    return $html;
  }
}

/**
 * simplayer()
 *
 * helper for the player html
 * @param  string $video_provider [description]
 * @param  string $vid_id         [description]
 * @return [type]                 [description]
 */
function simplayer($video_provider = 'YouTube', $vid_id = ''){
  $player_html = '<div id="player" data-plyr-provider="'.strtolower($video_provider).'" data-plyr-embed-id="'.$vid_id.'"></div>';
  return $player_html;
}

/**
 * inititate the player
 * @var Sim_Video_Player
 */
$sm_vid_player = new Sim_Video_Player();

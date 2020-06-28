<?php
/**
 * Plugin Name: Sim Video Player
 * Plugin URI:  https://switchwebdev.com/wordpress-plugins/
 * Description: Sim Video Player is a YouTube and Vimeo player, this plugin will create a beatiful HTML5, YouTube and Vimeo media player for all Youtube and Vimeo Videos using the Plyr library by Sam Potts
 * Author:      SwitchWebdev.com
 * Author URI:  https://switchwebdev.com
 * Version:     0.6.2
 * License:     GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: sim-video-player
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
	  define("WPPLYR_VERSION", '0.6.2');

  # plugin directory
    define("WPPLYR_DIR", plugin_dir_path( __FILE__ ));

  # plugin url
    define("WPPLYR_URL", plugins_url( "/",__FILE__ ));
#  -----------------------------------------------------------------------------

  /**
   * Load the Plyr class
   * @var [type]
   */
  require plugin_dir_path( __FILE__ ) . 'src/class-video-plyr.php';

  /**
   * simplayer()
   *
   * helper for the player html
   * @param  string $video_provider
   * @param  string $vid_id
   * @return
   */
  function simplayer($video_provider = 'YouTube', $vid_id = ''){
    $player_html = '<div class="js-simplayer" data-plyr-provider="'.strtolower($video_provider).'" data-plyr-embed-id="'.$vid_id.'"></div>';
    return $player_html;
  }

  /**
   * inititate the player
   * @var Sim_Video_Player
   */
  $simvideo_player = Sim_Video_Player\get_video_player();

<?php

/**
 * setup the Plyr class
 */
namespace Sim_Video_Player;

class Video_Plyr {

  /**
   * return the class instance
   * @var
   */
  public static $instance;

  function __construct(){
    add_action( 'wp_enqueue_scripts', array( $this , 'simvideo_scripts') );
    add_filter( 'embed_oembed_html', array( $this , 'simplyer_html'), 99, 4 );
  }

  /**
   * load the scripts
   * @return
   */
  public function simvideo_scripts() {

    // load css
    wp_enqueue_style( 'video-plyr-css', WPPLYR_URL . 'vendor/plyr/css/plyr.css', array(), WPPLYR_VERSION, 'all' );

  	// load js
  	wp_enqueue_script( 'vid-plyr', WPPLYR_URL . 'vendor/plyr/src/js/plyr.js', array(), WPPLYR_VERSION, true );
  	wp_enqueue_script( 'simvideo-player', WPPLYR_URL . 'assets/js/simplayer.js', array(), WPPLYR_VERSION , true);
  }

	/**
	 * [instance description]
	 * @param  boolean $init
	 * @return
	 */
	public static function instance() {

    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Video_Plyr) ) {
    	self::$instance = new Video_Plyr();
		}
    return self::$instance;
  }

  /**
   * [oembed description]
   * @return
   */
  public function oembed(){
    include_once ABSPATH . WPINC . '/class-wp-oembed.php';
  	$wp_oembed = new \WP_oEmbed();
    return $wp_oembed;
  }

  /**
   * simplayer_html
   *
   * Outputs the HTML
   * @param  [type] $html
   * @param  [type] $url
   * @param  [type] $attr
   * @param  [type] $post_id
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
 * sim_video_player function
 */
if (! function_exists('video_player')) {
	/**
	 * get_video_player()
	 *
	 * @return object
	 */
	function get_video_player() {
		// new up the object
		return Video_Plyr::instance();
	}
}

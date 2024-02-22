<?php
/**
 * Plugin Name: TextExtendAI
 * Description: Extend text in the block editor using AI
 * Version: 1.0
 * Author: Your Name
 * License: GPL2
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function textextendai_enqueue_scripts() {

  wp_enqueue_script( 'textextendai', plugins_url( 'textextendai.js', __FILE__ ) );

}

add_action( 'enqueue_block_editor_assets', 'textextendai_enqueue_scripts' );


?>
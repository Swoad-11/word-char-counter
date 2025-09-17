<?php
/**
 * Plugin Name:       Word & Character Counter
 * Description:       Displays the total word count, character count, and estimated reading time on blog posts. Lightweight, frontend-only plugin with no backend setup required.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Toufiq Islam Swoad
 * Author URI:        https://your-portfolio-or-github-link.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       word-character-counter
 * Domain Path:       /languages
 *
 * @package WordCharacterCounter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Add word count, character count, and reading time to post content.
 */
function wcc_add_counter_to_content( $content ) {
	if ( is_single() && in_the_loop() && is_main_query() ) {
		// Strip HTML tags and shortcodes.
		$text = wp_strip_all_tags( strip_shortcodes( $content ) );

		// Word count.
		$word_count = str_word_count( $text );

		// Character count (without spaces).
		$char_count = strlen( preg_replace( '/\s+/', '', $text ) );

		// Estimated reading time (assuming 200 words/minute).
		$reading_time = ceil( $word_count / 200 );

		// Counter HTML.
		$counter_html  = '<div class="wcc-reading-counter">';
		$counter_html .= '<p>';
		$counter_html .= '📝 Word Count: <strong>' . number_format_i18n( $word_count ) . '</strong> | ';
		$counter_html .= '🔡 Characters: <strong>' . number_format_i18n( $char_count ) . '</strong> | ';
		$counter_html .= '⏱ Estimated Reading Time: <strong>' . $reading_time . ' min</strong>';
		$counter_html .= '</p>';
		$counter_html .= '</div>';

		// Show counter before content.
		return $counter_html . $content;
	}

	return $content;
}
add_filter( 'the_content', 'wcc_add_counter_to_content' );

/**
 * Add basic frontend styling.
 */
function wcc_enqueue_styles() {
	wp_add_inline_style(
		'wp-block-library',
		'.wcc-reading-counter {
			font-size: 14px;
			color: #444;
			margin-bottom: 15px;
			padding: 10px 12px;
			border-left: 4px solid #0073aa;
			background: #f9f9f9;
			border-radius: 4px;
		}
		.wcc-reading-counter p {
			margin: 0;
			font-style: italic;
		}'
	);
}
add_action( 'wp_enqueue_scripts', 'wcc_enqueue_styles' );

/**
 * Display a custom admin notice in the dashboard.
 */
function wcc_custom_admin_notice() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p>
            ✅ <strong>Word & Character Counter</strong> is active!  
            Word counts, character counts, and estimated reading times are now displayed on your posts.
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'wcc_custom_admin_notice' );


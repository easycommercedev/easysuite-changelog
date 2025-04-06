<?php
/**
 * Plugin Name: EasySuite Changelog
 */
add_shortcode( 'changelog', function ( $atts ) {

		$atts = shortcode_atts(
			array(
				'slug'    => 'easycommerce',
				'version' => 'trunk',
				'cache'   => DAY_IN_SECONDS,
				'heading' => 'h4',
			),
			$atts,
			'changelog'
		);

		$slug    = $atts['slug'];
		$version = $atts['version'] == 'trunk' ? 'trunk' : "tags/{$atts['version']}";

		if ( false === $readme = get_transient( "easysuite-readme_{$slug}_{$version}" ) ) {

			$remote = wp_remote_get( "https://plugins.svn.wordpress.org/{$slug}/{$version}/readme.txt" );

			if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
				return;
			}

			$readme = wp_remote_retrieve_body( $remote );

			set_transient( "easysuite-readme_{$slug}_{$version}", $readme, $atts['cache'] );
		}

		if ( $readme ) {
			return parse_changelog( $readme, $atts['heading'] );
		}
	}
);

function parse_changelog( $readme_content, $heading = 'h4' ) {

	// Extract Changelog section only
	if ( preg_match( '/== Changelog ==(.*?)(== [^=]+ ==|$)/s', $readme_content, $matches ) ) {
		$changelog_raw = trim( $matches[1] );

		// Match version blocks like "= 0.9.7-beta – 2025.03.19 ="
		preg_match_all( '/= (.*?) =\s*(.*?)(?=\n= .*? =|\z)/s', $changelog_raw, $entries, PREG_SET_ORDER );

		$html = '<div class="changelog">';

		foreach ( $entries as $entry ) {
			$version     = htmlspecialchars( trim( $entry[1] ) );
			$changes_raw = trim( $entry[2] );

			$html .= "<{$heading}>{$version}</{$heading}>";

			$html .= '<ul>';

			// Convert each change line into <li>, ignoring empty lines
			foreach ( preg_split( '/\r\n|\r|\n/', $changes_raw ) as $line ) {
				$line = trim( $line );
				if ( ! empty( $line ) ) {
					$html .= '<li>' . htmlspecialchars( $line ) . '</li>';
				}
			}

			$html .= '</ul>';
		}

		$html .= '</div>';

		return $html;
	}

	return '';
}

<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts' );


function my_custom_sidebar() {
	register_sidebar(
		array (
			'name' => __( 'Custom Sidebar Area', 'hello-elementor-child' ),
			'id' => 'custom-side-bar',
			'description' => __( 'This is the custom sidebar that you registered using the code snippet. You can change this text by editing this section in the code.', 'your-theme-domain' ),
			'before_widget' => '<div class="widget-content">',
			'after_widget' => "</div>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'my_custom_sidebar' );


// Pagebuilder Locale
function sp_unload_textdomain_elementor() {
	if (is_admin()) {
		$user_locale = get_user_meta( get_current_user_id(), 'locale', true );
		if ( 'en_US' === $user_locale ) {
			unload_textdomain( 'elementor' );
			unload_textdomain( 'elementor-pro' );
		}
	}
}
add_action( 'init', 'sp_unload_textdomain_elementor', 100 );

/* Icon Widget Fix - Link now applies to the whole element (not only icon & title) */ 

function tdau_link_whole_icon_box ( $content, $widget ) {
	
    if ( 'icon-box' === $widget->get_name() ) {
        $settings = $widget->get_settings_for_display();

		$wrapper_tag = 'div';

		$has_icon = ! empty( $settings['icon'] );

		if ( ! empty( $settings['link']['url'] ) ) {
			$wrapper_tag = 'a';
		}

		$icon_attributes = $widget->get_render_attribute_string( 'icon' );
		$link_attributes = $widget->get_render_attribute_string( 'link' );

		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
        $is_new = ! isset( $settings['icon'] ) && Elementor\Icons_Manager::is_migration_allowed();
		
		ob_start();

		?>
		<<?php echo implode( ' ', [ $wrapper_tag, $link_attributes ] ); ?> class="elementor-icon-box-wrapper elementor-icon-box-wrapper-tdau elementor-animation-<?php echo $settings['hover_animation']; ?>">
			<?php if ( $has_icon ) : ?>
			<div class="elementor-icon-box-icon">
				<<?php echo implode( ' ', [ 'span', $icon_attributes ] ); ?>>
				<?php
				if ( $is_new || $migrated ) {
					Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
				} elseif ( ! empty( $settings['icon'] ) ) {
					?><i <?php echo $widget->get_render_attribute_string( 'i' ); ?>></i><?php
				}
				?>
				</span>
			</div>
			<?php endif; ?>
			<div class="elementor-icon-box-content">
				<<?php echo $settings['title_size']; ?> class="elementor-icon-box-title">
					<?php echo $settings['title_text']; ?>
				</<?php echo $settings['title_size']; ?>>
				<?php if ( ! Elementor\Utils::is_empty( $settings['description_text'] ) ) : ?>
				<p <?php echo $widget->get_render_attribute_string( 'description_text' ); ?>><?php echo $settings['description_text']; ?></p>
				<?php endif; ?>
			</div>
		</<?php echo $wrapper_tag; ?>>
		<?php

		$content = ob_get_clean();

    }

    return $content;
}
add_filter( 'elementor/widget/render_content', 'tdau_link_whole_icon_box', 10, 2 );
function ti_custom_javascript() {
    ?>
        <script>
          !function() {
			var t;
			if (t = window.driftt = window.drift = window.driftt || [], !t.init) return t.invoked ? void (window.console && console.error && console.error("Drift snippet included twice.")) : (t.invoked = !0,
			t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ],
			t.factory = function(e) {
			return function() {
			var n;
			return n = Array.prototype.slice.call(arguments), n.unshift(e), t.push(n), t;
			};
			}, t.methods.forEach(function(e) {
			t[e] = t.factory(e);
			}), t.load = function(t) {
			var e, n, o, i;
			e = 3e5, i = Math.ceil(new Date() / e) * e, o = document.createElement("script"),
			o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + i + "/" + t + ".js",
			n = document.getElementsByTagName("script")[0], n.parentNode.insertBefore(o, n);
			});
			}();
			drift.SNIPPET_VERSION = '0.3.1';
			drift.load('7vhbtcfkhe3f');
        </script>
    <?php
}
add_action('wp_head', 'ti_custom_javascript');

// DISABLE ELEMENTOR METADATA
add_filter( 'hello_elementor_description_meta_tag', '__return_false' );

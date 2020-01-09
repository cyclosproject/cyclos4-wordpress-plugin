<?php
/**
 * The widget for the login form.
 *
 * @package Cyclos
 */

namespace Cyclos\Widgets;

/**
 * Widget class for the Login widget.
 */
class LoginWidget extends \WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'cyclos_login_widget',
			__( 'Cyclos Login', 'cyclos' ),
			array( 'description' => __( 'Cyclos Login Form', 'cyclos' ) )
		);
	}

	/**
	 * Override the widget method to output the html of our widget.
	 *
	 * @param array $args     The args containing before/after title/widget html.
	 * @param array $instance The instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		// Note: we ignore the phpcs errors for not escaping output, because they can contain html that should not be escaped.
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo do_shortcode( '[cycloslogin]' );

		echo $args['after_widget'];
		// phpcs:enable
	}

	/**
	 * Outputs the settings form for our widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title      = apply_filters( 'widget_title', $instance['title'] ?? '' );
		$title_id   = $this->get_field_id( 'title' );
		$title_name = $this->get_field_name( 'title' );
		?>
		<p>
			<label for="<?php echo esc_attr( $title_id ); ?>"><?php echo esc_html( __( 'Title', 'cyclos' ) ); ?>:
			<input class="widefat" id="<?php echo esc_attr( $title_id ); ?>" name="<?php echo esc_attr( $title_name ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<?php
	}

	/**
	 * Handles updating settings for our current widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] ?? '' );
		return $instance;
	}

}

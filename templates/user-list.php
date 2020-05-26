<?php
// phpcs:ignoreFile WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
/**
 * The template for displaying the list of Cyclos users.
 *
 * You can override this template in your theme by copying it to {your-theme-directory}/cyclos/user-list.php.
 *
 * Available variables:
 * $cyclos_user_data the array containing the data of the Cyclos users.
 *
 * @package Cyclos
 */

?>
<style>
	.cyclos-user img { max-width: 150px; height: auto; }
</style>
<?php
foreach ( $cyclos_user_data as $cyclos_user ) {
	?>
	<div class="cyclos-user">
		<?php
		$user_name = $cyclos_user->name ?? $cyclos_user->display ?? '';
		if ( empty( $user_name ) ) {
			// Without a name, there is not much use in showing the user, so continue to the next user.
			continue;
		}
		?>
		<h2><?php echo esc_html( $user_name ); ?></h2>

		<?php
		$img_width  = $cyclos_user->image->width ?? '';
		$img_height = $cyclos_user->image->height ?? '';
		$user_props = array(
			'image'   => $cyclos_user->image->url ?? '',
			'address' => $cyclos_user->address->addressLine1 ?? '',
			'city'    => $cyclos_user->address->city ?? '',
			'country' => $cyclos_user->address->country ?? '',
			'website' => $cyclos_user->customValues->website ?? '',
			'phone'   => $cyclos_user->phone ?? '',
		);
		foreach ($user_props as $field => $value) {
			if ( $value ) {
				switch( $field ) {
					case 'website': ?>
						<div class="website"><a href="<?php echo esc_url( $value ); ?>"><?php echo esc_html( $value ); ?></a></div>
						<?php
						break;
					case 'image': ?>
						<div class="image"><img src="<?php echo esc_url( $value ); ?>" alt="<?php echo esc_attr( $user_name ); ?>" width="<?php echo esc_attr( $img_width ); ?>" height="<?php echo esc_attr( $img_height ); ?>"></div>
						<?php
						break;
					default: ?>
						<div class="<?php echo esc_attr( $field ); ?>"><?php echo esc_html( $value ); ?></div>
						<?php
				}
			?>
			<?php
			}
		}
		?>
	</div>
	<?php
}
?>

<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Producto_Theme
 */

?>

	<footer id="colophon" class="site-footer">
	<div class="footer-content">
		<div class="footer-branding">
			<h3 class="footer-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
			</h3>
			<p class="footer-message">Proyecto de prueba - E-commerce con WordPress y WooCommerce</p>
		</div>

		<nav class="footer-navigation">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'footer-menu',
					'container'      => false,
				)
			);
			?>
		</nav>
	</div>

	<div class="site-info">
		<p>&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. Todos los derechos reservados.</p>
	</div>
</footer>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

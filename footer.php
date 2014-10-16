<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Minimus
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
			<div class="container">
				<ul class="medium-block-grid-5 small-block-grid-2">
			    <?php dynamic_sidebar( 'footer-1' ); ?>
				</ul>
			</div>
		<?php endif; ?>
		<div class="container site-info">
			<span class="copyright"><?php echo date( "Y" ); echo " "; bloginfo( 'name' ); ?></span>
			<span class="designer">Developed <a href="https://byjust.in">byJust.in</a></span>
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

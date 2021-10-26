<?php global $wp_query; ?>

<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<nav id="nav-below" class="navigation" role="navigation">
		<div class="nav-previous"><?php next_posts_link(sprintf( __( '%s older', 'betschart-gh' ), '<span class="meta-nav">&larr;</span>' ) ) ?></div>
		<div class="nav-next"><?php previous_posts_link(sprintf( __( 'newer %s', 'betschart-gh' ), '<span class="meta-nav">&rarr;</span>' ) ) ?></div>
	</nav>
<?php endif; ?>

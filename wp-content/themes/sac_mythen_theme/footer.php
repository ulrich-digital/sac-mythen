<div class="clear"></div>
</div>

<footer id="footer">
	<div class="footer_container_top">
		<div class="footer_menu_1"><?php wp_nav_menu(array( 'theme_location' => 'footer_menu_1' )); ?></div>
		<div class="footer_menu_2"><?php wp_nav_menu(array( 'theme_location' => 'footer_menu_2' )); ?></div>
	</div>
	<!--<div class="footer_container_middle">
		<div class="footer_menu_3"><?php wp_nav_menu(array( 'theme_location' => 'footer_menu_3' )); ?></div>
	</div>-->
		<?php 
		$social_media_array= array('instagram', 'facebook', 'vimeo');
		$html_output = "";
		foreach($social_media_array as $item):
			if(get_field($item, 'options')):
				$html_output .= '<div class="item"> <a href="' . get_field($item, 'options') . '" target="_blank"><i class="fa-brands fa-' . $item . '"></i></a></div>';
			endif; 
		endforeach;?>
		
		<div class="social_media">
			<?php echo $html_output;?>
		</div>

		<div class="footer_menu_4"><?php wp_nav_menu(array( 'theme_location' => 'footer_menu_impressum_datenschutz' )); ?></div>

</footer>

</div>
<?php wp_footer(); ?>
</body>
</html>

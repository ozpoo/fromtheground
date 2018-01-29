<?php get_header(); ?>

	<main role="main">

		<section>

			<?php if (have_posts()): while (have_posts()) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<p><?php the_title(); ?></p>
					<?php $short_name = get_field("short_name"); ?>
					<?php if($short_name): ?>
						<p><?php echo $short_name; ?></p>
					<?php endif; ?>
					<?php the_content(); ?>
					<p>
						<?php
							$terms = get_the_terms( get_the_ID() , array( 'movement_category') );
							$i = 1;
							foreach ( $terms as $term ) {
							 $term_link = get_term_link( $term, array( 'movement_category') );
							 if( is_wp_error( $term_link ) )
							 continue;
							 echo $term->name;
							 echo ($i < count($terms))? " / " : "";
							 $i++;
							}
						?>
					</p>

				</article>

			<?php endwhile; ?>

			<?php endif; ?>

		</section>

	</main>

<?php get_footer(); ?>

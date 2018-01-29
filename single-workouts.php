<?php get_header(); ?>

	<main role="main">

		<section>

			<?php if (have_posts()): while (have_posts()) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<p><?php the_title(); ?></p>
					<p>
						<?php
							if( get_field('workout') ):
								while ( has_sub_field('workout') ) :
									if( get_row_layout() == 'amrap' ):
										echo "AMRAP";
									elseif( get_row_layout() == 'rft' ):
										echo "RFT";
									elseif( get_row_layout() == 'emom' ):
										echo "EMOM";
									elseif( get_row_layout() == 'tabata' ):
										echo "TABATA";
									elseif( get_row_layout() == 'other' ):
										echo "Other";
									endif;
								endwhile;
							endif;
						?>
					</p>
					<p>
						<?php
							$terms = get_the_terms( get_the_ID() , array( 'workout_category') );
							$i = 1;
							foreach ( $terms as $term ) {
							 $term_link = get_term_link( $term, array( 'workout_category') );
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

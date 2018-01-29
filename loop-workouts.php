<ul class="workouts">
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
		<li>
			<article class="workout">
				<div class="title">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<span class="title">
							<span class="trans">
								<strong><?php the_title(); ?></strong>
							</span>
						</span>
						<span class="category">
							<span class="trans">
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
							</span>
						</span>
					</a>
				</div>
				<div class="content">
					<?php if( have_rows('type') ):
						while ( have_rows('type') ) : the_row();
							if( get_row_layout() == 'amrap' ):
								// $title = get_sub_field('title');
								echo "<p>AMRAP</p>";
							elseif( get_row_layout() == 'rft' ):
								echo "<p>RFT</p>";
							elseif( get_row_layout() == 'emom' ):
								echo "<p>EMOM</p>";
							elseif( get_row_layout() == 'tabata' ):
								echo "<p>TABATA</p>";
							elseif( get_row_layout() == 'hiit' ):
								echo "<p>HIIT</p>";
							elseif( get_row_layout() == 'custom' ):
								echo "<p>Custom</p>";
							endif;
						endwhile;
					endif;
				  ?>
					<?php echo get_field("about"); ?>
				</div>
			</article>
		</li>
	<?php endwhile; endif; ?>
</ul>

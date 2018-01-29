<ul class="movements">
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
		<li>
			<article class="movement">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<span class="title">
							<span class="trans">
								<strong><?php the_title(); ?></strong>
							</span>
						</span>
						<span class="category">
							<span class="trans">
								<small>
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
								</small>
							</span>
						</span>
					</a>
			</article>
		</li>
	<?php endwhile; endif; ?>
</ul>

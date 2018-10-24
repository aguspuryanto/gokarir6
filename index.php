<?php

/**
 * The main template file.
 */

get_header(); ?>

	<!-- download Section Start -->
    <section id="download">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-xs-12">            
            <div class="download-thumb wow fadeInLeft" data-wow-delay="0.2s">
              <img class="img-fluid" src="<?=get_template_directory_uri();?>/img/mac.png" alt="">
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="download-wrapper wow fadeInRight" data-wow-delay="0.2s">
              <div>
                <div class="download-text">
                  <h4>Download Our App From Store</h4>
                  <p>Appropriately implement one-to-one catalysts for change vis-a-vis wireless catalysts for change. Enthusiastically architect adaptive.</p>
                </div>
                <div class="header-button">
                	<a href="#" class="btn btn-common btn-effect"><i class="lni-android"></i> From PlayStore<br></a>
                	<a href="#" class="btn btn-apple"><i class="lni-apple"></i> From AppStore<br></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- download Section Start -->

    <!-- Blog Section -->
    <section id="blog" class="section">
      <!-- Container Starts -->
      <div class="container">
        <div class="row">

		  	<div id="main" class="col-md-8 col-xs-12 clearfix">

		  		<div class="card text-white bg-primary mb-3">
					<div class="card-header">
						<h3 class="premium-title">LOWONGAN KERJA PREMIUM</h3>
					</div>						
					<ul class="list-group list-group-flush">
			  		<?php
					$args = array('post__in' => get_option( 'sticky_posts' ), 'orderby' => date, 'posts_per_page' => -1);
					$my_query = new WP_Query( $args );						
					$i=1;
					while ( $my_query->have_posts() ) :  $my_query->the_post(); 
						$do_not_duplicate = $post->ID; ?>

						<!-- Blog Item Starts -->
				        <li itemscope="<?=$i;?>" itemtype="http://schema.org/JobPosting" class="list-group-item media sticky">
				        	<?php if( has_post_thumbnail() ) : ?>
				            <div class="blog-item-img">
				                <a href="<?=get_permalink($post->ID);?>">
				                  <img src="<?=get_template_directory_uri();?>/img/blog/img1.jpg" alt="">
				                </a>   
				                <div class="author-img">
				                  <img src="<?=get_template_directory_uri();?>/img/blog/author.png" alt="">
				                </div>             
				            </div>
				            <?php endif; ?>

				            <div class="blog-item-text clearfix"> 
				                <h3><a href="<?=get_permalink($post->ID);?>"><?=$post->post_title;?></a></h3>
				                <div class="author">
				                	<p class="float-left"><?=get_post_meta( $post->ID, "company", true);?></p>
				                  	<span class="date float-right"><?=get_the_time('d, M Y', $post->ID);?></span>
				                </div>
				            </div>
				        </li>
				        <!-- Blog Item Wrapper Ends-->
					<?php
						$i++;								
					endwhile;
					wp_reset_query();						
					?>
					</ul>
				</div>

		  	</div>

		  	<?php get_sidebar(); ?>

	  	</div>
      </div>
    </section>
    <!-- blog Section End -->

<?php get_footer(); ?>
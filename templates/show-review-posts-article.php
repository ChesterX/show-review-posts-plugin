<div class="show-review-posts-row show-review-posts-flex-article">
<?php
// custom query args setup
$currentPage = get_query_var( 'paged' );
if ( $category_id ) {
	$args = array(
		'tax_query' => array(
			array(
				'taxonomy' => 'srp_review_tax_cat',
				'field' => 'tax_ID',
				'terms' => $category_id
			)
		),
		'post_type'      => $post_type_slug,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'posts_per_page' => $posts_per_page,
		'paged'          => $currentPage
	);
} else {
	$args = array(
		'post_type'      => $post_type_slug,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'posts_per_page' => $posts_per_page,
		'paged'          => $currentPage
	);
}

$custom_query = new WP_Query( $args );

    
if ( $custom_query->have_posts() ) :
	while ( $custom_query->have_posts() ) : $custom_query->the_post();
		$post_id = get_the_ID();
		$text_content = get_the_content();
		$post_url = get_permalink();
?>
<article class="review-posts-article">
    <header class="review-posts-entry-header">
		<div class="review-posts-left">
			<span class="review-author-name">
				<strong>
					<?php
                        $review_author_name = get_post_meta( get_the_ID(), 'srp_author_name_meta', true );
						if ( ! empty( $review_author_name ) ) {
							echo $review_author_name;
						} else {
							echo get_the_author();
						}
                    ?>
				</strong>
			</span>

								<?php
									// get link address

										// Get the custom post class.
										$author_description_text = get_post_meta(  get_the_ID(), 'srp_author_description_meta', true );

										// If a post class was input, sanitize it and add it to the post class array.
										if ( ! empty( $author_description_text ) ) { ?>
											<span class="review-author-description">
														<?php echo $author_description_text ?>
													</span>
										<?php } ?>

							<?php
							// get link address

								// Get the custom post class.
								$review_link      = get_post_meta(  get_the_ID(), 'srp_review_link_meta', true );
								$review_link_text = get_post_meta(  get_the_ID(), 'srp_review_link_text_meta', true );

								// If a post class was input, sanitize it and add it to the post class array.
								if ( ! empty( $review_link ) && ! empty( $review_link_text ) ) { ?>
									<a href="<?php echo $review_link ?>" class="review-posts-link-to-source" target="_blank">
										<?php echo $review_link_text ?>
									</a>
								<?php }
							?>
						</div>

						<div class="review-posts-right">
						<svg width="34" height="24" viewBox="0 0 34 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M6.67827 16.7929C5.83276 16.7929 5.0577 16.6661 4.35311 16.4124C3.66261 16.1588 3.07074 15.7853 2.57752 15.2921C2.0843 14.7989 1.69678 14.193 1.41494 13.4743C1.1331 12.7556 0.992188 11.9312 0.992188 11.0011C0.992188 10.1134 1.14015 9.31716 1.43608 8.61257C1.73201 7.89388 2.13363 7.28793 2.64094 6.79471C3.14825 6.2874 3.7542 5.90692 4.45879 5.65327C5.16339 5.38552 5.93844 5.25165 6.78396 5.25165C7.62947 5.25165 8.39748 5.38552 9.08799 5.65327C9.77849 5.90692 10.3633 6.2874 10.8424 6.79471C11.3356 7.28793 11.7161 7.89388 11.9839 8.61257C12.2657 9.31716 12.4066 10.1134 12.4066 11.0011C12.4066 11.9312 12.2657 12.7556 11.9839 13.4743C11.702 14.193 11.3145 14.7989 10.8213 15.2921C10.3281 15.7853 9.72917 16.1588 9.02457 16.4124C8.31998 16.6661 7.53788 16.7929 6.67827 16.7929ZM19.5385 16.7929C18.693 16.7929 17.9179 16.6661 17.2134 16.4124C16.5228 16.1588 15.931 15.7853 15.4378 15.2921C14.9445 14.7989 14.557 14.193 14.2752 13.4743C13.9933 12.7556 13.8524 11.9312 13.8524 11.0011C13.8524 10.1134 14.0004 9.31716 14.2963 8.61257C14.5923 7.89388 14.9939 7.28793 15.5012 6.79471C16.0085 6.2874 16.6144 5.90692 17.319 5.65327C18.0236 5.38552 18.7987 5.25165 19.6442 5.25165C20.4897 5.25165 21.2577 5.38552 21.9482 5.65327C22.6387 5.90692 23.2235 6.2874 23.7027 6.79471C24.1959 7.28793 24.5764 7.89388 24.8441 8.61257C25.126 9.31716 25.2669 10.1134 25.2669 11.0011C25.2669 11.9312 25.126 12.7556 24.8441 13.4743C24.5623 14.193 24.1747 14.7989 23.6815 15.2921C23.1883 15.7853 22.5894 16.1588 21.8848 16.4124C21.1802 16.6661 20.3981 16.7929 19.5385 16.7929ZM18.4605 8.57026C18.7705 8.41525 19.1369 8.33775 19.5597 8.33775C19.9824 8.33775 20.3418 8.41525 20.6377 8.57026C20.9477 8.71118 21.1943 8.91552 21.3775 9.18326C21.5748 9.43692 21.7157 9.7258 21.8003 10.0499C21.8848 10.3599 21.9271 10.6841 21.9271 11.0223C21.9271 11.4027 21.8848 11.755 21.8003 12.0791C21.7157 12.4033 21.5748 12.6851 21.3775 12.9247C21.1943 13.1642 20.9477 13.3615 20.6377 13.5165C20.3418 13.6715 19.9824 13.749 19.5597 13.749C19.1369 13.749 18.7705 13.6786 18.4605 13.5377C18.1646 13.3827 17.9179 13.1854 17.7207 12.9458C17.5234 12.6921 17.3754 12.4033 17.2768 12.0791C17.1922 11.755 17.1499 11.4027 17.1499 11.0223C17.1499 10.6841 17.1922 10.3599 17.2768 10.0499C17.3754 9.7258 17.5234 9.43692 17.7207 9.18326C17.9179 8.91552 18.1646 8.71118 18.4605 8.57026ZM6.69942 8.33775C6.27667 8.33775 5.91028 8.41525 5.60025 8.57026C5.30432 8.71118 5.05772 8.91552 4.86043 9.18326C4.66315 9.43692 4.51518 9.7258 4.41654 10.0499C4.33199 10.3599 4.28971 10.6841 4.28971 11.0223C4.28971 11.4027 4.33199 11.755 4.41654 12.0791C4.51518 12.4033 4.66315 12.6921 4.86043 12.9458C5.05772 13.1854 5.30432 13.3827 5.60025 13.5377C5.91028 13.6786 6.27667 13.749 6.69942 13.749C7.12218 13.749 7.48153 13.6715 7.77746 13.5165C8.08748 13.3615 8.33409 13.1642 8.51728 12.9247C8.71457 12.6851 8.85549 12.4033 8.94004 12.0791C9.02459 11.755 9.06687 11.4027 9.06687 11.0223C9.06687 10.6841 9.02459 10.3599 8.94004 10.0499C8.85549 9.7258 8.71457 9.43692 8.51728 9.18326C8.33409 8.91552 8.08748 8.71118 7.77746 8.57026C7.48153 8.41525 7.12218 8.33775 6.69942 8.33775Z" fill="#072630"/>
							<path fill-rule="evenodd" clip-rule="evenodd" d="M21.1038 22.1845C16.9854 24.5639 9.9289 24.6151 4.98633 22.2753L6.5741 19.0043C10.7368 20.5153 15.847 20.5269 19.3787 18.8577L21.1038 22.1845Z" fill="url(#paint0_angular_516_706)"/>
							<defs>
							<radialGradient id="paint0_angular_516_706" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12.9917 12.3036) rotate(133.452) scale(12.6294 12.8538)">
							<stop stop-color="#7DCDD4"/>
							<stop offset="0.765625" stop-color="#ABD490"/>
							</radialGradient>
							</defs>
						</svg>
							<span class="review-posts-date">
								<?php the_time( 'j F Y' ); ?>
							</span>
						</div>
					</header><!-- .review-posts-entry-header -->

					<div class="review-posts-entry-content <?php if ( empty( $text_content ) ) echo 'hide' ;  ?>">
						<p>
							<?php
							echo wp_trim_words(get_the_content(), 25, '...');
							?>
						</p>
					</div><!-- .review-posts-entry-content -->

					<div class="review-posts-full-content">
						<?php the_content(); ?>

					</div><!-- .eview-posts-full-content -->

					<footer class="review-posts-entry-footer <?php //if ($srp_words_num <= NUMBER_OF_WORDS) echo 'hide' ; ?>">
								<span class="link-full-review">
									<a href="<?php the_permalink() ?>">
										<?php _e( 'Read more', 'show_review_posts' ); ?>
									</a>
								</span>
					</footer><!-- .review-posts-entry-footer -->
				</article><!-- .article -->
<?php endwhile; ?>
</div>
<? if ( ! is_singular('srp_review_posts') && $show_on_home_state == 0 ) : ?>
    <div class="show-review-posts-row show-review-posts-flex-nav">
        <?php echo paginate_links( array( 'total' => $custom_query->max_num_pages ) ); ?>
    </div>
<?php
	endif;
	wp_reset_postdata();
endif;
?>

<div style="display: none;" id="fn-fullrev">
	<h2></h2>
	<span class="fn-description"></span>
	<div class="fn-meta"></div>
	<p class="fn-content"></p>
	<div class="show-review-posts-share">
		<p>Share testimonial</p>
		<!-- FB -->
		<a href="#" data-share-url="https://www.facebook.com/sharer/sharer.php?u=">
			<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 7H20.504C22.987 7 25 9.013 25 11.496V20.505C25 22.987 22.987 25 20.504 25H11.496C9.013 25 7 22.987 7 20.504V11.5C7 9.015 9.015 7 11.5 7V7Z" stroke="#7DCDD4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.0996 16.9H20.4996" stroke="#7DCDD4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.5004 12.4H19.5554C18.0894 12.4 16.9004 13.589 16.9004 15.055V16V25" stroke="#7DCDD4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path> </svg>
		</a>
		<!-- /FB -->
		<!-- IN -->
		<a href="#"  data-share-url="https://www.linkedin.com/shareArticle?mini=true&amp;url=">
			<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 7H20.504C22.987 7 25 9.013 25 11.496V20.505C25 22.987 22.987 25 20.504 25H11.496C9.013 25 7 22.987 7 20.504V11.5C7 9.015 9.015 7 11.5 7V7Z" stroke="#7DCDD4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12.1201 15.1V20.5" stroke="#7DCDD4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.7188 20.5V17.35C15.7188 16.107 16.7257 15.1 17.9688 15.1V15.1C19.2118 15.1 20.2188 16.107 20.2188 17.35V20.5" stroke="#7DCDD4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12.1181 11.838C11.9941 11.838 11.8931 11.939 11.8941 12.063C11.8941 12.187 11.9951 12.288 12.1191 12.288C12.2431 12.288 12.3441 12.187 12.3441 12.063C12.3441 11.938 12.2431 11.838 12.1181 11.838" stroke="#7DCDD4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path> </svg>
		</a>
		<!-- /IN -->
		<!-- TWITER -->
		<a href="#"  data-share-url="https://twitter.com/intent/tweet?text=">
			<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M24.1 13.35C24.1 12.8529 23.6971 12.45 23.2 12.45C22.7029 12.45 22.3 12.8529 22.3 13.35H24.1ZM7.72678 20.0191C7.43361 19.6177 6.87056 19.53 6.46917 19.8232C6.06777 20.1164 5.98004 20.6794 6.27322 21.0808L7.72678 20.0191ZM6.84708 19.6631C6.35725 19.7475 6.02863 20.2131 6.11309 20.7029C6.19754 21.1927 6.66309 21.5213 7.15292 21.4369L6.84708 19.6631ZM9.7 19.65L9.98595 20.5033C10.2806 20.4046 10.5035 20.1608 10.5755 19.8585C10.6475 19.5561 10.5584 19.238 10.3399 19.0171L9.7 19.65ZM8.8 8.84998L9.5927 8.42379C9.44556 8.15012 9.16766 7.9717 8.85758 7.95182C8.5475 7.93194 8.2491 8.07341 8.06822 8.32605L8.8 8.84998ZM14.2 13.35L13.9875 14.2245C14.2507 14.2885 14.5287 14.2305 14.7444 14.0666C14.9601 13.9028 15.0906 13.6505 15.0995 13.3797L14.2 13.35ZM22.3 10.65L21.5486 11.1453C21.7151 11.3979 21.9974 11.55 22.3 11.55V10.65ZM25 10.65L25.7488 11.1492C25.933 10.873 25.9501 10.5179 25.7935 10.2253C25.6369 9.93266 25.3319 9.74998 25 9.74998V10.65ZM22.4512 12.8507C22.1754 13.2643 22.2872 13.8231 22.7008 14.0988C23.1143 14.3745 23.6731 14.2628 23.9488 13.8492L22.4512 12.8507ZM22.3 13.35C22.3 16.3343 21.4481 18.8052 19.9376 20.517C18.4395 22.2149 16.2212 23.25 13.3 23.25V25.05C16.6788 25.05 19.4105 23.8351 21.2874 21.7079C23.1519 19.5948 24.1 16.6656 24.1 13.35H22.3ZM13.3 23.25C11.6735 23.25 10.597 22.8309 9.79124 22.2607C8.95795 21.671 8.35956 20.8855 7.72678 20.0191L6.27322 21.0808C6.89744 21.9354 7.64905 22.9499 8.75151 23.73C9.88146 24.5296 11.3265 25.05 13.3 25.05V23.25ZM7.15292 21.4369C7.1814 21.432 7.20344 21.4267 7.21102 21.4249C7.22137 21.4224 7.22972 21.4202 7.23471 21.4189C7.24467 21.4162 7.25274 21.4138 7.25683 21.4126C7.26555 21.4101 7.27333 21.4077 7.27808 21.4062C7.28838 21.403 7.30012 21.3992 7.3116 21.3955C7.33524 21.3879 7.36737 21.3774 7.40571 21.3648C7.48285 21.3394 7.5906 21.3037 7.71805 21.2613C7.97322 21.1764 8.31036 21.0639 8.64615 20.9517C8.98204 20.8394 9.31698 20.7274 9.56792 20.6433C9.6934 20.6013 9.7979 20.5663 9.87102 20.5418C9.90759 20.5296 9.93631 20.52 9.9559 20.5134C9.96569 20.5101 9.9732 20.5076 9.97826 20.5059C9.98079 20.5051 9.98271 20.5044 9.984 20.504C9.98464 20.5038 9.98513 20.5036 9.98545 20.5035C9.98562 20.5035 9.98574 20.5034 9.98582 20.5034C9.98586 20.5034 9.98589 20.5034 9.98591 20.5034C9.98594 20.5033 9.98595 20.5033 9.7 19.65C9.41405 18.7966 9.41404 18.7966 9.41402 18.7966C9.414 18.7966 9.41397 18.7966 9.41393 18.7966C9.41385 18.7967 9.41373 18.7967 9.41357 18.7968C9.41325 18.7969 9.41277 18.797 9.41213 18.7973C9.41085 18.7977 9.40894 18.7983 9.40641 18.7992C9.40137 18.8009 9.39387 18.8034 9.3841 18.8066C9.36454 18.8132 9.33585 18.8228 9.29932 18.835C9.22627 18.8595 9.12186 18.8945 8.99648 18.9365C8.74571 19.0204 8.41109 19.1324 8.0756 19.2445C7.74002 19.3567 7.40396 19.4688 7.15017 19.5532C7.02313 19.5954 6.91746 19.6305 6.84298 19.655C6.80551 19.6673 6.77727 19.6765 6.7586 19.6826C6.74892 19.6857 6.74403 19.6872 6.74252 19.6877C6.74136 19.6881 6.74445 19.6871 6.74972 19.6856C6.75209 19.6849 6.75874 19.6829 6.76762 19.6805C6.77206 19.6793 6.77994 19.6773 6.7899 19.6749C6.79709 19.6731 6.81882 19.6679 6.84708 19.6631L7.15292 21.4369ZM10.3399 19.0171C7.64849 16.2961 7.4478 12.2847 9.53178 9.3739L8.06822 8.32605C5.4842 11.9353 5.73951 16.9258 9.06014 20.2829L10.3399 19.0171ZM8.0073 9.27616C9.20199 11.4983 11.4522 13.6084 13.9875 14.2245L14.4125 12.4754C12.4958 12.0096 10.612 10.3197 9.5927 8.42379L8.0073 9.27616ZM15.0995 13.3797C15.136 12.2765 15.5245 11.3746 16.1314 10.7544C16.7334 10.1392 17.5996 9.74998 18.7 9.74998V7.94998C17.1494 7.94998 15.8086 8.51072 14.8449 9.4955C13.886 10.4754 13.35 11.8234 13.3005 13.3202L15.0995 13.3797ZM18.7 9.74998C19.4863 9.74998 20.0512 9.89907 20.4783 10.127C20.9034 10.3539 21.2474 10.6884 21.5486 11.1453L23.0514 10.1547C22.6196 9.49955 22.0661 8.93409 21.3257 8.53897C20.5873 8.14489 19.7187 7.94998 18.7 7.94998V9.74998ZM22.3 11.55H25V9.74998H22.3V11.55ZM24.2512 10.1507L22.4512 12.8507L23.9488 13.8492L25.7488 11.1492L24.2512 10.1507Z" fill="#7DCDD4"></path> </svg>
		</a>
		<!-- /TWITER -->
	</div>

</div>
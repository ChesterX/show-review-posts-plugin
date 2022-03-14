jQuery( document ).ready(function($) {
	// $('#share').on('click', function(){
	// 	 $.fancybox.open( $('.share'), {

	// 	  });
	// });


	$('.review-posts-entry-footer .link-full-review a').on('click', function(){
		let th = $(this).parents('.review-posts-article');
		let url = $(this).attr('href');
		let name = th.find('.review-author-name').text();
		let description = th.find('.review-author-description').text();
		let meta = th.find('.review-posts-right').html();
		let fullcontent = th.find('.review-posts-full-content').html();
		
		$('#fn-fullrev h2').html('<a href="' + url + '">' + name + '</a>');
		$('#fn-fullrev .fn-description').html(description);
		$('#fn-fullrev .fn-meta').html(meta);
		$('#fn-fullrev .fn-content').html(fullcontent);

		$('#fn-fullrev .show-review-posts-share a').each(function(){
			$(this).attr('href', $(this).data('share-url') + url);
		});
		

		

		$.fancybox.open( $('#fn-fullrev'), {

		});

		return false;
	});

	$('.base64img').each(function(){
		$(this).attr('src', 'data:' + $(this).attr('src'));
	});
});
$(document).ready(function () {
	var alertContainer = $('.block-alert');
	var form = $('.review-form');

	form.on('submit', function (e) {
		e.preventDefault();

		$.ajax({
			url: 'review.php',
			method: 'post',
			dataType: 'json',
			data: form.serialize(),
			success: function (response) {
				if (response.success) {
					form[0].reset();
					alertContainer.addClass('success')
					alertContainer.text('Отзыв добавлен');
					if (response.content.length > 0) {
						$('.review-list').prepend(response.content);
					}
				} else if (response.error) {
					alertContainer.addClass('error');
					alertContainer.html('');
					Object.values(response.error).forEach(function(items) {
						items.forEach(function(error) {
							alertContainer.append(`<div class="text-left">${error}</div>`)
						});

					})
				}
				setTimeout(function() {
					if (alertContainer.hasClass('error')) {
						alertContainer.removeClass('error');
					}
					if (alertContainer.hasClass('success')) {
						alertContainer.removeClass('success')
					}
					alertContainer.html('');
				}, 3000)
			}
		})
	});
});

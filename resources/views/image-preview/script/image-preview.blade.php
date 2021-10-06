<script>
    $(document).ready(function() {
        $('#modal-image-preview').on('show.bs.modal', function(e) {
			

			var id = $(e.relatedTarget).data('followup-id');
			var access_from = $('#access_from').val();
			$('#data-loader-image').show();
			$('#data_image').hide();
			$('#btn_close').hide();
			$.ajax({
                url: APP_URL + '/followup/' + access_from + '/photo/' + id,
				type: 'GET',
				success: function(response){
					$('#div_image_top').remove();

					var content = "";
					content += `<div id="div_image_top">`;
					content += `<div class="swiper-container">`;
					content += `<div class="swiper-wrapper">`;

					$.each(response, function (index, val) { 
						content += `<div class="swiper-slide"><img src="` +val['photo'] + `"></div>`;
					});

					content += `</div>`;
					content += `<div class="swiper-pagination"></div>
								<div class="swiper-button-prev"></div>
								<div class="swiper-button-next"></div>`;
					content +=	`</div></div>`;
					
					$('#data_image').append(content);

					var swiper = new Swiper('.swiper-container', {
						// Optional parameters
						loop: true,

						// If we need pagination
						pagination: {
							el: '.swiper-pagination',
						},

						// Navigation arrows
						navigation: {
							nextEl: '.swiper-button-next',
							prevEl: '.swiper-button-prev',
						},

						// And if we need scrollbar
						scrollbar: {
							el: '.swiper-scrollbar',
						},
					});
				},
				complete: function(){
					$('#data-loader-image').hide();
					$('#data_image').show();
					$('#btn_close').show();
				}
            });
        });
    });
</script>
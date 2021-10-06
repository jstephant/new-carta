<script>
    $(document).ready(function() {
		$('#customer_category, #type').select2({
            minimumResultsForSearch: -1
        });

		// $('#type').on('change', function(){
		// 	$('#products').val('');
		// 	$('#products').trigger('change');
		// });

		$('#type').on('change', function(){
			$.ajax({
				type: "GET",
				url: APP_URL + "/ajax/product_by_type",
				data: {
					type: $('#type').val()
				},
				dataType: "json",
				success: function (response) {
					$('#products').find('option').remove();
					var content="";
					$.each(response, function (indx, value) { 
						 content += "<option value='" +value['product_id'] + "'>" + value['product_name']+ "</option>"
					});
					$('#products').append(content);
				}
			});
		});
		$("#products").select2();
			// language: {
			// 	inputTooShort: function () {
    		// 		return "Please enter 3 or more characters (product name)";
  			// 	}
			// },
			// allowClear: true,
			// minimumInputLength: 3,
            // closeOnSelect: true,
			// width: 'resolve',
			// ajax: {
            //     url: APP_URL + "/ajax/product_by_type",
            //     dataType: 'json',
            //     type: 'GET',
			// 	data: function(params) {
            //         var query = { 
			// 			q: params.term, 
			// 			type: type 
			// 		};
            //         return query;
            //     },
            //     processResults: function(data) {
            //         return {
            //             results: $.map(data, function(item){
			// 				return {
			// 					text: item.caption,
			// 					id: item.product_id
			// 				}
            //             })
            //         };
            //     },
            // },
		// });
    });

	(function() {
		'use strict';
		window.addEventListener('load', function() {
			var forms = document.getElementsByClassName('needs-validation');
			var validation = Array.prototype.filter.call(forms, function(form) {
				form.addEventListener('submit', function(event) {
					if (form.checkValidity() === false) {
						event.preventDefault();
						event.stopPropagation();
					}
					form.classList.add('was-validated');
				}, false);
			});
		}, false);
	})();
</script>
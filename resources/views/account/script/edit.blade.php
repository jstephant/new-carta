<script>
    $(document).ready(function() {
        $("#customer_dob").flatpickr({
			allowInput: true,
			altFormat: "Y-m-d",
			altInput: true,
			dateFormat: "Y-m-d",
			maxDate: new Date(),
        });

		$('#teritory').change(function(){
			var teritory_id = $(this).val();
			if(teritory_id!='') {
				$.ajax({
					url: APP_URL + '/ajax/province/' + teritory_id,
					type: 'GET',
					success: function(result) {
						var provinceopt = "";
						provinceopt += `<option value="" selected></option>`;
						$.each(result, function(index, val){
							provinceopt += `<option value="`+val['province_id']+`">`+ val['name'] + `</option>`;
						});
						$('#province').val('');
						$("#province").html(provinceopt);
						$('#province').trigger('change');
					},
				});
			} else {
                var provinceopt = "";
                provinceopt += `<option value="" selected></option>`;
                $('#province').val('');
                $("#province").html(provinceopt);
                $('#province').trigger('change');
            }
		});

        $('#province').change(function(){
			var province_id = $(this).val();
			if(province_id!='') {
				$.ajax({
					url: APP_URL + '/ajax/city/' + province_id,
					type: 'GET',
					success: function(result) {
						var cityopt = "";
						cityopt += `<option value="" selected></option>`;
						$.each(result, function(index, val){
							cityopt += `<option value="`+val['city_id']+`">`+ val['name'] + `</option>`;
						});
						$('#city').val('');
						$("#city").html(cityopt);
						$('#city').trigger('change');
					},
				});
			} else {
                var cityopt = "";
                cityopt += `<option value="" selected></option>`;
                $('#city').val('');
                $("#city").html(cityopt);
                $('#city').trigger('change');
            }
		});

        $('#city').change(function(){
			var city_id = $(this).val();
			if(city_id!='') {
				$.ajax({
					url: APP_URL + '/ajax/subdistrict/' + city_id,
					type: 'GET',
					success: function(result) {
						var subdistrictopt = "";
						subdistrictopt += `<option value="" selected></option>`;
						$.each(result, function(index, val){
							subdistrictopt += `<option value="`+val['subdistrict_id']+`">`+ val['name'] + `</option>`;
						});
						$('#subdistrict').val('');
						$("#subdistrict").html(subdistrictopt);
						$('#subdistrict').trigger('change');
					},
				});
			} else {
                var subdistrictopt = "";
                subdistrictopt += `<option value="" selected></option>`;
                $('#subdistrict').val('');
                $("#subdistrict").html(subdistrictopt);
                $('#subdistrict').trigger('change');
            }
		});

        $('#subdistrict').change(function(){
			var subdistrict_id = $(this).val();
			if(subdistrict_id!='') {
				$.ajax({
					url: APP_URL + '/ajax/village/' + subdistrict_id,
					type: 'GET',
					success: function(result) {
						var villageopt = "";
						villageopt += `<option value="" selected></option>`;
						$.each(result, function(index, val){
							villageopt += `<option value="`+val['village_id']+`">`+ val['name'] + `</option>`;
						});
						$('#village').val('');
						$("#village").html(villageopt);
						$('#village').trigger('change');
					},
				});
			} else {
                var villageopt = "";
                villageopt += `<option value="" selected></option>`;
                $('#village').val('');
                $("#village").html(villageopt);
                $('#village').trigger('change');
            }
		});

		$('.select-type-1').select2({
			minimumResultsForSearch: -1,
		});

		$('.select-type-2').select2();
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
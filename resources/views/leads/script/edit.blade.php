<script>
    $(document).ready(function() {
		$('.phone').mask('0000-0000-0000');
		
		var occ_selected = $("#occupation option:selected" ).text();
		$('#occupation_name').val(occ_selected);

		$('#div_other_occupation').hide();
		$('#occupation').change(function() {
			var occ_selected_change = $("#occupation option:selected" ).text();
			$('#occupation_name').val(occ_selected_change);
			if(occ_selected_change=='Others') {
				$('#div_other_occupation').show();
				$('#div_other_occupation').prop('required', true);
			} else {
				if($('#other_occupation').val()!='')
				{
					$('#other_occupation').val('');
				}
				$('#div_other_occupation').hide();
				$('#div_other_occupation').prop('required', false);
			}
		});

		if(occ_selected=='Others')
		{
			$('#div_other_occupation').show();
			$('#div_other_occupation').prop('required', true);
		}

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
                        $('#city').select2();
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
                        $('#subdistrict').select2();
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
                        $('#village').select2();
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
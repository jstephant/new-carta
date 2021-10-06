<script>
    $(document).ready(function() {
		$('#nik').change(function(){
			var nik = $(this).val();
			if(nik!='') {
				$.ajax({
					url: APP_URL + '/ajax/staff/' + nik,
					type: 'GET',
					success: function(response) {
                        console.log(response)
                        $('#name').val(response[0].NAMA)
                        $('#email').val(response[0].EMAIL)
					},
				});
			}
		});
        
		$('.select-type-1').select2({
			minimumResultsForSearch: -1,
		});
		$('.select-type-2').select2();
    });
</script>
<script>
    $(document).ready(function() {
        var date = new Date();
        var start_date = date.setDate(date.getDate() - 7);
        $("#start_date").flatpickr({
            dateFormat: "d-m-Y",
            defaultDate: start_date,
            maxDate: new Date(),
        })

        $("#end_date").flatpickr({
            dateFormat: "d-m-Y",
            defaultDate: new Date(),
            maxDate: new Date(),
        })

        var log_table = $('#logging_table').DataTable( {
			processing: false,
            serverSide: true,
            pageLength: 10,
            ajax: {
                type: "GET",
				url: APP_URL + '/logging/list',
				data: function(d) {
					d.start_date = $('#start_date').val()
                    d.end_date = $('#end_date').val()
				}
			},
			dom:
				"<'row'<'col-sm-12 col-md-6'l>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row py-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			lengthChange: true,
			language: {
				lengthMenu: "Show _MENU_ entries",
				paginate: {
					first: "<i class='fa fa-angle-double-left'></i>",
					previous: "<i class='fa fa-angle-left'></i>",
					next: "<i class='fa fa-angle-right'></i>",
					last: "<i class='fa fa-angle-double-right'></i>",
				}
			},
			pagingType: "simple_numbers",
			columns : [
                {
                    render: function (data, type, row, meta) {
                        return (row.nik) ? row.nik : ''
                    }
                },
                {
					orderable: true,
					render: function(data, type, row, meta) {
                        return (row.user) ? row.user.name : '';
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return row.type;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return row.source
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return row.created_at
					}
				}
            ],
            error:setTimeout(1000)
		});

        $('#start_date, #end_date').on('change', function(){
            log_table.ajax.reload()
        })
    })

	function downloadData()
	{
		$.ajax({
			type: 'GET',
			url: "{{ url('/logging/download') }}",
			data: {
				start_date: $('#start_date').val(),
				end_date: $('#end_date').val()
			},
			dataType: 'json',
			success: function (response) {
				alert(response.message)
			}
		})
	}
</script>

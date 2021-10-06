<script>
    $(document).ready(function() {
		var access_from = $('#access_from').val();
		var date = new Date();
        var start_date = date.setDate(date.getDate() - 7);
        $("#start_date").flatpickr({
			dateFormat: "d-m-Y",
			defaultDate: start_date,
			maxDate: new Date(),
        });

        $("#end_date").flatpickr({
			dateFormat: "d-m-Y",
			defaultDate: new Date(),
			maxDate: new Date(),
        });

        var followup_table = $('#followup_table').DataTable( {
			processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: {
                type: "GET",
				url: APP_URL + $('#url_data').val(),
				data: function(d) {
					d.header_id = $('#header_id').val();
					d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
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
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
					orderable: true,
					render: function(data, type, row, meta) {
                        return row.follow_up_date;
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
						var lead_status = "-";
						if(row.status!=null)
							lead_status = row.status
						return lead_status;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return row.customer_feedback;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.hpl;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.edging;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var content = `<a class="btn btn-primary btn-sm" href="#" data-toggle="modal" data-target="#modal-image-preview" data-followup-id="` + row.id + `" data-category="` + row.category + `">View</a>`;
						return content;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return row.category.charAt(0).toUpperCase() + row.category.slice(1);
					}
				},
                {
					orderable: false,
					render: function(data, type, row, meta) {
						return row.follow_up_status;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return getDate(row.created_at);
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var content = `<a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#modal-detail-sales" data-followup-id="` + row.id + `" data-category="` + row.category + `">Sales Detail</a>`;
						return content;
					}
				}
            ],
            error:setTimeout(1000),
			order: [0, 'asc'],
		});

        $('#start_date').on('change', function(){
            followup_table.ajax.reload();
        });

        $('#end_date').on('change', function(){
            followup_table.ajax.reload();
        });
    });

</script>

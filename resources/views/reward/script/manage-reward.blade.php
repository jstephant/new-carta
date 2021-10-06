<script>
    $(document).ready(function() {
		var my_menu = JSON.parse($('#my_menu').val());
        var manage_reward_table = $('#manage_reward_table').DataTable( {
			processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: {
                type: "GET",
				url: APP_URL + '/reward/manage/list'
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
                        return row.description;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return row.customer_category.category;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
                        var hpl = '';
                        if(row.type!=null && row.type=='HPL')
                            hpl = row.quantity;
                        return hpl;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
                        var edging = '';
                        if(row.type!=null && row.type=='Edging')
                            edging = row.quantity
                        return edging;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.points;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.model;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
                        return row.sku;
					}
				},
				{
					orderable: false,
					render: function(data, type, row, meta) {
						return row.notes;
					}
				},
				{
					orderable: false,
					render: function(data, type, row, meta) {
						var content = '';
						if(my_menu.delete==true)
							content  = `<a class="btn btn-danger btn-sm" href="{{url('/reward/manage/delete/` + row.reward_id + `')}}">Delete</a>`;
						return content;
					}
				},
				
            ],
            createdRow: function (row, data, rowIndex) {
                // Per-cell function to do whatever needed with cells
                $.each($('td', row), function (colIndex) {
                    if(colIndex=="0") {
                        $(this).attr('data-title', "Description");
					} else if(colIndex=="1") {
                        $(this).attr('data-title', "Segmentation");
					} else if(colIndex=="2") {
                        $(this).attr('data-title', "# of Sheets");
					} else if(colIndex=="3") {
                        $(this).attr('data-title', "# of Meters");
					} else if(colIndex=="4") {
                        $(this).attr('data-title', "Points");
					} else if(colIndex=="5") {
                        $(this).attr('data-title', "Model");
					} else if(colIndex=="6") {
                        $(this).attr('data-title', "SKU");
					} else if(colIndex=="7") {
                        $(this).attr('data-title', "Notes");
					}
                });
            },
            error:setTimeout(1000),
			order: [0, 'asc'],
		});
    });
</script>
<script>
    $(document).ready(function() {
		var my_menu = JSON.parse($('#my_menu').val());
        var manage_reward_item_table = $('#manage_reward_item_table').DataTable( {
			processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: {
                type: "GET",
				url: APP_URL + '/reward/manage/item/list'
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
                        return row.item_code;
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
                        return row.points_required
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
                        return row.segmentation;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.notes;
					}
				},
				{
					orderable: false,
					render: function(data, type, row, meta) {
						var content = '';
						if(my_menu.delete==true)
							content = `<a class="btn btn-danger btn-sm" href="{{url('/reward/manage/item/delete/` + row.reward_item_id + `')}}">Delete</a>`;
						return content;
					}
				},
            ],
            createdRow: function (row, data, rowIndex) {
                // Per-cell function to do whatever needed with cells
                $.each($('td', row), function (colIndex) {
                    if(colIndex=="0") {
                        $(this).attr('data-title', "Item Code");
					} else if(colIndex=="1") {
                        $(this).attr('data-title', "Description");
					} else if(colIndex=="2") {
                        $(this).attr('data-title', "# of Points");
					} else if(colIndex=="3") {
                        $(this).attr('data-title', "Segmentation");
					} else if(colIndex=="4") {
                        $(this).attr('data-title', "Notes");
					}
                });
            },
            error:setTimeout(1000),
			order: [0, 'asc'],
		});
    });
</script>
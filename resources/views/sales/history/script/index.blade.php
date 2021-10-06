<script>
    $(document).ready(function() {
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

        var sales_history_table = $('#sales_history_table').DataTable( {
			processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: {
                type: "GET",
				url: APP_URL + $('#url_data').val(),
				data: function(d) {
					d.id = $('#header_id').val();
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
						return getDate(row.order_date);
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var hpl = '';
						if(row.account_follow_up!=null && row.account_follow_up.hpl!=null)
							hpl = row.account_follow_up.hpl;
						return hpl;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var edging = '';
						if(row.account_follow_up!=null && row.account_follow_up.edging!=null) 
							edging = row.account_follow_up.edging;
						return edging;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var point = '';
						if(row.product_carta!=null)
							point = row.product_carta[0].points;
						return point;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						var store = '';
						if(row.collect!=null)
							store = row.collect;
						return store;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var content=''
						if(row.sales_networking_visit_id!=null)
							content = `<a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#modal-detail-sales" data-followup-id="` + row.sales_networking_visit_id + `">Sales Detail</a>`;
						return content;
					}
				}
            ],
            createdRow: function (row, data, rowIndex) {
                // Per-cell function to do whatever needed with cells
                $.each($('td', row), function (colIndex) {
                    if(colIndex=="0") {
                        $(this).attr('data-title', "Date of Purchase");
					} else if(colIndex=="1") {
                        $(this).attr('data-title', "HPL (sheets)");
					} else if(colIndex=="2") {
                        $(this).attr('data-title', "Edging (m)");
					} else if(colIndex=="3") {
                        $(this).attr('data-title', "Points");
					} else if(colIndex=="4") {
                        $(this).attr('data-title', "Store");
					}
                });
            },
            error:setTimeout(1000),
			order: [0, 'asc'],
		});

        $('#start_date').on('change', function(){
            sales_history_table.ajax.reload();
        });

        $('#end_date').on('change', function(){
            sales_history_table.ajax.reload();
        });
    });
</script>
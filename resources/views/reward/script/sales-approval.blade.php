<script>
    $(document).ready(function() {
		var my_menu = JSON.parse($('#my_menu').val());
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

		$('#status').select2({
			minimumResultsForSearch: -1,
		});

        var sales_approval_table = $('#sales_approval_table').DataTable( {
			processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: {
                type: "GET",
				url: APP_URL + '/reward/sales-approval/list',
                data: function(d) {
					d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
					d.status = $('#status').val();
                    d.name = $('#name').val();
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
                        return row.account_follow_up.customer_account.customer_name;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return row.account_follow_up.customer_account.customer_category.category;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.account_follow_up.customer_account.assigned_to.name;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.account_follow_up.customer_account.teritory.name;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.order_date;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.account_follow_up.hpl;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.account_follow_up.edging;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.account_follow_up.customer_account.points;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.collect;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.sales_approval_status.status;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.approval_date;
					}
				},
				{
					orderable: false,
					render: function(data, type, row, meta) {
						var content=''
						if(my_menu.approval==true && row.sales_networking_visit_id!=null)
							content = `<a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#modal-detail-sales-approval" data-followup-id="` + row.sales_networking_visit_id + `" data-status="` + row.approval_status + `">Detail</a>`;
						return content;
					}
				},
            ],
            createdRow: function (row, data, rowIndex) {
                // Per-cell function to do whatever needed with cells
                $.each($('td', row), function (colIndex) {
                    if(colIndex=="0") {
                        $(this).attr('data-title', "Name");
					} else if(colIndex=="1") {
                        $(this).attr('data-title', "Type");
					} else if(colIndex=="2") {
                        $(this).attr('data-title', "Assigend To");
					} else if(colIndex=="3") {
                        $(this).attr('data-title', "Area");
					} else if(colIndex=="4") {
                        $(this).attr('data-title', "Date of Purchase");
					} else if(colIndex=="5") {
                        $(this).attr('data-title', "HPL (sheets)");
					} else if(colIndex=="6") {
                        $(this).attr('data-title', "Edging (m)");
					} else if(colIndex=="7") {
                        $(this).attr('data-title', "Points");
					} else if(colIndex=="8") {
                        $(this).attr('data-title', "Store");
					} else if(colIndex=="9") {
                        $(this).attr('data-title', "Status");
					} else if(colIndex=="10") {
                        $(this).attr('data-title', "Approved On");
					}

                });
            },
            error:setTimeout(1000),
			order: [0, 'asc'],
		});

        $('#start_date').on('change', function(){
            sales_approval_table.ajax.reload();
        });

        $('#end_date').on('change', function(){
            sales_approval_table.ajax.reload();
        });

        $('#status').on('change', function(){
            sales_approval_table.ajax.reload();
        });

        $('#name').on('input', function(){
			sales_approval_table.ajax.reload();
		});
    });
</script>
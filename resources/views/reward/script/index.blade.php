<script>
    $(document).ready(function() {
		var my_menu = JSON.parse($('#my_menu').val());
		var followup_menu = JSON.parse($('#followup_menu').val());
        var sales_menu = JSON.parse($('#sales_menu').val());
        var redeem_menu = JSON.parse($('#redeem_menu').val());

		var date = new Date();
        var start_date = date.setDate(date.getDate() - 7);
        $("#followup_start").flatpickr({
			dateFormat: "d-m-Y",
			defaultDate: start_date,
			maxDate: new Date(),
        });

        $("#followup_to").flatpickr({
			dateFormat: "d-m-Y",
			defaultDate: new Date(),
			maxDate: new Date(),
        });

        $("#purchase_start").flatpickr({
			dateFormat: "d-m-Y",
			defaultDate: start_date,
			maxDate: new Date(),
        });

        $("#purchase_to").flatpickr({
			dateFormat: "d-m-Y",
			defaultDate: new Date(),
			maxDate: new Date(),
        });

		$("#assigned_to").select2();

		var customer_reward_table = $('#customer_reward_table').DataTable( {
			processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: {
                type: "GET",
				url: APP_URL + $('#url_data').val(),
				data: function(d) {
					d.followup_start = $('#followup_start').val();
                    d.followup_to = $('#followup_to').val();
					d.purchase_start = $('#purchase_start').val();
                    d.purchase_to = $('#purchase_to').val();
					d.type = $('#type').val();
					d.area = $('#area').val();
                    d.assigned_to = $('#assigned_to').val();
                    d.name = $('#contact_name').val();
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
                        return row.customer_account.customer_name;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return row.customer_account.customer_category.category;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
                        return row.customer_account.assigned_to.name;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
                        return row.customer_account.teritory.name;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.customer_account.points;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						var last_follow_up = '-';
						if(row.latest_follow_up!=null) last_follow_up = row.latest_follow_up;
                        return last_follow_up;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
                        return row.total_follow_up;
					}
				},
				{
					orderable: false,
					render: function(data, type, row, meta) {
						var latest_purchased = '-';
						if(row.latest_purchased!=null) latest_purchased = row.latest_purchased;
						return latest_purchased;
					}
				},
                {
					orderable: false,
					render: function(data, type, row, meta) {
						return row.customer_reward_status.status;
					}
				},
				{
					orderable: false,
					render: function(data, type, row, meta) {
						var content = '';
						if(row.customer_reward_status_id==1)
							content = `<a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#modal-approve" data-id="` + row.customer_reward_id + `">Approve</a>`;
						else if(row.customer_reward_status_id==2)
							content = `<a class="btn btn-danger btn-sm" href="{{url('/reward/delivery-item/` + row.customer_reward_id + `')}}">Item Delivered</a>`;
						return content;
					}
				},
				{
					orderable: false,
					render: function(data, type, row, meta) {
						var detail = '';
						if(my_menu.detail==true)
							detail = `<a class="dropdown-item" href="{{url('/reward/detail/` + row.sales_networking_id + `')}}">Detail</a>`;
						
						var followup = '';
						if(followup_menu.view==true)
							followup = `<a class="dropdown-item" href="{{url('/followup/reward/detail/` + row.sales_networking_id + `')}}">Follow up history</a>`;

                        var sales = '';
                        if(sales_menu.view==true)
                            sales = `<a class="dropdown-item" href="{{url('/sales-history/reward/detail/` + row.sales_networking_id + `')}}">Sales History</a>`;
						
                        var redeem = '';
                        if(redeem_menu.view==true)
                            redeem = `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-redeem-history" data-id="` + row.sales_networking_id + `">Redeem History</a>`;

						var content = `
							<ul class="navbar-nav ml-lg-auto">
								<li class="nav-item dropdown">
									<a class="text-gray" href="#" id="navbar-primary_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-primary_dropdown_1">` +
										detail +
										sales +
										followup +
										redeem +
									`</div>
								</li>
							</ul>
						`;
						return content;
					}
				}
            ],
            createdRow: function (row, data, rowIndex) {
                // Per-cell function to do whatever needed with cells
                $.each($('td', row), function (colIndex) {
                    if(colIndex=="0") {
                        $(this).attr('data-title', "Name");
					} else if(colIndex=="1") {
                        $(this).attr('data-title', "Type");
					} else if(colIndex=="2") {
                        $(this).attr('data-title', "Assigned To");
					} else if(colIndex=="3") {
                        $(this).attr('data-title', "Area");
					} else if(colIndex=="4") {
                        $(this).attr('data-title', "Points");
					} else if(colIndex=="5") {
                        $(this).attr('data-title', "Last Follow Up");
					} else if(colIndex=="6") {
                        $(this).attr('data-title', "Total Follow Up");
					} else if(colIndex=="7") {
                        $(this).attr('data-title', "Last Recorded Purchase");
					} else if(colIndex=="8") {
                        $(this).attr('data-title', "Status");
					} 
                });
            },
            error:setTimeout(1000),
			order: [0, 'asc'],
		});

		$('#followup_start').on('change', function(){
            customer_reward_table.ajax.reload();
        });

        $('#followup_to').on('change', function(){
            customer_reward_table.ajax.reload();
        });

		$('#purchase_start').on('change', function(){
            customer_reward_table.ajax.reload();
        });

        $('#purchase_to').on('change', function(){
            customer_reward_table.ajax.reload();
        });

		$('#type').on('change', function(){
            customer_reward_table.ajax.reload();
        });

		$('#area').on('change', function(){
            customer_reward_table.ajax.reload();
        });

        $('#assigned_to').on('change', function(){
            customer_reward_table.ajax.reload();
        });

        $('#contact_name').on('input', function(){
			customer_reward_table.ajax.reload();
		});
    });
</script>
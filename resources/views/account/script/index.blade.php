<script>
    $(document).ready(function() {
        var my_menu = JSON.parse($('#my_menu').val());
		var followup_menu = JSON.parse($('#followup_menu').val());
        var sales_menu = JSON.parse($('#sales_menu').val());
        var redeem_menu = JSON.parse($('#redeem_menu').val());

        var date = new Date();
        var start_date = date.setDate(date.getDate() - 7);
        $("#start_date").flatpickr({
                dateFormat: "d-m-Y",
                defaultDate: start_date,
                maxDate: new Date(),
            }
        );

        $("#end_date").flatpickr(
            {
                dateFormat: "d-m-Y",
                defaultDate: new Date(),
                maxDate: new Date(),
            }
        );


        $(".dob-date").flatpickr({
            dateFormat: "d-m-Y",
            maxDate: new Date(),
            onReady: function(selectedDates, dateStr, instance){
                if (instance.isMobile) {
                    $(instance.mobileInput).attr('step', null);
                }
            },
            wrap: true,
            disableMobile: "true"
        });

        var account_table = $('#account_table').DataTable( {
			processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: {
                type: "GET",
				url: APP_URL + '/account/list',
				data: function(d) {
					d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.dob_from = $('#dob_from').val();
                    d.dob_to = $('#dob_to').val();
                    d.keyword = $('#search').val();
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
                        return row.customer_name;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
                        var phone = '-';
                        if(row.contact_one!=null)
                        {
                            if(row.contact_one.contact_phone_one!= null)
                                phone = row.contact_one.contact_phone_one.phone
                        }
						return phone;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
                        var customer_category='-';
                        if(row.customer_category!=null)
                        {
                            customer_category = row.customer_category.category
                        }

						return customer_category;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
                        var teritory = '-';
                        if(row.teritory!=null)
                        {
                            teritory = row.teritory.name;
                        }
                        
						return teritory;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.detail_address;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
                        var province = '-';
                        if(row.village!=null)
                        {
                            province = row.village.subdistrict.city.province.name;
                        }
						return province;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
                        var city = '-';
                        if(row.village!=null)
                        {
                            city = row.village.subdistrict.city.name;
                        }
						return city;
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
                        var content='-';
                        if(row.customer_dob!=null)
                        {
                            var str = row.customer_dob;
                            var dob = str.split('-');
                            var content = dob[2] + '-' + dob[1] + '-' + dob[0];
                        }
                        
						return content;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return row.points;
					}
				},
                {
					orderable: false,
					render: function(data, type, row, meta) {
                        var detail = '';
						if(my_menu.detail==true)
							detail = `<a class="dropdown-item" href="{{url('/account/detail/` + row.sales_networking_id + `')}}">Detail</a>`;
						
						var edit = '';
						if(my_menu.edit==true)
							edit = `<a class="dropdown-item" href="{{url('/account/edit/` + row.sales_networking_id + `')}}">Edit</a>`;
						
						var followup = '';
						if(followup_menu.view==true)
							followup = `<a class="dropdown-item" href="{{url('/followup/account/detail/` + row.sales_networking_id + `')}}">Follow Up History</a>`;

                        var sales = '';
                        if(sales_menu.view==true)
                            sales = `<a class="dropdown-item" href="{{url('/sales-history/account/detail/` + row.sales_networking_id + `')}}">Sales History</a>`;
						
                        var redeem = '';
                        if(redeem_menu.view==true)
                            redeem = `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-redeem-history" data-id="` + row.sales_networking_id + `">Redeem History</a>`;
                            
                        var content = `
							<ul class="navbar-nav ml-lg-auto">
								<li class="nav-item dropdown">
									<a class="text-gray" href="#" id="navbar-primary_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-primary_dropdown_1">` +
                                        detail +
                                        edit +
                                        sales +
                                        followup +
                                        redeem +
									`</div>
								</li>
							</ul>
						`;
						return content;
					}
				},
            ],
            createdRow: function (row, data, rowIndex) {
                // Per-cell function to do whatever needed with cells
                $.each($('td', row), function (colIndex) {
                    if(colIndex=="0") {
                        $(this).attr('data-title', "Customer Name");
					} else if(colIndex=="1") {
                        $(this).attr('data-title', "Phone");
					} else if(colIndex=="2") {
                        $(this).attr('data-title', "Type");
					} else if(colIndex=="3") {
                        $(this).attr('data-title', "Area");
					} else if(colIndex=="4") {
                        $(this).attr('data-title', "Address");
					} else if(colIndex=="5") {
                        $(this).attr('data-title', "Province");
					} else if(colIndex=="6") {
                        $(this).attr('data-title', "City");
					} else if(colIndex=="7") {
                        $(this).attr('data-title', "Account Created");
					} else if(colIndex=="8") {
                        $(this).attr('data-title', "Date of Birth");
					} else if(colIndex=="9") {
                        $(this).attr('data-title', "Points");
					} 
                });
            },
            error:setTimeout(1000),
			order: [0, 'asc'],
		});

        $('#start_date').on('change', function(){
            account_table.ajax.reload();
        });

        $('#end_date').on('change', function(){
            account_table.ajax.reload();
        });

        $('#dob_from').on('change', function(){
            account_table.ajax.reload();
        });

        $('#dob_to').on('change', function(){
            account_table.ajax.reload();
        });

        $('#search').on('input', function(){
			account_table.ajax.reload();
		});
    });
</script>

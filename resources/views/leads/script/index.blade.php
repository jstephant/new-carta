<script>
    $(document).ready(function() {
		var my_menu = JSON.parse($('#my_menu').val());
		var followup_menu = JSON.parse($('#followup_menu').val());
		$("#div_import").hide();
		$("#btn_import").on('click', function(){
			if($("#div_import").is(":visible")) {
				$("#div_import").hide();
			} else {
				$("#div_import").show();
			}
		});

		$('#download_template').on('click', function(){
			$.ajax({
				url: APP_URL + '/leads/download/template-import-data-leads.xls',
				type: 'GET',
				success: function(response){
					console.log(response);
				}
			})
		});

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

        $("#assigned_to").select2();
		$('#status').select2({
			minimumResultsForSearch: -1,
		});

        var leadstable = $('#leads_table').DataTable( {
			processing: true,
            serverSide: true,
            pageLength: 10,
			scrollY: true,
			scrollX: true,
			scrollCollapse: true,
			fixedColumns: {
				leftColumns: 2
			},
			buttons: [
				'excelHtml5'
			],
            ajax: {
                type: "GET",
				url: APP_URL + '/leads/list',
				data: function(d) {
					d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.assigned_to = $('#assigned_to').val();
                    d.status = $('#status').val();
                    d.contact_name = $('#contact_name').val();
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
						return row.contact_phone;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.duplicate==1) ? 'Yes' : 'No';
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.contact_email) ? row.contact_email : '-'
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.detail_address) ? row.detail_address : '-'
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.occupation_name) ? row.occupation_name : '-'
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return row.notes
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.avg_hpl_usage) ? row.avg_hpl_usage : '-'
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						var content = '';
                        if(row.assigned_to!=null)
                        {
							content += row.assigned_to_name;
							if(row.assigned_at) content += '<br> on ' + row.assigned_at
							if(row.assigned_by_name) content += '<br> by ' + row.assigned_by_name
						} else {
							content = '-'
						}
						return content
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.brand_name) ? row.brand_name : '-'
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
                        if(row.status_name=='New')
                        {
                            var badge_class = 'badge-default';
                        } else if(row.status_name=='Failed')
                        {
                            var badge_class = 'badge-danger';
                        } else if(row.status_name=='Contacted' || row.status_name=='Visited')
                        {
                            var badge_class = 'badge-warning';
                        } else if(row.status_name=='Converted to Account')
                        {
                            var badge_class = 'badge-success';
                        }
                        var content = `<span class="badge ` + badge_class + `">` + row.status_name + `</span>`;
						return content;
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
						return getDate(row.created_at);
					}
				},
				{
					orderable: false,
					render: function(data, type, row, meta) {
						return (row.first_followup) ? row.first_followup : '-'
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.last_followup) ? row.last_followup : '-'
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.total_followup) ? row.total_followup : '0'
					}
				},
                {
					orderable: false,
					render: function(data, type, row, meta) {
						var link_active='';
						if(row.is_converted_account==1)
							link_active = 'href="#" data-toggle="modal" data-target="#modal-view-account"';

						var detail = '';
						if(my_menu.detail==true)
							detail = `<a class="dropdown-item" href="{{url('/leads/detail/` + row.id + `')}}">Detail</a>`;
						
						var edit = '';
						if(my_menu.edit==true)
							edit = `<a class="dropdown-item" href="{{url('/leads/edit/` + row.id + `')}}">Edit</a>`;
						
						var deleted = '';
						if(my_menu.delete==true)
							deleted = `<a class="dropdown-item" href="{{url('/leads/delete/` + row.id + `')}}">Delete</a>`;
						
						var followup = '';
						if(followup_menu.view==true)
							followup = `<a class="dropdown-item" href="{{url('/followup/leads/detail/` + row.id + `')}}">Follow up history</a>`;

						var content = `
							<ul class="navbar-nav ml-lg-auto">
								<li class="nav-item dropdown">
									<a class="text-gray" href="#" id="navbar-primary_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-primary_dropdown_1">` +
										detail +
										edit +
										deleted +
                                        followup +
										`<a class="dropdown-item" ` + link_active +` data-lead-id=` + row.id + `>View Account</a>
									</div>
								</li>
							</ul>
						`;
						return content;
					}
				},
            ],
            error:setTimeout(1000),
			order: [0, 'asc'],
		});

        $('#start_date, #end_date, #assigned_to, #status').on('change', function(){
            leadstable.ajax.reload();
        });

        $('#contact_name').on('input', function(){
			leadstable.ajax.reload();
		});

		$('#data-loader').hide();
		$('#modal-view-account').on('show.bs.modal', function(e) {
			var lead_id = $(e.relatedTarget).data('lead-id');
			$('#data-loader').show();
			$('#data_account').hide();
			$('#btn_close').hide();
			$.ajax({
				url: APP_URL + '/leads/account/' + lead_id,
				type: 'GET',
				success: function(response){
					$('#customer_name').text(response['customer_name']);

					var phone = '-';
					var email = '-';
					if(response['contact_one']!=null)
					{
						if(response['contact_one']['contact_phone_one']!=null)
						{
							phone = response['contact_one']['contact_phone_one']['phone'];
						}

						if(response['contact_one']['contact_email_one']!=null)
						{
							email = response['contact_one']['contact_email_one']['email'];
						}
					}
					$('#phone').text(phone);
					$('#email').text(email);
					$('#address').text(response['detail_address']);

					var province = '-';
					var city = '-';
					if(response['village']!=null)
					{
						city = response['village']['subdistrict']['city']['name'];
						province = response['village']['subdistrict']['city']['province']['name'];
					}
					$('#province').text(province);
					$('#city').text(city);
					$('#type').text(response['customer_category']['category']);

					var customer_dob = '-';
					if(response['customer_dob']!=null) {
						var str = response['customer_dob'];
						var explode = str.split('-');
						
						customer_dob = explode[2] + '-' + explode[1] + '-' + explode[0];
					}
					$('#dob').text(customer_dob);
				},
				complete: function() {
					$('#data-loader').hide();
					$('#data_account').show();
					$('#btn_close').show();
				}
			});
		});

		$('#modal-upload').on('show.bs.modal', function(e) {
			var data_type = $(e.relatedTarget).data('type')
			$('#data_type').val(data_type)

			var file_type = '.xls, .xlsx';
			if(data_type=='import-lead') $('#title').text('Import Data Lead')
			$('#attachment').attr("accept", file_type)
		});
    });

	function downloadData(data_type) {
		$.ajax({
			type: 'GET',
			url: "{{ url('/leads/download') }}/" + data_type,
			dataType: 'json',
			success: function (response) {
				if(data_type=='all-data-leads')
				{
					if(response.status.content.status)
					{
						alert('Data has been downloaded successfully.')
					}
				}
			}
		})
	}

	function downloadDataFilter()
	{
		$.ajax({
			type: 'GET',
			url: "{{ url('/leads/download-data-filter') }}",
			data: {
				start_date: $('#start_date').val(),
				end_date: $('#end_date').val(),
				assigned_to: $('#assigned_to').val(),
				status: $('#status').val(),
				contact_name: $('#contact_name').val()
			},
			dataType: 'json',
			success: function (response) {
				alert(response.message)
			}
		})
	}
</script>

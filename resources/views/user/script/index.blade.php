<script>
    $(document).ready(function() {
		var my_menu = JSON.parse($('#my_menu').val());
	
        var usertable = $('#user_table').DataTable({
			processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: {
                type: "GET",
				url: APP_URL + '/data-user/list',
				data: function(d) {
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
						return pad(row.nik, 9)
                    }
                },
                {
					orderable: true,
					render: function(data, type, row, meta) {
                        return row.name;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
                        return (row.email) ? row.email : '-'
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.role) ? row.role.name : '-'
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.sales_org) ? row.sales_org : '-'
					}
				},
                {
					orderable: true,
					render: function(data, type, row, meta) {
                        return (row.nik_atasan) ? row.nik_atasan : '-'
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
                        return (row.nik_atasan2) ? row.nik_atasan2 : '-'
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						return (row.user_teritory) ? row.user_teritory : '-'
					}
				},
				
                {
					orderable: false,
					render: function(data, type, row, meta) {
                        var badge_class = (row.st==1) ? 'badge-success' : 'badge-danger'
						var status = (row.st==1) ? 'Active' : 'Inactive' 
                        var content = `<span class="badge ` + badge_class + `">` + status + `</span>`
                        return content
					}
				},
                {
					orderable: false,
					render: function(data, type, row, meta) {
						var edit = '';
						if(my_menu.edit==true)
							edit = `<a class="dropdown-item" href="{{url('/data-user/edit/` + pad(row.nik, 9) + `')}}">Edit</a>`;
						
						// var deleted = '';
						// if(my_menu.delete==true)
						// {
						// 	deleted = `<a class="dropdown-item text-danger delete" href="#"  
						// 					onclick="deleteConfirmation(` + row.id+ `)">Delete
						// 				</a>`;
						// }

						var content = `
							<ul class="navbar-nav ml-lg-auto">
								<li class="nav-item dropdown">
									<a class="text-gray" href="#" id="navbar-primary_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-primary_dropdown_1">` +
										edit +
										`
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

        $('#search').on('input', function(){
			usertable.ajax.reload();
		});
    });

	function pad (str, max) {
		str = str.toString();
		return str.length < max ? pad("0" + str, max) : str;
	}
</script>

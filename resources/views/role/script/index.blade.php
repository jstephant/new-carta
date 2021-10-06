<script>
	$(document).ready(function () {
		var permission_table = $('#permission_table').DataTable({
			processing: true,
			serverSide: true,
			pageLength: 10,
			ajax: {
				type: "GET",
				url: APP_URL + '/role/list/permission',
				data: function(d) {
					d.id = $('#role_id').val();
					d.length = -1;
				}
			},
			dom:
				"<'row'<'col-sm-12 col-md-6'>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row py-3'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'>>",
			lengthChange: true,
			language: {
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
						var content = ''
						if(row.parent_id > 0)
						{
							content = `<span class="ml-4">` + row.name + `</span>`
						} else {
							content = `<span>` + row.name + `</span>`
						}
						return content;
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var content = row.detail_is_view==true ? `<span class="text-success"><i class="far fa-check-circle"></i></span>` : `<span class="text-danger"><i class="far fa-times-circle"></i></span>`
						return (row.is_view==true) ? content : ''
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var content = row.detail_is_create==true ? `<span class="text-success"><i class="far fa-check-circle"></i></span>` : `<span class="text-danger"><i class="far fa-times-circle"></i></span>`
						return (row.is_create==true) ? content : ''
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var content = row.detail_is_edit==true ? `<span class="text-success"><i class="far fa-check-circle"></i></span>` : `<span class="text-danger"><i class="far fa-times-circle"></i></span>`
						return (row.is_edit==true) ? content : ''
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var content = row.detail_is_delete==true ? `<span class="text-success"><i class="far fa-check-circle"></i></span>` : `<span class="text-danger"><i class="far fa-times-circle"></i></span>`
						return (row.is_delete==true) ? content : ''
					}
				},
				{
					orderable: true,
					render: function(data, type, row, meta) {
						var content = row.detail_is_approval==true ? `<span class="text-success"><i class="far fa-check-circle"></i></span>` : `<span class="text-danger"><i class="far fa-times-circle"></i></span>`
						return (row.is_approval==true) ? content : ''
					}
				}
			],
			error:setTimeout(1000),
			order: [0, 'asc'],
		});

		$('.detail-role-id').on('click', function () {
			var role_id = $(this).attr('data-id')
			$('#role_id').val(role_id);
			permission_table.ajax.reload();
		});
	});

    function goToEditLink(id)
    {
        var link = "{{ url('/role/edit') }}/" + id
        window.location.href = link
    }

    function deleteConfirmation(id) {
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-danger',
				cancelButton: 'btn btn-link'
			},
			buttonsStyling: false,
		})

		swalWithBootstrapButtons.fire({
			text: "Are you sure want to delete this role?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			reverseButtons: true,
			icon: 'info'
		}).then((result) => {
			if (result.value) {
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{url('/role/delete')}}/" + id,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.status === true) {
                            Swal.fire("Success!", results.message, "success");
							window.location.reload();
                        } else {
                            Swal.fire("Error!", results.message, "error");
                        }
                    }
                });
			} else {
				result.dismiss;
			}
		})
    }
</script>

<script>
    $(document).ready(function() {
        $('#data_loader').hide();
		$('#modal-approve').on('show.bs.modal', function(e) {
			var id = $(e.relatedTarget).data('id');
			$('#customer_reward_id').val(id);

			$('#data_loader').show();
			$('#data_header').hide();
			$('#data_item').hide();
			$('#btn_approve').hide();
			$('#btn_reject').hide();
			$('#btn_cancel').hide();
			$.ajax({
				url: APP_URL + '/reward/find/' + id,
				type: 'GET',
				success: function(response){
					$('#data_item_div').remove();
					
					var requested = getDate(response['request_date']);
					$('#requested').text(requested);

					var delivered = getDate(response['delivery_date']);
					$('#delivered').text(delivered);

					var list_item = '';
					var all_point = 0;
					$.each(response['customer_reward_item'], function(index, val) {
							var total_point = val['quantity'] * val['points_per_qty'];
							list_item += `<li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                            <span class="text-sm text-muted mb-0">` + val['reward_item']['description'] + ` x ` + val['quantity'] +`</span> 
                                            <span class="badge badge-primary badge-pill">` + total_point.toString() + ` pts</span>
                                        </li>`;
							all_point += total_point;
					});
					$('#redeemed').text(all_point + ' pts');
					var content = '';
					content += `<div id="data_item_div">`;
					content += `<ul class="list-group list-group-flush">` + list_item + `</ul>`;
					content += `</div>`;
					$('#data_item').append(content);
				},
				complete: function(){
					$('#data_loader').hide();
					$('#data_header').show();
					$('#data_item').show();
					$('#btn_approve').show();
					$('#btn_reject').show();
					$('#btn_cancel').show();
				}
			});
		});
    });
</script>
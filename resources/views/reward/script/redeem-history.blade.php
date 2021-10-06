<script>
    $(document).ready(function() {
        $('#data-loader').hide();
		$('#modal-redeem-history').on('show.bs.modal', function(e) {
			var id = $(e.relatedTarget).data('id');
			$('#data-loader').show();
			$('#data_history').hide();
			$('#btn_close').hide();
			$.ajax({
				url: APP_URL + $('#url_redeem_history').val() + id,
				type: 'GET',
				success: function(response){
					$('#data_history_div').remove();
					var content = '';
					content += `<div id="data_history_div" class="row">`;
					$.each(response, function(index, val) {
						var requested = getDate(val['request_date']);
						var delivered = getDate(val['delivery_date']);

						var list_item = '';
						$.each(val['customer_reward_item'], function(index2, val2) {
							var total_point = val2['quantity'] * val2['points_per_qty'];
							list_item += `<li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                            <span class="text-sm text-muted mb-0">` + val2['reward_item']['description'] + ` x ` + val2['quantity'] +`</span> 
                                            <span class="badge badge-primary badge-pill">` + total_point.toString() + ` pts</span>
                                        </li>`;
						});

						content += `
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="card shadow">
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <table class="table table-borderless" id="redeem_history_table" width="100%">
                                                    <tr>
														<td width="25%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted">Requested</span></td>
														<td width="1px" style="padding:1px; !important;">:</td>
                        								<td width="100%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted">` + requested + `</span></td>
                                                    </tr>
													<tr>
                                                        <td width="25%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted">Delivered</span></td>
                                                        <td width="1px" style="padding:1px; !important;">:</td>
                                                        <td width="100%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted">` + delivered + `</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="25%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted">Redeemed</span></td>
                                                        <td width="1px" style="padding:1px; !important;">:</td>
                                                        <td width="100%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted">` + val['total_point']+ ` pts</span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <ul class="list-group list-group-flush">` + list_item + `</ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>`;
					});
					content += `</div>`;
					$('#data_history').append(content);
				},
				complete: function(){
					$('#data-loader').hide();
					$('#data_history').show();
					$('#btn_close').show();
				}
			});
		});
    });
</script>
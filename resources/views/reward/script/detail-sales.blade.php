<script>
    $(document).ready(function() {
        $('#data-loader').hide();
		$('#modal-detail-sales-approval').on('show.bs.modal', function(e) {
			var followup_id = $(e.relatedTarget).data('followup-id');
			var status = $(e.relatedTarget).data('status');
			$('#sales_id').val(followup_id);

			$('#data-loader').show();
			$('#data_header').hide();
			$('#data_detail').hide();
			$('#btn_close').hide();
			$('#btn_approve').hide();
			$.ajax({
				url: APP_URL + $('#url_sales_detail').val() + followup_id,
				type: 'GET',
				success: function(response){
					var purchased_on = getDate(response['order_date']);
					$('#purchased_on').text(purchased_on);
					
					var entered_on = getDate(response['created_at']);
					$('#entered_on').text(entered_on);
					
					$('#store').text(response['collect']);
					$("#img_receipt").attr("src", response['order_receipt']);

					$('#detail_sales_table tbody').html("");
					var tr = "";
					$.each(response['product_carta'], function(index, val){
						var no = index+1;
						tr += `<tr>`;
						tr += `<td>` + no +  `</td>`;
						tr += `<td>` + val['master_product']['product_code'] + `</td>`;
						tr += `<td>` + val['master_product']['product_alias'] + `</td>`;
						tr += `<td>` + val['master_product']['sales_material_group3_desc'] + `</td>`;
						tr += `<td>` + val['quantity'] + `</td>`;
						tr += `<td>` + val['uom'] + `</td>`;
						tr += `<td>` + val['points'] + `</td>`;
					});
					$('#detail_sales_table > tbody:last-child').append(tr);
				},
				complete: function(){
					$('#data-loader').hide();
					$('#data_header').show();
					$('#data_detail').show();
					$('#btn_close').show();
					if(status==1) $('#btn_approve').show();
				}
			});
		});
    });
</script>
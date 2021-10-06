<div class="modal fade" id="modal-approve" tabindex="-1" role="dialog" aria-labelledby="modal-approve" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
		<div class="modal-content">
			<form class="form-basic needs-validation" novalidate method="post" role="form" action="{{ route('customer-reward.approval')}}" enctype="multipart/form-data" id="form-create-lead">
				{{ csrf_field() }}
				<div class="modal-header justify-content-center bg-danger">
					<h5 class="modal-title text-white text-uppercase">Confirmation</h5>
				</div>
				<div class="modal-body justify-content-center">
					<div id="data_loader" class="row">
						<div class="col-md-12">
							<div class="justify-content-center text-center">
								<div class="spinner-border text-primary " role="status" ></div>
							</div>
						</div>
					</div>
					
					<div class="row" id="data_header">
						<div class="col-12">
							<table class="table table-borderless" id="redeem_history_table" width="100%">
								<tr>
									<td width="25%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted">Requested</span></td>
									<td width="1px" style="padding:1px; !important;">:</td>
									<td width="100%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted" id="requested"></span></td>
								</tr>
								<tr>
									<td width="25%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted">Delivered</span></td>
									<td width="1px" style="padding:1px; !important;">:</td>
									<td width="100%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted" id="delivered"></span></td>
								</tr>
								<tr>
									<td width="25%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted">Redeemed</span></td>
									<td width="1px" style="padding:1px; !important;">:</td>
									<td width="100%" style="padding:1px; !important;"><span class="mb-0 text-sm text-muted" id="redeemed"></span></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-12" id="data_item"></div>
					</div>
				</div>
				<div class="modal-footer justify-content-center">
					<input type="hidden" id="customer_reward_id" name="customer_reward_id">
					<button type="button" class="btn btn-link" data-dismiss="modal" id="btn_cancel">Cancel</button>
					<button type="submit" class="btn btn-danger" id="btn_reject" name="action" value="reject">Reject</button>
					<button type="submit" class="btn btn-success" id="btn_approve" name="action" value="approve">Approve</button>
				</div>
			</form>
		</div>
	</div>
</div>
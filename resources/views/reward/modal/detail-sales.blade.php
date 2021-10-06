<div class="modal fade" id="modal-detail-sales-approval" tabindex="-1" role="dialog" aria-labelledby="modal-detail-sales" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<form class="form-basic needs-validation" novalidate method="post" role="form" action="{{ route('sales-approval.post')}}" enctype="multipart/form-data" id="form-create-lead">
				{{ csrf_field() }}
				<div class="modal-header justify-content-center bg-danger">
					<h5 class="modal-title text-white text-uppercase">Sales Detail</h5>
				</div>
				<div class="modal-body justify-content-center">
					<div id="data-loader" class="row">
						<div class="col-md-12">
							<div class="justify-content-center text-center">
								<div class="spinner-border text-primary " role="status" ></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div id="data_header" class="col-12">
							<div class="card-body card-body pt-0 pl-2 pb-0">
								<div class="row">
									<div class="d-flex align-items-center">
										<table class="table table-borderless" width="100%">
											<tr>
												<td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Purchased On</span></td>
												<td width="1px" style="padding:8px; !important;">:</td>
												<td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm" id="purchased_on"></span></td>
											</tr>
											<tr>
												<td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Entered On</span></td>
												<td width="1px" style="padding:8px; !important;">:</td>
												<td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm" id="entered_on"></span></td>
											</tr>
											<tr>
												<td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Store</span></td>
												<td width="1px" style="padding:8px; !important;">:</td>
												<td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm" id="store"></span></td>
											</tr>
										</table>
									</div>
									<div class="text-right ml-auto">
										<img id="img_receipt" class="card-image" alt="" width="150" height="150">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12" id="data_detail">
							<div class="table-responsive ml-0 mr-0 mt-3">
								<table class="table align-items-center table-flush dataTable" id="detail_sales_table" width="100%">
									<thead class="thead-light">
										<tr>
											<th scope="col">No</th>
											<th scope="col">Code</th>
											<th scope="col">Description</th>
											<th scope="col">Model</th>
											<th scope="col">Qty</th>
											<th scope="col">UoM</th>
											<th scope="col">Points</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-center">
					<input type="hidden" id="sales_id" name="sales_id">
					<button type="button" id="btn_close" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-success" id="btn_approve" name="action" value="approve">Approve</button>
				</div>
			</form>
		</div>
	</div>
</div>
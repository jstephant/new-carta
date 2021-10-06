@extends('layouts.app')

@section('content')
	<div class="header pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7 text-left">
						<a href="javascript:history.back()" class="btn btn-sm btn-danger" >Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid -fluid mt--6">
		<div class="row">
			<div class="col">
				<div class="card shadow">
					<div class="card-header">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-xs-6">
								<table class="table table-borderless" id="detail_lead1" width="100%">
									<tr>
										<td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Name</span></td>
										<td width="1px" style="padding:8px; !important;">:</td>
										<td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $header->customer_name }}</span></td>
									</tr>
									<tr>
										<td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Email</span></td>
										<td width="1px" style="padding:8px; !important;">:</td>
										<td width="100%" style="padding:8px; !important;">
											<span class="name mb-0 text-sm">
												@if ($access_from=='leads')
													{{ $header->contact_email }}
												@elseif ($access_from=='account' || $access_from=='reward')
													@if ($header->contact_one && $header->contact_one->contact_email_one)
														{{ $header->contact_one->contact_email_one->email }}	
													@else
														{{ '-' }}
													@endif
												@endif
											</span>
										</td>
									</tr>
									<tr>
										<td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Phone</span></td>
										<td width="1px" style="padding:8px; !important;">:</td>
										<td width="100%" style="padding:8px; !important;">
											<span class="name mb-0 text-sm">
												@if ($access_from=='leads')
													{{ $header->contact_phone }}
												@elseif ($access_from=='account' || $access_from=='reward')
													@if($header->contact_one && $header->contact_one->contact_phone_one)
														{{ $header->contact_one->contact_phone_one->phone }}
													@else
														{{ '-' }}
													@endif
												@endif
											</span>
										</td>
									</tr>
									<tr>
										<td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Address</span></td>
										<td width="1px" style="padding:8px; !important;">:</td>
										<td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $header->detail_address }}</span></td>
									</tr>
								</table>
							</div>
							<div class="col-lg-6 col-md-6 col-xs-6">
								<table class="table table-borderless" id="detail_lead2" width="100%">
									<tr>
										<td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Assigned To</span></td>
										<td width="1px" style="padding:8px; !important;">:</td>
										<td width="100%" style="padding:8px; !important;">
											<span class="name mb-0 text-sm">
												@if ($access_from=='leads')
													{{ $header->assigned_to_name }}	
												@elseif ($access_from=='account' || $access_from=='reward')
													{{ $header->assigned_to->name }}
												@endif
											</span>
										</td>
									</tr>
									<tr>
										<td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Lead Status</span></td>
										<td width="1px" style="padding:8px; !important;">:</td>
										<td width="100%" style="padding:8px; !important;">
											<span class="name mb-0 text-sm">
												@if ($access_from=='leads')
													{{ $header->status_name }}
												@elseif ($access_from=='account' || $access_from=='reward')
													{{ ($header->leads) ? $header->leads->status->name : '-'}}
												@endif
											</span>
										</td>
									</tr>
									<tr>
										<td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Account Conversion Date</span></td>
										<td width="1px" style="padding:8px; !important;">:</td>
										<td width="100%" style="padding:8px; !important;">
											<span class="name mb-0 text-sm">
												@if ($access_from=='leads')
													{{ ($header->account_conversion_date) ? date('d-m-Y', strtotime($header->account_conversion_date)) : '' }}
												@elseif ($access_from=='account' || $access_from=='reward')
													{{ ($header->created_at) ? date('d-m-Y', strtotime($header->created_at)) : ''}}
												@endif
											</span>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>

					<div class="card-body">
						<div class="row">
                            <div class="col-lg-3 col-md-6">
								<label for="start_date" class="col-form-label-sm text-uppercase display-4">From</label>
								<input type="text" class="form-control flatpickr flatpickr-input" id="start_date" name="start_date" value="">
                            </div>
                            <div class="col-lg-3 col-md-6">
								<label for="end_date" class="col-form-label-sm text-uppercase display-4">To</label>
								<input type="text" class="form-control flatpickr flatpickr-input" id="end_date" name="end_date" value="">
                            </div>
						</div>
						<div class="row">
							<div class="col-12">
								<input type="hidden" id="header_id" name="header_id" value="{{ ($access_from=='leads') ? $header->id : $header->sales_networking_id }}">
								<input type="hidden" id="access_from" name="access_from" value="{{ $access_from }}">
								<input type="hidden" id="url_data" name="url_data" value="{{ $url_data }}">
								<input type="hidden" id="url_sales_detail" name="url_sales_detail" value="{{ $url_sales_detail }}">
								<div class="table-responsive ml-0 mr-0 mt-3">
									<table class="table align-items-center table-flush dataTable" id="followup_table" width="100%">
										<thead class="thead-light">
											<tr>
												<th scope="col">No</th>
												<th scope="col">Follow Up Date</th>
												<th scope="col">Type</th>
												<th scope="col">Lead Status</th>
												<th scope="col">Client Feedback</th>
												<th scope="col">HPL (sheets)</th>
												<th scope="col">Edging (m)</th>
												<th scope="col">Images</th>
												<th scope="col">Follow Up Type</th>
												<th scope="col">Status</th>
												<th scope="col">Created On</th>
												<th scope="col"></th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			<div>
		</div>
	</div>
	@include('follow-up.script.index')
	@include('image-preview.modal.image-preview')
	@include('image-preview.script.image-preview')
	@include('sales.detail.modal.detail-sales')
	@include('sales.detail.script.detail-sales')
	@include('global')
@endsection

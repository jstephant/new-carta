@extends('layouts.app')

@section('content')
	<div class="header pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
							<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
								<li class="breadcrumb-item"><a href="{{url('/home')}}"><i class="fas fa-home text-gray-dark"></i></a></li>
								<li class="breadcrumb-item active"><a href="{{url('/reward')}}" class="text-gray-dark">Customer Reward</a></li>
								<li class="breadcrumb-item active"><a href="#" class="text-gray-dark">Sales Approval</a></li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid -fluid mt--6">
		<div class="row">
			<div class="col">
				<div class="card shadow">
					<div class="card-body">
						<div class="row">
                            <div class="col-lg-2 col-md-6">
								<label for="start_date" class="col-form-label-sm text-uppercase display-4">Purchase Date</label>
								<input type="text" class="form-control flatpickr flatpickr-input" id="start_date" name="start_date" value="">
                            </div>
                            <div class="col-lg-2 col-md-6">
								<label for="end_date" class="col-form-label-sm text-uppercase display-4">To</label>
								<input type="text" class="form-control flatpickr flatpickr-input" id="end_date" name="end_date" value="">
                            </div>
							<div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="status" class="col-form-label-sm text-uppercase display-4">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value=""></option>
										@foreach ($status as $item)
											<option value="{{ $item->sales_approval_status_id }}" {{ ($item->sales_approval_status_id==1) ? 'selected' : '' }} >{{ $item->status }}</option>
										@endforeach
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4" col-md-12>
                                <div class="form-group">
                                    <label for="contact_name" class="col-form-label-sm text-uppercase display-4">Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="name" name="name" value="">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="row">
							<input type="hidden" id="my_menu" value="{{ $my_menu }}">
							<input type="hidden" id="access_from" name="access_from" value="{{ $access_from }}">
							<input type="hidden" id="url_data" name="url_data" value="{{ $url_data }}">
							<input type="hidden" id="url_sales_detail" name="url_sales_detail" value="{{ $url_sales_detail }}">
							<div class="col-12">
								<div class="table-responsive ml-0 mr-0 mt-3">
									<table class="table align-items-center table-flush dataTable" id="sales_approval_table" width="100%">
										<thead class="thead-light">
											<tr>
												<th scope="col">No</th>
												<th scope="col">Name</th>
												<th scope="col">Type</th>
												<th scope="col">Assigned To</th>
												<th scope="col">Area</th>
												<th scope="col">Date of Purchase</th>
												<th scope="col">HPL (sheets)</th>
												<th scope="col">Edging (m)</th>
												<th scope="col">Points</th>
												<th scope="col">Store</th>
												<th scope="col">Status</th>
												<th scope="col">Approved On</th>
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
	@include('reward.script.sales-approval')
	@include('reward.modal.detail-sales')
	@include('reward.script.detail-sales')
	@include('global')
@endsection

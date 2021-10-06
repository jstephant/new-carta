@extends('layouts.app')

@section('content')
	<div class="header pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
							<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
								<li class="breadcrumb-item"><a href="/home" class="text-gray-dark"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item active"><a href="#" class="text-gray-dark">List of account</a></li>
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
						<div class="row align-items-center">
                            <div class="col-lg-2 col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="col-form-label-sm text-uppercase display-4">Created</label>
                                    <input type="text" class="form-control flatpickr flatpickr-input" id="start_date" name="start_date" value="">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="col-form-label-sm text-uppercase display-4">To</label>
                                    <input type="text" class="form-control flatpickr flatpickr-input" id="end_date" name="end_date" value="">
                                </div>
                            </div>
							<div class="col-lg-2 col-md-6">
                                <div class="form-group">
									<label for="dob_from" class="col-form-label-sm text-uppercase display-4">DOB</label>
									<div class="dob-date">
										<div class="input-group">
											<input type="text" id="dob_from" name="dob_from" class="form-control flatpickr flatpickr-input" value="" data-input>
											<div class="input-group-append">
												<span class="btn-sm btn-outline-danger input-group-text" data-clear><i class="fas fa-times"></i></span>
											</div>
										</div>
									</div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="form-group">
									<label for="dob_to" class="col-form-label-sm text-uppercase display-4">To</label>
									<div class="dob-date">
										<div class="input-group">
											<input type="text" id="dob_to" name="dob_to" class="form-control flatpickr flatpickr-input" value="" data-input>
											<div class="input-group-append">
												<span class="btn-sm btn-outline-danger input-group-text" data-clear><i class="fas fa-times"></i></span>
											</div>
										</div>
									</div>
                                </div>
                            </div>
                            <div class="col-lg-4" col-md-12>
                                <div class="form-group">
                                    <label for="search" class="col-form-label-sm text-uppercase display-4">Search</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search" name="search" value="">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="table-responsive ml-0 mr-0 mt-3">
									<input type="hidden" id="my_menu" value="{{ $my_menu }}">
									<input type="hidden" id="followup_menu" value="{{ $followup_menu }}">
									<input type="hidden" id="sales_menu" value="{{ $sales_menu }}">
									<input type="hidden" id="redeem_menu" value="{{ $redeem_menu }}">
									<input type="hidden" id="url_redeem_history" name="url_redeem_history" value="{{ $url_redeem_history }}">
									<table class="table align-items-center table-flush dataTable" id="account_table" width="100%">
										<thead class="thead-light">
											<tr>
												<th scope="col">No</th>
												<th scope="col">Name</th>
												<th scope="col">Phone</th>
												<th scope="col">Type</th>
												<th scope="col">Area</th>
												<th scope="col">Address</th>
												<th scope="col">Province</th>
												<th scope="col">City</th>
												<th scope="col">Account Created</th>
												<th scope="col">Date of Birth</th>
												<th scope="col">Points</th>
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
	@include('account.script.index')
	@include('reward.modal.redeem-history')
	@include('reward.script.redeem-history')
	@include('global')
@endsection

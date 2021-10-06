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
								<li class="breadcrumb-item active"><a href="#" class="text-gray-dark">List of reward</a></li>
							</ol>
						</nav>
					</div>
					
					{{-- <div class="col-lg-6 col-5 text-right">
						<a href="/reward/manage" class="btn btn-sm btn-danger">Manage Reward</a>
						<a href="/reward/manage/item" class="btn btn-sm btn-danger">Manage Reward Item</a>
					</div> --}}
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
							<div class="col-lg-6 col-md-6 col-xs-12">
								<div class="row">
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label for="followup_start" class="col-form-label-sm text-uppercase display-4">Last Follow Up</label>
											<input type="text" class="form-control flatpickr flatpickr-input" id="followup_start" name="followup_start" value="">
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label for="followup_to" class="col-form-label-sm text-uppercase display-4">To</label>
											<input type="text" class="form-control flatpickr flatpickr-input" id="followup_to" name="followup_to" value="">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label for="purchase_start" class="col-form-label-sm text-uppercase display-4">Last Purchase</label>
											<input type="text" class="form-control flatpickr flatpickr-input" id="purchase_start" name="purchase_start" value="">
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label for="purchase_to" class="col-form-label-sm text-uppercase display-4">To</label>
											<input type="text" class="form-control flatpickr flatpickr-input" id="purchase_to" name="purchase_to" value="">
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-xs-12">
								<div class="row">
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label for="type" class="col-form-label-sm text-uppercase display-4">Type</label>
											<select id="type" name="type" class="form-control">
												<option value=""></option>
												@foreach ($category as $item)
													<option value="{{ $item->customer_category_id }}">{{ $item->category }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label for="area" class="col-form-label-sm text-uppercase display-4">Area</label>
											<select id="area" name="area" class="form-control">
												<option value=""></option>
												@foreach ($teritory as $item)
													<option value="{{ $item->teritory_id }}">{{ $item->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label for="assigned_to" class="col-form-label-sm text-uppercase display-4">Assigned To</label>
											<select id="assigned_to" name="assigned_to" class="form-control" data-toggle="select">
												<option value=""></option>
												@foreach ($assigned_to as $item)
													<option value="{{ $item->nik }}">{{ $item->nik . ' | ' . $item->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label for="contact_name" class="col-form-label-sm text-uppercase display-4">Name</label>
											<div class="input-group">
												<input type="text" class="form-control" id="contact_name" name="contact_name" value="">
												<div class="input-group-append">
													<span class="input-group-text"><i class="fas fa-search"></i></span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					
						<div class="row">
							<input type="hidden" id="my_menu" value="{{ $my_menu }}">
							<input type="hidden" id="followup_menu" value="{{ $followup_menu }}">
							<input type="hidden" id="sales_menu" value="{{ $sales_menu }}">
							<input type="hidden" id="redeem_menu" value="{{ $redeem_menu }}">
							<input type="hidden" id="access_from" name="access_from" value="{{ $access_from }}">
							<input type="hidden" id="url_data" name="url_data" value="{{ $url_data }}">
							<input type="hidden" id="url_redeem_history" name="url_redeem_history" value="{{ $url_redeem_history }}">
							<div class="col-12">
								<div class="table-responsive">
									<table class="table align-items-center table-flush dataTable" id="customer_reward_table" width="100%">
										<thead class="thead-light">
											<tr>
												<th scope="col">No</th>
												<th scope="col">Name</th>
												<th scope="col">Type</th>
												<th scope="col">Assigned To</th>
												<th scope="col">Area</th>
												<th scope="col">Points</th>
												<th scope="col">Last Follow Up</th>
												<th scope="col">Total Follow Up</th>
												<th scope="col">Last Recorded Purchase</th>
												<th scope="col">Status</th>
												<th scope="col"></th>
												<th scope="col"></th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('reward.script.index')
	@include('reward.modal.redeem-history')
	@include('reward.script.redeem-history')
	@include('reward.modal.approve')
	@include('reward.script.approve')
	@include('global')
@endsection

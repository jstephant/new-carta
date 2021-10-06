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
								<li class="breadcrumb-item active"><a href="#" class="text-gray-dark">List of leads</a></li>
							</ol>
						</nav>
					</div>
					<div class="col-lg-6 col-5 text-right">
						<input type="hidden" id="my_menu" value="{{ $my_menu }}">
						<input type="hidden" id="followup_menu" value="{{ $followup_menu }}">
						@if($my_menu['create'])
							<a href="{{url('/leads/create')}}" class="btn btn-sm btn-danger">Create</a>
						@endif
						<div class="btn-group">
							<button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i></button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
								@if($my_menu['create'])
									<a href="#" class="dropdown-item" data-type='template' onclick="downloadData('template')" title="Download Template">
										<span class="btn-inner--icon text-success"><i class="fas fa-download mr-2"></i></span>
										<span class="btn-inner--text">Template</span>
									</a>
									<a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-upload" data-type="import-lead" title="Import Data Leads">
										<span class="btn-inner--icon text-default"><i class="fas fa-upload mr-2"></i></span>
										<span class="btn-inner--text">Import Lead</span>
									</a>
								@endif
								<a href="#" class="dropdown-item" data-type="all-data-leads" onclick="downloadData('all-data-leads')" title="Download All Data Leads">
									<span class="btn-inner--icon  text-success"><i class="fas fa-download mr-2"></i></span>
									<span class="btn-inner--text">All Data</span>
								</a>
								<a href="#" class="dropdown-item" onclick="downloadDataFilter()" title="Download Data Filtered">
									<span class="btn-inner--icon  text-success"><i class="fas fa-download mr-2"></i></span>
									<span class="btn-inner--text">Data Filter</span>
								</a>
							</div>
						</div>
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
                                    <label for="start_date" class="col-form-label-sm text-uppercase display-4">From</label>
                                    <input type="text" class="form-control flatpickr flatpickr-input" id="start_date" name="start_date" value="">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="col-form-label-sm text-uppercase display-4">To</label>
                                    <input type="text" class="form-control flatpickr flatpickr-input" id="end_date" name="end_date" value="">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label for="assigned_to" class="col-form-label-sm text-uppercase display-4">Assigned To</label>
                                    <select id="assigned_to" name="assigned_to" class="form-control" data-toggle="select">
										<option value=""></option>
										@foreach ($assigned_to as $item)
											<option value="{{ $item->nik }}">{{ $item->nik . ' | '. $item->name}}
										@endforeach
									</select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="form-group">
                                    <label for="status" class="col-form-label-sm text-uppercase display-4">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value=""></option>
                                        @foreach ($lead_status as $item)
											<option value="{{ $item->id }}">{{$item->name}}
										@endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3" col-md-12>
                                <div class="form-group">
                                    <label for="contact_name" class="col-form-label-sm text-uppercase display-4">Contact name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="contact_name" name="contact_name" value="">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					
						<div class="row">
							<div class="col-12">
								<div class="table-responsive">
									<table class="table align-items-center table-flush dataTable" id="leads_table" width="100%">
										<thead class="thead-light">
											<tr>
												<th scope="col">No</th>
												<th scope="col">Name</th>
												<th scope="col">Phone</th>
												<th scope="col">Duplicate</th>
												<th scope="col">Email</th>
												<th scope="col">Address</th>
												<th scope="col">Occupation</th>
												<th scope="col">Notes</th>
												<th scope="col">Average HPL<br>Usage/month</th>
												<th scope="col">Assigned to</th>
												<th scope="col">Brand</th>
												<th scope="col">Status</th>
												<th scope="col">Lead Submitted</th>
												<th scope="col">1st Follow Up</th>
												<th scope="col">Last Follow Up</th>
												<th scope="col">Total Follow Up</th>
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
	@include('leads.script.index')
	@include('leads.modal.view-account')
	@include('leads.modal.upload')
	@include('global')
@endsection

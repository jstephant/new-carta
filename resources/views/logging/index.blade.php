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
								<li class="breadcrumb-item active"><a href="#" class="text-gray-dark">Log Activity</a></li>
							</ol>
						</nav>
					</div>
					<div class="col-lg-6 col-5 text-right">
						<a href="#" class="btn btn-sm btn-danger" onclick="downloadData()" title="Download">
							<span class="btn-inner--icon text-white"><i class="fas fa-download mr-2"></i></span>
							<span class="btn-inner--text">Download</span>
						</a>
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
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="col-form-label-sm text-uppercase display-4">From</label>
                                    <input type="text" class="form-control flatpickr flatpickr-input" id="start_date" name="start_date" value="">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="col-form-label-sm text-uppercase display-4">To</label>
                                    <input type="text" class="form-control flatpickr flatpickr-input" id="end_date" name="end_date" value="">
                                </div>
                            </div>
						</div>
					
						<div class="row">
							<div class="col-12">
								<div class="table-responsive">
									<table class="table align-items-center table-flush dataTable" id="logging_table" width="100%">
										<thead class="thead-light">
											<tr>
												<th scope="col">NIK</th>
												<th scope="col">Name</th>
												<th scope="col">Description</th>
												<th scope="col">Source</th>
												<th scope="col">Created At</th>
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
	@include('logging.script.index')
	@include('global')
@endsection

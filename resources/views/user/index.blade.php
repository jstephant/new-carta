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
								<li class="breadcrumb-item active"><a href="#" class="text-gray-dark">Data User</a></li>
							</ol>
						</nav>
					</div>
					<div class="col-lg-6 col-5 text-right">
						@if($my_menu['create'])
							<a href="{{url('/data-user/create')}}" class="btn btn-sm btn-danger">Create</a>
						@endif
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
                            <div class="col-lg-3 col-md-12" >
                                <div class="form-group">
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
								<div class="table-responsive">
                                    <input type="hidden" id="my_menu" value="{{ $my_menu }}">
									<table class="table align-items-center table-flush dataTable" id="user_table" width="100%">
										<thead class="thead-light">
											<tr>
												<th scope="col">NIK</th>
												<th scope="col">Name</th>
												<th scope="col">Email</th>
												<th scope="col">Role</th>
												<th scope="col">Sales Org</th>
												<th scope="col">NIK Atasan</th>
												<th scope="col">NIK Atasan 2</th>
												<th scope="col">Teritory</th>
												<th scope="col">Status</th>
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
	@include('user.script.index')
	@include('global')
@endsection

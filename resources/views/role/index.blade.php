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
								<li class="breadcrumb-item active"><a href="#" class="text-gray-dark">List of Role</a></li>
							</ol>
						</nav>
					</div>
					<div class="col-lg-6 col-5 text-right">
						@if($my_menu['create'])
							<a href="{{url('/role/create')}}" class="btn btn-sm btn-danger">Create</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid -fluid mt--6">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-xs-12">
				<div class="card shadow">
					<div class="card-body">
						<div class="list-group" id="list-tab" role="tablist">
							<input type="hidden" id="role_id" value="{{ $roles[0]['role_id'] }}">
							@foreach ($roles as $key => $item)
								<a href="{{ '#list-'.$item->role_id }}" class="list-group-item list-group-item-action detail-role-id {{ ($key==0) ? 'active' : '' }}" id="{{ 'list-'. $item->role_id .'-list' }}" data-toggle="tab" data-id="{{ $item->role_id }}" role="tab" aria-controls="{{ strtolower($item->name) }}">
									<div class="row align-items-center">
										<div class="col ml-2">
											<span class="mb-0">
												{{ $item->name }}
											</span>
											<p class="mb-0">
												@if ($item->st==1)
													<span class="text-success">●</span>
													<small>Active</small>
												@else
													<span class="text-danger">●</span>
													<small>Inactive</small>	
												@endif
											</p>
										</div>
										<div class="col-auto">
											@if($my_menu['view']==true)
												<div class="dropdown">
													<button class="btn btn-secondary dropdown-toggle btn-sm" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												  		Action
													</button>
													<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
														@if ($my_menu['edit']==true)
															<span class="dropdown-item" onclick="goToEditLink('{{ $item->role_id }}')">Edit</span>
														@endif
														@if ($my_menu['delete']==true)
															<span class="dropdown-item text-danger" onclick="deleteConfirmation('{{ $item->role_id }}')">Delete</span>
														@endif
													</div>
											  	</div>
											@endif
										</div>
									</div>
								</a>
							@endforeach
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-xs-12">
				<div class="card shadow">
					<div class="card-header text-left"><h4 class="mb-0">Permission</h4></div>
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="table-responsive">
									<table class="table align-items-center table-flush dataTable" id="permission_table" width="100%">
										<thead class="thead-light">
											<tr>
												<th scope="col">Description</th>
												<th scope="col">View</th>
												<th scope="col">Create</th>
												<th scope="col">Edit</th>
												<th scope="col">Delete</th>
												<th scope="col">Approval</th>
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
	@include('role.script.index')
	@include('global')
@endsection

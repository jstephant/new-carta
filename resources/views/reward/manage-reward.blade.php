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
								<li class="breadcrumb-item"><a href="{{url('/reward')}}" class="text-gray-dark">Customer Rewards</a></li>
								<li class="breadcrumb-item active"><a href="#" class="text-gray-dark">Manage Reward</a></li>
							</ol>
						</nav>
					</div>
					
					<div class="col-lg-6 col-5 text-right">
						@if ($reward_item_menu['view'])
							<a href="{{ url('/reward/manage/item')}}" class="btn btn-sm btn-danger">Maintain Item</a>	
						@endif
						@if ($my_menu['create'])
							<a href="{{ url('/reward/manage/create')}}" class="btn btn-sm btn-danger">Create</a>	
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
						<div class="row">
							<div class="col-12">
								<input type="hidden" id="my_menu" value="{{ $my_menu }}">
								<div class="table-responsive">
									<table class="table align-items-center table-flush dataTable" id="manage_reward_table" width="100%">
										<thead class="thead-light">
											<tr>
												<th scope="col">No</th>
												<th scope="col">Description</th>
												<th scope="col">Segmentation</th>
												<th scope="col"># of Sheets</th>
												<th scope="col"># of Meters</th>
												<th scope="col">Points</th>
												<th scope="col">Model</th>
												<th scope="col">SKU</th>
												<th scope="col">Notes</th>
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
	@include('reward.script.manage-reward')
@endsection

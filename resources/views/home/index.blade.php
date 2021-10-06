@extends('layouts.app')

@section('content')
    <div class="header pb-6"></div>
    <div class="container-fluid -fluid mt--5">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-xs-12">
				<div class="card shadow">
					<div class="card-header bg-danger h4 text-center text-uppercase text-white">Lead Summary</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
								<label for="start_date" class="col-form-label-sm text-uppercase display-4">From</label>
								<input type="text" class="form-control flatpickr flatpickr-input" id="start_date" name="start_date" value="">
                            </div>
                            <div class="col-lg-6 col-md-6">
								<label for="end_date" class="col-form-label-sm text-uppercase display-4">To</label>
								<input type="text" class="form-control flatpickr flatpickr-input" id="end_date" name="end_date" value="">
                            </div>
						</div>
                        <br>
                        <ul class="list-group" id="lead_status">
                            @foreach ($lead_status as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item['status_name']}}
                                    <span class="badge badge-primary badge-pill">{{ $item['total'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <br>
                        {{-- <ul class="list-group" id="followup_status">
                            @foreach ($followup_status as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item['status_name']}}
                                    <span class="badge badge-primary badge-pill">{{ $item['total'] }}</span>
                                </li>
                            @endforeach
                        </ul> --}}
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-xs-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header bg-danger h4 text-center text-uppercase text-white">Account Summary</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <label for="account_from" class="col-form-label-sm text-uppercase display-4">From</label>
                                        <input type="text" class="form-control flatpickr flatpickr-input" id="account_from" name="account_from" value="">
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <label for="account_to" class="col-form-label-sm text-uppercase display-4">To</label>
                                        <input type="text" class="form-control flatpickr flatpickr-input" id="account_to" name="account_to" value="">
                                    </div>
                                </div>
                                <br>
                                <ul class="list-group" id="total_account">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        New Accounts
                                        <span class="badge badge-primary badge-pill">{{ $total_new_account }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Total Account
                                        <span class="badge badge-primary badge-pill">{{ $total_account }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header bg-danger h4 text-center text-uppercase text-white">Point Summary</div>
                            <div class="card-body">
                                <ul class="list-group" id="total_account">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Total Points
                                        <span class="badge badge-primary badge-pill">{{ $total_points }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Total Floating Points
                                        <span class="badge badge-primary badge-pill">{{ $total_floating }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Total Redeemed
                                        <span class="badge badge-primary badge-pill">{{ $total_redeem }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
				
            </div>

            <div class="col-lg-4 col-md-4 col-xs-12">
				<div class="card shadow">
					<div class="card-header bg-danger h4 text-center text-uppercase text-white">Sales Summary</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
								<label for="sales_from" class="col-form-label-sm text-uppercase display-4">From</label>
								<input type="text" class="form-control flatpickr flatpickr-input" id="sales_from" name="sales_from" value="">
                            </div>
                            <div class="col-lg-6 col-md-6">
								<label for="sales_to" class="col-form-label-sm text-uppercase display-4">To</label>
								<input type="text" class="form-control flatpickr flatpickr-input" id="sales_to" name="sales_to" value="">
                            </div>
						</div>
                        <br>
                        <ul class="list-group" id="total_sales">
                            @foreach ($total_sales as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item->sales_material_group3_desc }}
                                    <span class="badge badge-primary badge-pill">{{ $item->total }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <br>
                        <ul class="list-group" id="total_sales2">
                            @foreach ($total_sales2 as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item->sales_material_group2_desc. ' - ' . $item->sales_material_group4_desc}}
                                    <span class="badge badge-primary badge-pill">{{ $item->total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('home.script.index')
@endsection
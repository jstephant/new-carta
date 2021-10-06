@extends('layouts.app')

@section('content')
    <div class="header pb-6">
        <div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7 text-left">
                        <a href="javascript:history.back()" class="btn btn-sm btn-danger" >Back</a>
                    </div>
					<div class="col-lg-6 col-5 text-right">
                        @if ($sales_menu['view'])
                            <a href="{{ url('/sales-history/reward/detail/' . $reward->sales_networking_id ) }}" class="btn btn-sm btn-danger" >Sales History</a>    
                        @endif
                        @if ($followup_menu['view'])
                            <a href="{{ url('/followup/reward/detail/' . $reward->sales_networking_id ) }}" class="btn btn-sm btn-danger" >Follow Up History</a>    
                        @endif
						@if ($redeem_menu['view'])
                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-redeem-history" data-id="{{ $reward->sales_networking_id }}">Redeem History</a>    
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
                            <div class="col-lg-10 col-md-6 col-xs-12">
                                <input type="hidden" id="url_redeem_history" name="url_redeem_history" value="{{ $url_redeem_history }}">
                                <table class="table table-borderless" id="detail_table" width="100%">
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Customer Name</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $reward->customer_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Phone</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ ($reward->contact_one) ? (($reward->contact_one->contact_phone_one) ? $reward->contact_one->contact_phone_one->phone : '-') : '-' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Email</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ ($reward->contact_one) ? (($reward->contact_one->contact_email_one) ? $reward->contact_one->contact_email_one->email : '-') : '-' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Address</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $reward->detail_address }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Province</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ ($reward->village) ? $reward->village->subdistrict->city->province->name : '-' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">City</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ ($reward->village) ? $reward->village->subdistrict->city->name : '-' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Type</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $reward->customer_category->category }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Date of Birth</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ date('d-m-Y', strtotime($reward->customer_dob)) }}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('reward.modal.redeem-history')
	@include('reward.script.redeem-history')
	@include('global')
@endsection
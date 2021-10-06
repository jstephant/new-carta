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
                        @if ($my_menu['edit'])
                            <a href="{{url('/leads/edit/'. $lead->id) }}" class="btn btn-sm btn-danger">Edit</a>    
                        @endif
                        @if ($followup_menu['view'])
                            <a href="{{url('/followup/leads/detail/'. $lead->id) }}" class="btn btn-sm btn-danger" >Follow Up History</a>    
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
                                <table class="table table-borderless" id="detail_table" width="100%">
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Customer Name</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->customer_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Customer Category</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->customer_category }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Contact Title</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->contact_title }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Contact Name</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->contact_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Contact Phone</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->contact_phone }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Contact Email</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->contact_email }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Occupation</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->occupation_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Other Occupation</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->other_occupation }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Position</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->position_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Customer Birthday</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->customer_dob }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Address</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->detail_address }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Teritory</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->teritory_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Province</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->province_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">City</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->city_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Sub District</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->subdistrict_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Village</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->village_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">E-Catalog</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ ($lead->is_e_catalog=='0') ? 'No' : 'Yes' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Average HPL Usage/month</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->avg_hpl_usage }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Data Source</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->data_source_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Assigned To</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;">
                                            @if (count($history_assigned)==0)
                                                <span class="name mb-0 text-sm">{{ $lead->assigned_to_name }}</span>
                                            @elseif (count($history_assigned)==1)
                                                <span class="name mb-0 text-sm">{{ $lead->assigned_to_name . (($history_assigned[0]['created_at']) ? ' on ' . date('Y-m-d', strtotime($history_assigned[0]['created_at'])) : '') . (($history_assigned[0]['assigned_by']) ? ' by ' . $history_assigned[0]['assigned_by']['name'] : '')  }}</span>   
                                            @elseif (count($history_assigned)>1)
                                                <span class="name mb-0 text-sm">{{ $lead->assigned_to_name }}</span><br>
                                                @foreach ($history_assigned as $item)
                                                    <span class="name mb-0 text-sm">{{ ' on ' . date('Y-m-d', strtotime($item->created_at)) . ' by ' . $item->assigned_by->name}}</span><br>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Brand</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->brand_name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Notes</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->notes }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Products</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->products }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" style="padding:8px; !important;"><span class="name mb-0 text-sm h4">Status</span></td>
                                        <td width="1px" style="padding:8px; !important;">:</td>
                                        <td width="100%" style="padding:8px; !important;"><span class="name mb-0 text-sm">{{ $lead->status_name }}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
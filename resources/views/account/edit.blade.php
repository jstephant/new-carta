@extends('layouts.app')

@section('content')
    <div class="header bg-white pb-6"></div>
    <div class="container-fluid -fluid mt--7">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mt-5 mb-5">
                    <form class="form-basic needs-validation" novalidate method="post" role="form" action="{{ route('account.edit.post') }}" enctype="multipart/form-data" id="form-edit-account">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="pl-lg-2">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="customer_name" class="form-control-label">Customer Name *</label>
                                            <input type="text" id="customer_name" name="customer_name" class="form-control" autocomplete="off" value="{{ $account->customer_name }}" required autofocus>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="customer_category" class="form-control-label">Category</label>
                                            <select id="customer_category" name="customer_category" class="form-control select-type-1">
                                                <option value=""></option>
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item->customer_category_id }}" {{ ($item->customer_category_id==$account->customer_category_id) ? 'selected' : '' }}>{{ $item->category }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" id="sales_networking_contact_id" name="sales_networking_contact_id" value="{{ $account->sales_networking_contact_id }}">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" id="sales_networking_contact_phone_id" name="sales_networking_contact_phone_id" value="{{ $account->sales_networking_contact_phone_id }}">
                                            <label for="contact_phone" class="form-control-label">Phone</label>
                                            <input type="tel" id="contact_phone" name="contact_phone" class="form-control" value="{{ ($account->phone) ? $account->phone : '' }}" autocomplete="off">
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" id="sales_networking_contact_email_id" name="sales_networking_contact_email_id" value="{{ $account->sales_networking_contact_email_id }}">
                                            <label for="contact_email" class="form-control-label">Email</label>
                                            <input type="email" id="contact_email" name="contact_email" class="form-control" value="{{ ($account->email) ? $account->email : '' }}" autocomplete="off">
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="customer_dob" class="form-control-label">Customer Birthday (yyyy-mm-dd)</label>
                                            <input type="text" id="customer_dob" name="customer_dob" class="form-control" value="{{ $account->customer_dob }}" autocomplete="off">
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="address" class="form-control-label">Address *</label>
                                            <textarea class="form-control" id="address" name="address" rows="5" required>{{ trim($account->detail_address) }}</textarea>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="teritory" class="form-control-label">Teritory</label>
                                            <select id="teritory" name="teritory" class="form-control select-type-1">
                                                <option value=""></option>
                                                @foreach ($teritories as $item)
                                                    <option value="{{ $item->teritory_id }}" {{ ($item->teritory_id==$account->teritory_id) ? 'selected' : '' }}>{{ $item->name }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="province" class="form-control-label">Province</label>
                                            <select id="province" name="province" class="form-control select-type-2">
                                                @if($provinces)
                                                    <option value=""></option>
                                                    @foreach ($provinces as $item)
                                                        <option value="{{ $item->province_id }}" {{ ($item->province_id==$account->province_id) ? 'selected' : '' }}>{{ $item->name }}</option> 
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="city" class="form-control-label">City</label>
                                            <select id="city" name="city" class="form-control select-type-2">
                                                @if($cities)
                                                    <option value=""></option>
                                                    @foreach ($cities as $item)
                                                        <option value="{{ $item->city_id }}" {{ ($item->city_id==$account->city_id) ? 'selected' : '' }}>{{ $item->name }}</option> 
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="subdistrict" class="form-control-label">Sub District</label>
                                            <select id="subdistrict" name="subdistrict" class="form-control select-type-2">
                                                @if($subdistricts)
                                                    <option value=""></option>
                                                    @foreach ($subdistricts as $item)
                                                        <option value="{{ $item->subdistrict_id }}" {{ ($item->subdistrict_id==$account->subdistrict_id) ? 'selected' : '' }}>{{ $item->name }}</option> 
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="village" class="form-control-label">Village</label>
                                            <select id="village" name="village" class="form-control select-type-2">
                                                @if($villages)
                                                    <option value=""></option>
                                                    @foreach ($villages as $item)
                                                        <option value="{{ $item->village_id }}" {{ ($item->village_id==$account->village_id) ? 'selected' : '' }}>{{ $item->name }}</option> 
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="postal_code" class="form-control-label">Postal Code</label>
                                            <input type="text" id="postal_code" name="postal_code" class="form-control" value="{{ $account->postal_code }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="data_source" class="form-control-label">Data Source</label>
                                            <select id="data_source" name="data_source" class="form-control select-type-1">
                                                <option value=""></option>
                                                @foreach ($data_sources as $item)
                                                    <option value="{{ $item->data_source_id }}" {{ ($item->data_source_id==$account->data_source_id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <div class="d-flex">
                                <input type="hidden" id="sales_networking_id" name="sales_networking_id" value="{{ $account->sales_networking_id }}">
                                <a href="/account" id="btn_cancel" name="action" class="btn btn-link">Cancel</a>
                                <button type="submit" id="btn_save" name="action" class="btn btn-facebook" value="save">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('account.script.edit')
@endsection
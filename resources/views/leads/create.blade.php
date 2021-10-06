@extends('layouts.app')

@section('content')
    <div class="header bg-white pb-6"></div>
    <div class="container-fluid -fluid mt--7">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mt-5 mb-5">
                    <form class="form-basic needs-validation" novalidate method="post" role="form" action="{{ route('leads.create.post') }}" enctype="multipart/form-data" id="form-create-lead">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="pl-lg-2">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="customer_name" class="form-control-label">Customer Name *</label>
                                            <input type="text" id="customer_name" name="customer_name" class="form-control" autocomplete="off" required autofocus value="{{ old('customer_name') }}">
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="customer_category" class="form-control-label">Category *</label>
                                            <select id="customer_category" name="customer_category" class="form-control select-type-1" required>
                                                <option value=""></option>
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item->customer_category_id }}" {{ ($item->customer_category_id==old('customer_category')) ? 'selected' : '' }}>{{ $item->category }}</option> 
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="contact_title" class="form-control-label">Contact Title</label>
                                            <select id="contact_title" name="contact_title" class="form-control select-type-1">
                                                <option value=""></option>
                                                @foreach ($contact_titles as $item)
                                                    <option value="{{ $item->contact_title_id }}" {{ ($item->contact_title_id==old('contact_title')) ? 'selected' : '' }}>{{ $item->name }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="contact_name" class="form-control-label">Contact Name *</label>
                                            <input type="text" id="contact_name" name="contact_name" class="form-control" autocomplete="off" required value="{{ old('contact_name') }}">
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="contact_phone" class="form-control-label">Contact Phone *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">+62</span>
                                                </div>
                                                <input type="text" id="contact_phone" name="contact_phone" class="form-control phone" autocomplete="off" required value="{{ old('contact_phone') }}">
                                            </div>
                                            <span class="text-muted h6">Ex: 08123456789 / 021355471</span>
                                            @if(session()->get('contact_phone.error'))
                                                <label class="text-danger h5">{!! session()->get('contact_phone.error') !!}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="contact_email" class="form-control-label">Contact Email</label>
                                            <input type="email" id="contact_email" name="contact_email" class="form-control" autocomplete="off" value="{{ old('contact_email') }}"> 
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="occupation" class="form-control-label">Occupation</label>
                                            <select id="occupation" name="occupation" class="form-control select-type-1">
                                                <option value=""></option>
                                                @foreach ($occupations as $item)
                                                    <option value="{{ $item->id }}" {{ ($item->id==old('occupation')) ? 'selected' : '' }} >{{ $item->name }}</option> 
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="occupation_name" name="occupation_name">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12" id="div_other_occupation">
                                        <div class="form-group">
                                            <label for="other_occupation" class="form-control-label">Other Occupation</label>
                                            <input type="text" id="other_occupation" name="other_occupation" class="form-control" autocomplete="off" value="{{ old('other_occupation') }}">
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="position" class="form-control-label">Position</label>
                                            <select id="position" name="position" class="form-control select-type-1">
                                                <option value=""></option>
                                                @foreach ($positions as $item)
                                                    <option value="{{ $item->position_id }}" {{ ($item->position_id==old('position')) ? 'selected' : ''}}>{{ $item->name }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="customer_dob" class="form-control-label">Customer Birthday (yyyy-mm-dd)</label>
                                            <input type="text" id="customer_dob" name="customer_dob" class="form-control" autocomplete="off" value="{{old('customer_dob')}}">
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="address" class="form-control-label">Address *</label>
                                            <textarea class="form-control" id="address" name="address" rows="5" required>{{ old('address') }}</textarea>
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
                                                    <option value="{{ $item->teritory_id }}" {{ ($item->teritory_id==old('teritory')) ? 'selected' : '' }}>{{ $item->name }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="province" class="form-control-label">Province</label>
                                            <select id="province" name="province" class="form-control select-type-2"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="city" class="form-control-label">City</label>
                                            <select id="city" name="city" class="form-control select-type-2"></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="subdistrict" class="form-control-label">Sub District</label>
                                            <select id="subdistrict" name="subdistrict" class="form-control select-type-2"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="village" class="form-control-label">Village</label>
                                            <select id="village" name="village" class="form-control select-type-2"></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="postal_code" class="form-control-label">Postal Code</label>
                                            <input type="text" id="postal_code" name="postal_code" class="form-control" autocomplete="off" value="{{ old('postal_code') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="ecatalog" class="form-control-label">E-Catalog</label>
                                            <select id="ecatalog" name="ecatalog" class="form-control select-type-1">
                                                <option value="0" {{ (0==old('ecatalog')) ? 'selected' : '' }}>No</option> 
                                                <option value="1" {{ (1==old('ecatalog')) ? 'selected' : '' }}>Yes</option> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="avg_hpl" class="form-control-label">Average HPL Usage/month</label>
                                            <select id="avg_hpl" name="avg_hpl" class="form-control select-type-1">
                                                <option value=""></option>
                                                @foreach ($avg_hpls as $item)
                                                    <option value="{{ $item->id }}" {{ ($item->id==old('avg_hpl')) ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="data_source" class="form-control-label">Data Source *</label>
                                            <select id="data_source" name="data_source" class="form-control select-type-1" required>
                                                <option value=""></option>
                                                @foreach ($data_sources as $item)
                                                    <option value="{{ $item->data_source_id }}" {{ ($item->data_source_id==old('data_source')) ? 'selected' : '' }}>{{ $item->name }}</option>     
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="assigned_to" class="form-control-label">Assigned To</label>
                                            <select id="assigned_to" name="assigned_to" class="form-control select-type-2">
                                                <option value=""></option>
                                                @foreach ($assigned_to as $item)
                                                    <option value="{{ $item->nik }}" {{ ($item->nik==old('assigned_to')) ? 'selected' : '' }}>{{ $item->nik . ' | ' . $item->name }}</option>     
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="brand" class="form-control-label">Brand *</label>
                                            <select id="brand" name="brand" class="form-control select-type-1" required>
                                                @foreach ($brands as $item)
                                                    <option value="{{ $item->id }}" {{ ($item->id==old('brand')) ? 'selected' : '' }}>{{ $item->name }}</option>     
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="notes" class="form-control-label">Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="5">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="products" class="form-control-label">Product</label>
                                            <textarea class="form-control" id="products" name="products" rows="5">{{ old('products') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <div class="d-flex">
                                <a href="{{url('/leads')}}" id="btn_cancel" name="action" class="btn btn-link">Cancel</a>
                                <button type="submit" id="btn_save" name="action" class="btn btn-facebook" value="save">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('leads.script.create')
@endsection
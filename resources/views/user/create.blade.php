@extends('layouts.app')

@section('content')
    <div class="header bg-white pb-6"></div>
    <div class="container-fluid -fluid mt--7">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mt-5 mb-5">
                    <form class="form-basic needs-validation" novalidate method="post" role="form" action="{{ route('user.create.post') }}" enctype="multipart/form-data" id="form-create-lead">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="nik" class="form-control-label">NIK *</label>
                                        <select id="nik" name="nik" class="form-control select-type-2">
                                            <option value=""></option>
                                            @foreach ($staff as $item)
                                                <option value="{{ $item->NIK }}" {{ ($item->NIK==old('nik')) ? 'selected' : '' }} {{ ($item->disabled) ? 'disabled' : '' }}>{{ $item->NIK . ' | '. $item->NAMA }}</option> 
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Name *</label>
                                        <input type="text" id="name" name="name" class="form-control" autocomplete="off" required autofocus value="{{ old('name') }}" readonly>
                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="email" class="form-control-label">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" autocomplete="off" value="{{ old('email') }}" readonly> 
                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="nik_atasan" class="form-control-label">NIK Atasan</label>
                                        <select id="nik_atasan" name="nik_atasan" class="form-control select-type-2">
                                            <option value=""></option>
                                            @foreach ($user_list as $item)
                                                <option value="{{ $item['nik'] }}" {{ ($item['nik']==old('nik_atasan')) ? 'selected' : '' }}>{{ $item['nik'] . ' | '. $item['name'] }}</option> 
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="nik_atasan2" class="form-control-label">NIK Atasan 2</label>
                                        <select id="nik_atasan2" name="nik_atasan2" class="form-control select-type-2">
                                            <option value=""></option>
                                            @foreach ($user_list as $item)
                                                <option value="{{ $item['nik'] }}" {{ ($item['nik']==old('nik_atasan2')) ? 'selected' : '' }}>{{ $item['nik'] . ' | '. $item['name'] }}</option> 
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="role" class="form-control-label">Role *</label>
                                        <select id="role" name="role" class="form-control select-type-1" required>
                                            <option value=""></option>
                                            @foreach ($roles as $item)
                                                <option value="{{ $item->role_id }}" {{ ($item->role_id==old('role')) ? 'selected' : '' }} >{{ $item->name }}</option> 
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="teritory" class="form-control-label">Teritory *</label>
                                        <select id="teritory" name="teritory[]" class="form-control select-type-2" multiple required>
                                            <option value=""></option>
                                            @foreach ($teritory as $item)
                                                <option value="{{ $item->teritory_id }}" {{ ($item->teritory_id==old('teritory')) ? 'selected' : '' }} >{{ $item->name }}</option> 
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="sales_org" class="form-control-label">Sales Org.</label>
                                        <select id="sales_org" name="sales_org" class="form-control select-type-1">
                                            <option value=""></option>
                                            @foreach ($sales_org as $item)
                                                <option value="{{ $item }}" {{ ($item==old('sales_org')) ? 'selected' : '' }} >{{ $item }}</option> 
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <div class="d-flex">
                                <a href="{{url('/data-user')}}" id="btn_cancel" name="action" class="btn btn-link">Cancel</a>
                                <button type="submit" id="btn_save" name="action" class="btn btn-facebook" value="save">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('user.script.create')
@endsection
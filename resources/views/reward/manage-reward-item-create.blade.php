@extends('layouts.app')

@section('content')
    <div class="header bg-white pb-6"></div>
    <div class="container-fluid -fluid mt--7">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mt-5 mb-5">
                    <form class="form-basic needs-validation" novalidate method="post" role="form" action="{{ route('create-manage-reward-item.post') }}" enctype="multipart/form-data" id="form-create-lead">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="pl-lg-2">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="item_code" class="form-control-label">Item Code *</label>
                                            <input type="text" id="item_code" name="item_code" class="form-control" autocomplete="off" required autofocus>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="description" class="form-control-label">Description *</label>
                                            <input type="text" id="description" name="description" class="form-control" autocomplete="off" required>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="point" class="form-control-label">Point Required *</label>
                                            <input type="number" id="point" name="point" class="form-control" autocomplete="off" required>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="customer_category" class="form-control-label">Category *</label>
                                            <select id="customer_category" name="customer_category[]" class="form-control" multiple required>
                                                <option value=""></option>
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item->customer_category_id }}">{{ $item->category }}</option> 
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
                                            <textarea class="form-control" id="notes" name="notes" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <div class="d-flex">
                                <a href="{{url('/reward/manage/item')}}" id="btn_cancel" name="action" class="btn btn-link">Cancel</a>
                                <button type="submit" id="btn_save" name="action" class="btn btn-facebook" value="save">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('reward.script.manage-reward-item-create')
@endsection
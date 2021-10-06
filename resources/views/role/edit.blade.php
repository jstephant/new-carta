@extends('layouts.app')

@section('content')
    <div class="header bg-white pb-6"></div>
    <div class="container-fluid -fluid mt--7">
        <form class="form-basic needs-validation" novalidate method="post" role="form" action="{{ route('role.edit.post') }}" enctype="multipart/form-data" id="form-create-lead">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <div class="card shadow mt-5 mb-5">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Name *</label>
                                        <input type="text" id="name" name="name" class="form-control" autocomplete="off" required autofocus value="{{ $role->name }}">
                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="status" class="form-control-label">Status *</label>
                                        <select id="status" name="status" class="form-control select-type-1" required>
                                            <option value="1" {{ ($role->st==1) ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ ($role->st==0) ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <div class="d-flex">
                                <input type="hidden" id="role_id" name="role_id" value="{{ $role->role_id }}">
                                <a href="{{url('/role')}}" id="btn_cancel" name="action" class="btn btn-link">Cancel</a>
                                <button type="submit" id="btn_save" name="action" class="btn btn-facebook">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-xs-12">
                    <div class="card shadow mt-5 mb-5">
                        <div class="card-header text-left"><h4 class="mb-0">Permission</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush dataTable" id="feature_role_table" width="100%">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th scope="col">Description</th>
                                            <th scope="col">Access</th>
                                            <th scope="col">Create</th>
                                            <th scope="col">Edit</th>
                                            <th scope="col">Delete</th>
                                            <th scope="col">Approval</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($features as $key => $item)
                                            <tr>
                                                <td>
                                                    @if ($item['parent_id'] > 0)
                                                        <span class="ml-4">{{ $item['name'] }}</span>
                                                    @else
                                                        <span>{{ $item['name'] }}</span>
                                                    @endif
                                                    <input type="hidden" name="menu_carta_id[]" class="menu_carta_id" value="{{ $item['id'] }}">
                                                </td>
                                                <td>
                                                    @if ($item['is_view'])
                                                        <label class="custom-toggle">
                                                            <input type="checkbox" name="view[]" class="view" {{ ($item['detail_is_view']==true) ? 'checked' : '' }}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    @endif
                                                    <input type="hidden" name="view_value[]" class="view_value" value="{{ ($item['detail_is_view']==true) ? "1" : "0" }}">
                                                </td>
                                                <td>
                                                    @if ($item['is_create'])
                                                        <label class="custom-toggle">
                                                            <input type="checkbox" name="create[]" class="create" {{ ($item['detail_is_create']==true) ? 'checked' : '' }}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    @endif
                                                    <input type="hidden" name="create_value[]" class="create_value" value="{{ ($item['detail_is_create']==true) ? "1" : "0" }}">
                                                </td>
                                                <td>
                                                    @if ($item['is_edit'])
                                                        <label class="custom-toggle">
                                                            <input type="checkbox" name="edit[]" class="edit" {{ ($item['detail_is_edit']==true) ? 'checked' : '' }}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    @endif
                                                    <input type="hidden" name="edit_value[]" class="edit_value" value="{{ ($item['detail_is_edit']==true) ? "1" : "0" }}">
                                                </td>
                                                <td>
                                                    @if ($item['is_delete'])
                                                        <label class="custom-toggle">
                                                            <input type="checkbox" name="delete[]" class="delete" {{ ($item['detail_is_delete']==true) ? 'checked' : '' }}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    @endif
                                                    <input type="hidden" name="delete_value[]" class="delete_value" value="{{ ($item['detail_is_delete']==true) ? "1" : "0" }}">
                                                </td>
                                                <td>
                                                    @if ($item['is_approval'])
                                                        <label class="custom-toggle">
                                                            <input type="checkbox" name="approval[]" class="approval" {{ ($item['detail_is_approval']==true) ? 'checked' : '' }}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    @endif
                                                    <input type="hidden" name="approval_value[]" class="approval_value" value="{{ ($item['detail_is_approval']==true) ? "1" : "0" }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('role.script.edit')
@endsection
<div class="modal fade" id="modal-upload" tabindex="-1" role="dialog" aria-labelledby="modal-upload" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('leads.import.post') }}" class="needs-validation" novalidate method="POST" enctype="multipart/form-data" id="form-upload" role="form">
                <div class="modal-header justify-content-center bg-danger">
                    <h5 id="title" class="modal-title text-white text-uppercase">Import Lead</h5>
                </div>
                <div class="modal-body justify-content-center">
                    <div class="row align-content-center">
                        <div class="col-lg-12">
                            <label for="customFilelang" class="form-control-label">Upload File</label>
                            <div class="input-group control-group align-items-center">
                                <input type="file" class="form-control" id="attachment" name="attachment" lang="en">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <input type="hidden" id="data_type" name="data_type">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <button type="button" class="btn btn-link btn-sm mr-auto" data-dismiss="modal" id="btn_close">Close</button>
                    <button type="submit" class="btn btn-facebook btn-sm" id="btn_upload" name="btn_upload">Import</button>
                </div>
            </form>
        </div>
	</div>
</div>

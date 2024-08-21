@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Speciality</h4>
            <div class="text-right">
                <button type="button" class="btn btn-info mr-2" data-toggle ="modal" data-target="#specialtyModal" id="addSpecialty">
                    Add Speciality
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="specialtyList" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center" style="color: black;">
                            <th>Sr.No</th>
                            <th>Name</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

    <div class="modal" id="specialtyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Add Doctor Specialty</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="specialty">Specialty <span style="color: red;">*</span> :</label>
                            <input type="text" name="specialty" id="specialty" class="form-control" placeholder="enter specialty name..." value="">
                            <span class="errorMessage" style="display: none; color:red"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="submit">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    let saveSpecialty = "{{ route('admin.save-specialty') }}";
    let specialtyList = "{{ route('admin.get-specialty') }}";
</script>

<script src="{{ asset('js/specialty.js') }}"></script>
@endpush
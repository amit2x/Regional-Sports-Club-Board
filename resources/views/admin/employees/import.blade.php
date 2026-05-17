@extends('layouts.admin')

@section('title', 'Import Employees')

@section('content')
<div class="page-header">
    <h1 class="page-title">Import Employees</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item active">Import</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    {{-- Upload Form --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-file-earmark-excel me-2"></i>Upload Excel File
                </h5>
            </div>
            <div class="card-body">
                @if(session('import_errors'))
                    <div class="alert alert-warning">
                        <h6><i class="bi bi-exclamation-triangle me-2"></i>Import completed with errors:</h6>
                        <ul class="mb-0 small">
                            @foreach(session('import_errors') as $error)
                                <li>
                                    <strong>Row {{ $error['row'] ?? 'N/A' }}:</strong>
                                    {{ $error['error'] ?? 'Unknown error' }}
                                    @if(isset($error['employee_id']))
                                        (Employee ID: {{ $error['employee_id'] }})
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.employees.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Select Excel File <span class="text-danger">*</span></label>
                        <div class="border border-2 border-dashed rounded-3 p-4 text-center" id="dropZone">
                            <i class="bi bi-cloud-upload text-primary" style="font-size: 48px;"></i>
                            <p class="mt-2 mb-1">Drag and drop your file here</p>
                            <p class="text-muted small">or</p>
                            <label class="btn btn-primary btn-sm">
                                <i class="bi bi-folder2-open me-1"></i>Browse Files
                                <input type="file" name="file" class="d-none" id="fileInput" accept=".xlsx,.xls,.csv" required>
                            </label>
                            <p class="text-muted small mt-2 mb-0" id="fileName">No file selected</p>
                        </div>
                        <small class="text-muted">
                            Supported formats: .xlsx, .xls, .csv (Max size: 10MB)
                        </small>
                        <div class="invalid-feedback" id="fileError"></div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <div>
                            <strong>Important:</strong> Make sure your Excel file follows the required format.
                            <a href="{{ route('admin.employees.template') }}" class="alert-link">Download template</a>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="importBtn" disabled>
                        <i class="bi bi-upload me-1"></i>Import Employees
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    {{-- Instructions --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Instructions
                </h5>
            </div>
            <div class="card-body">
                <ol class="small mb-0">
                    <li class="mb-2">Download the Excel template using the button below.</li>
                    <li class="mb-2">Fill in the employee details as per the column headers.</li>
                    <li class="mb-2">Ensure all required fields are filled correctly.</li>
                    <li class="mb-2">Upload the completed Excel file.</li>
                    <li class="mb-2">Review any errors after import.</li>
                </ol>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Required Columns
                </h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Column</th>
                            <th>Required</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Employee ID</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td>Name</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td>PAN Number</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td>Designation</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td>Department</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td>Airport Code</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td>Region Code</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td>Gender</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td>Date of Birth</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td>Email</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td>Mobile</td><td><span class="badge bg-warning">Optional</span></td></tr>
                        <tr><td>Sports Category</td><td><span class="badge bg-warning">Optional</span></td></tr>
                        <tr><td>Blood Group</td><td><span class="badge bg-warning">Optional</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('admin.employees.template') }}" class="btn btn-success w-100">
            <i class="bi bi-download me-1"></i>Download Excel Template
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const importBtn = document.getElementById('importBtn');
    const dropZone = document.getElementById('dropZone');

    // File input change
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            const validTypes = ['.xlsx', '.xls', '.csv'];
            const extension = '.' + file.name.split('.').pop().toLowerCase();

            if (validTypes.includes(extension) && file.size <= 10485760) {
                fileName.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
                fileName.classList.remove('text-muted');
                fileName.classList.add('text-success');
                importBtn.disabled = false;
                dropZone.classList.add('border-primary');
            } else {
                fileName.textContent = 'Invalid file. Please select .xlsx, .xls, or .csv (Max 10MB)';
                fileName.classList.add('text-danger');
                importBtn.disabled = true;
                dropZone.classList.remove('border-primary');
            }
        }
    });

    // Drag and drop
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('bg-light');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('bg-light');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('bg-light');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    // Form submit with loading
    $('#importForm').submit(function() {
        Swal.fire({
            title: 'Importing...',
            text: 'Please wait while we process your file.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }
});
</script>

<style>
.border-dashed {
    border-style: dashed !important;
    transition: all 0.3s;
}
.border-dashed:hover {
    border-color: #667eea !important;
}
</style>
@endpush

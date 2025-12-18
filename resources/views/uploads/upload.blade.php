@include('layouts.dashboard-header')

<style>
    .import {
        background-color: white;
    }

    .import .main-card {
        border-radius: 0 !important;
        border: none !important;
    }

    .import .card-title {
        font-family: "Poppins", sans-serif;
        font-size: 18px;
        font-weight: 600;
    }

    .import p {
        font-family: "Poppins", sans-serif;
        font-size: 10px;
        font-weight: 400;
        color: #00000080;
    }

    .import .dotted-card {
        border-style: dashed;
        border-color: #CC0000;
        border-radius: 20px;
        width: auto;
        height: 240px;
    }

    .file-upload .title,
    .file-upload .info {
        font-family: "Poppins", sans-serif;
        font-size: 10px;
        font-weight: 400;
        color: #000000;
        display: flex;
        justify-content: center;
        margin-bottom: 5px;
    }

    .file-upload .info {
        color: #00000080;
    }

    .file-name {
        display: flex;
        justify-content: center;
    }

    .upload-circle {
        background-color: #771d1d0d;
        padding: 30px;
    }

    @media (max-width: 1384px) {
        .table-responsive {
            overflow-x: auto;
        }

        .table-responsive table {
            min-width: 35vw;
        }
    }
</style>

<div class="main-wrapper">
    <div class="row d-flex justify-content-between ms-3">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Upload</h1>
        </div>
    </div>

    <hr class="red-line mt-0">

    <div class="row d-flex gap-4 ms-3">
        <!-- Recent Uploads -->
        <div class="col-12 col-lg-5 col-md-5 import">
            <div class="card main-card">
                <div class="card-body ps-4">
                    <h5 class="card-title py-4">Recent Upload</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">File Type</th>
                                    <th scope="col">Imported Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($uploads as $upload)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $upload->file_type)) }}</td>
                                    <td>{{ $upload->created_at->format('d.m.Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">No uploads found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="col-12 col-lg-6 col-md-6 import">
            <div class="card main-card">
                <div class="card-body ps-4">
                    <h5 class="card-title">Upload Report</h5>
                    <p>Import data from the database</p>
                        @if(Session::has('success')) <div class="alert alert-success mt-2 mb-2">{{ Session::get('success') }}</div>@endif
                    @if(Session::has('fail')) <div class="alert alert-danger mt-2 mb-2">{{ Session::get('fail') }}</div>@endif

                    <form id="uploadForm" method="POST" action="{{ route('admin.fileupload.store') }}" enctype="multipart/form-data">
                        @csrf


                        <div class="mb-4">
                            <label for="file_type" class="form-label custom-input-label">Select Report Type</label>

                            <div class="dropdown w-100">
                                <button class="btn custom-dropdown w-100 text-start" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Report Type
                                    <span class="custom-arrow"></span>
                                </button>
                                <ul class="dropdown-menu custom-dropdown-menu w-100"
                                    aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#" data-value="return_cheque">Return Cheque Report</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="customer">Customer Report</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="invoice">Invoices</a></li>
                                     <li><a class="dropdown-item" href="#" data-value="credit-note">Credit Notes</a></li>
                                </ul>
                                
                            </div>
                             @if($errors->has("file_type")) <div class="alert alert-danger mt-2">{{ $errors->first('file_type') }}</div>@endif
                            <input type="hidden" name="file_type" id="file_type" required>
                            <small id="fileTypeError" class="text-danger" style="display: none;">Please select a report type before uploading.</small>
                        </div>

                        <div class="card dotted-card" id="dropZone">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                <input type="file" class="d-none" id="fileInput" name="file" accept=".xlsx,.xls,.csv" required>

                                <!-- Upload Circle -->
                                <div class="upload-circle rounded-circle">
                                    <svg width="27" height="28" viewBox="0 0 27 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M1.8 1.81465H0V0.0146484H1.8V1.81465ZM9 1.81465H7.2V0.0146484H9V1.81465ZM16.2 1.81465H14.4V0.0146484H16.2V1.81465ZM5.4 5.41465H25.2V12.6146H23.4V7.21465H7.2V23.4146H12.6V25.2146H5.4V5.41465ZM1.8 9.01465H0V7.21465H1.8V9.01465ZM12.8628 12.8774C12.9763 12.764 13.1182 12.683 13.2736 12.6428C13.429 12.6026 13.5923 12.6047 13.7466 12.6488L26.3466 16.2488C26.5251 16.2999 26.6835 16.405 26.7999 16.5497C26.9164 16.6943 26.9853 16.8714 26.9971 17.0568C27.0089 17.2421 26.9631 17.4265 26.866 17.5848C26.7689 17.7431 26.6252 17.8675 26.4546 17.9408L20.4858 20.5004L17.928 26.4692C17.8546 26.6398 17.7303 26.7835 17.572 26.8807C17.4137 26.9778 17.2292 27.0236 17.0439 27.0118C16.8586 26.9999 16.6815 26.9311 16.5368 26.8146C16.3922 26.6981 16.2871 26.5398 16.236 26.3612L12.636 13.7612C12.5919 13.6073 12.5897 13.4445 12.6296 13.2894C12.6695 13.1344 12.75 12.991 12.8628 12.8774ZM14.8104 14.8268L17.2692 23.4344L18.972 19.4618C19.063 19.249 19.2326 19.0795 19.4454 18.9884L23.418 17.2856L14.8104 14.8268ZM1.8 16.2146H0V14.4146H1.8V16.2146Z"
                                            fill="#CC0000" />
                                    </svg>
                                </div>

                                <!-- Upload Text -->
                                <div class="file-upload">
                                    <p class="title">Drag files here</p>
                                    <p class="info">or, upload files from your library (Max size of 100MB)</p>
                                </div>
                            </div>
                        </div>

                        <div class="file-name mt-2" id="fileName"></div>
                             @if($errors->has("file")) <div class="alert alert-danger mt-2">{{ $errors->first('file') }}</div>@endif
<div class="action-button-lg-row mt-4">
    <button id="submitBtn" type="submit" class="red-action-btn-lg mb-3 submit">
        Submit
    </button>
</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Toast message -->
<div id="user-toast" class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-4"
    role="alert" aria-live="assertive" aria-atomic="true"
    style="z-index: 9999; display: none; min-width: 320px;">
    <div class="d-flex align-items-center">
        <span class="toast-icon-circle d-flex align-items-center justify-content-center me-3">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="12" fill="#fff" />
                <path d="M7 12.5l3 3 7-7" stroke="#28a745" stroke-width="2" fill="none" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
        <div class="toast-body flex-grow-1">File uploaded successfully</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>

<script>
    document.getElementById('submitBtn').addEventListener('click', () => {
        document.getElementById('uploadForm').dispatchEvent(new Event('submit'));
    });

    // Dropdown
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', e => {
            e.preventDefault();
            const value = e.target.getAttribute('data-value');
            document.getElementById('file_type').value = value;
            document.getElementById('dropdownMenuButton').innerHTML = e.target.textContent + '<span class="custom-arrow"></span>';
            document.getElementById('fileTypeError').style.display = 'none'; // hide error on selection
        });
    });

    // File drag & drop
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');

    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length) handleFileUpload(files[0]);
    });
    fileInput.addEventListener('change', e => {
        if (e.target.files.length) handleFileUpload(e.target.files[0]);
    });

    function handleFileUpload(file) {
        if (file.size <= 100 * 1024 * 1024) {
            fileName.textContent = `Uploaded: ${file.name}`;
        } else {
            alert('File size exceeds 100MB');
            fileInput.value = '';
        }
    }

document.getElementById('uploadForm').addEventListener('submit', function (e) {
    const fileType = document.getElementById('file_type').value.trim();
    const errorMsg = document.getElementById('fileTypeError');

    errorMsg.style.display = 'none';
    if (!fileType) {
        e.preventDefault(); // block submit
        errorMsg.style.display = 'block';
    }
});

</script>
@include('layouts.dashboard-header')
<div class="container-fluid">

                <form class="" action="" method="post"  enctype="multipart/form-data">
                @csrf
            <div class="main-wrapper">

                <div class="row d-flex justify-content-between ms-3">
                    <div class="col-lg-6 col-12">
                        <h1 class="header-title">Import Customers</h1>
                    </div>

                </div>
                <div class="row d-flex gap-4 ms-3">
                    <div class="col-12 col-lg-5 col-md-5 import">
                        <div class="card main-card">
                            <div class="card-body ps-5">
                                <h5 class="card-title py-4">Recent Import</h5>
                                <div class="table-responsive division-table">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">File Name</th>
                                                <th scope="col">Imported Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($reports as $report){ ?>   
                                            <tr>
                                                <td>{{$report->name}}</th>
                                                <td>{{$report->date}}</td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-md-6 import">
                        <div class="card main-card">
                            <div class="card-body ps-5">
                                <h5 class="card-title">Import SAP Sales Report</h5>
                                <p>Import user data from the database</p>
                                <div class="card dotted-card" id="dropZone">
                                    <div class="card-body d-flex justify-content-center align-items-center">
                              
                                            <div class="row">
                                                <input type="file" class="d-none" id="fileInput" name="customers"  accept=".xls, .xlsx, .csv">

                                                <div class="file-name" id="fileName">
                                                    <div class="upload-circle rounded-circle">
                                                        <svg width="27" height="28" viewBox="0 0 27 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.8 1.81465H0V0.0146484H1.8V1.81465ZM9 1.81465H7.2V0.0146484H9V1.81465ZM16.2 1.81465H14.4V0.0146484H16.2V1.81465ZM5.4 5.41465H25.2V12.6146H23.4V7.21465H7.2V23.4146H12.6V25.2146H5.4V5.41465ZM1.8 9.01465H0V7.21465H1.8V9.01465ZM12.8628 12.8774C12.9763 12.764 13.1182 12.683 13.2736 12.6428C13.429 12.6026 13.5923 12.6047 13.7466 12.6488L26.3466 16.2488C26.5251 16.2999 26.6835 16.405 26.7999 16.5497C26.9164 16.6943 26.9853 16.8714 26.9971 17.0568C27.0089 17.2421 26.9631 17.4265 26.866 17.5848C26.7689 17.7431 26.6252 17.8675 26.4546 17.9408L20.4858 20.5004L17.928 26.4692C17.8546 26.6398 17.7303 26.7835 17.572 26.8807C17.4137 26.9778 17.2292 27.0236 17.0439 27.0118C16.8586 26.9999 16.6815 26.9311 16.5368 26.8146C16.3922 26.6981 16.2871 26.5398 16.236 26.3612L12.636 13.7612C12.5919 13.6073 12.5897 13.4445 12.6296 13.2894C12.6695 13.1344 12.75 12.991 12.8628 12.8774ZM14.8104 14.8268L17.2692 23.4344L18.972 19.4618C19.063 19.249 19.2326 19.0795 19.4454 18.9884L23.418 17.2856L14.8104 14.8268ZM1.8 16.2146H0V14.4146H1.8V16.2146Z" fill="#CC0000"/>
                                                        </svg>  
                                                    </div>
                                               
                                                </div>
                                                
                                            </div>

                                            <div class="justify-content-center file-upload">

                                                <p class="title">Drag files here</p>
                                                <p class="info">or, upload files from your library (Max size of 100MB)
                                                </p>
                                                
                                            </div>
                                            
              
                                    </div>
                                </div>
                                @if($errors->has("customers")) <div class="alert alert-danger mt-2">{{ $errors->first('customers') }}</div>@endif
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                    <button class="btn btn-danger submit">Import Data</button>
                </div>
            </div>
          
        </div>

        </form>

</body>

</html>

@include('layouts.footer2')


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('sidebarCollapse');
        const sidebar = document.getElementById('sidebar');

        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    });
</script>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });


    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFileUpload(files);
    });


    fileInput.addEventListener('change', () => {
        const files = fileInput.files;
        handleFileUpload(files);
    });

    // Handle file upload
    function handleFileUpload(files) {
        if (files.length > 0) {
            const file = files[0];
            if (file.size <= 100 * 1024 * 1024) { // 100MB limit
                fileName.textContent = `Uploaded: ${file.name}`;
            } else {
                alert('File size exceeds 100MB. Please upload a smaller file.');
                fileInput.value = '';
            }
        }
    }
</script>
<script>
    $(document).ready(function() {
        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('fail'))
        toastr.error("{{ Session::get('fail') }}");
        @endif
    });
</script>
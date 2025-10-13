<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('assets/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('new-assets/css/commonNew.css') }}">
    <link rel="stylesheet" href="{{ asset('new-assets/css/finance.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<style>
    .styled-tab-main {
        /* border-color: #CC0000; */
        border-top: 2px solid red;
    }

    .styled-tab-main .nav-link {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 500;

    }

    .styled-tab-main .nav-link.active {
        border-top: 2px solid red;
        color: #EE2128;
    }

    .add-new-division-btn {
        background-color: #CC0000;
        border-radius: 5px;
        height: 50px;
        border: none;
        color: #FFFFFF;
        padding-inline: 20px;
        padding-top: 10px;
        padding-bottom: 10px;
        font-family: 'Inter', sans-serif;
    }

    .header-btn {
        height: 50px;
        width: 50px;
        border: none;
        background-color: #FFF5F6;
    }

    .header-btn .fa-solid {
        color: #000000;
        height: 30px;
        width: 30px;
    }

    .action-btn {
        width: 82px;
        height: 28px;
        border-radius: 8px;
        background-color: #000000;
        color: #FFFFFF;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        border: none;
    }

    .division-table .table {
        font-family: Poppins;
        font-size: 18px;
    }

    .division-table .table thead tr {
        font-weight: 500;
    }

    .division-table .table tbody tr {
        font-weight: 400;

    }

    .offcanvas-filter {
        border-top-left-radius: 50px;
        border-bottom-left-radius: 50px;
        top: 86px !important;
        width: 35% !important;
        background-color: #FFFFFF;
        box-shadow: -3px 4px 8px 0px #0000001A !important;
        border: none !important;
        padding: 30px;
    }

    .offcanvas-title,
    .title-rest {
        font-family: 'Inter', sans-serif;
        font-size: 24.94px;
    }

    .offcanvas-title {
        font-weight: 700;
    }

    .title-rest {
        font-weight: 400 !important;
    }

    .filter-tag {
        width: fit-content;
        height: 33px;
        padding: 5px 15px 5px 15px;
        border-radius: 8px;
        box-shadow: 0px 4px 4px 0px #0000001A;
        color: #EE2128;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        margin: 10px;
        background-color: #FFF5F6;
    }


    .filter-tag button {
        background: none;
        border: none;
        cursor: pointer;
        color: #EE2128;
        margin-left: 20px;
    }

    .filter-title {
        font-family: 'Inter', sans-serif;
        font-size: 20px;
        font-weight: 500;
    }

    .radio-selection .form-check-input:checked,
    .radio-selection .form-check-input:focus {
        border-color: #cc0000 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 2px #dc3545 !important;
    }

    .filter-categories .form-check-input:focus {
        border-color: #dc3545 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 2.1px #dc354533 !important;
    }

    .filter-categories .form-check-input:checked {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .custom-input,
    .custom-input option {
        /* width: 530px; */
        height: 50px;
        padding: 14px;
        border-radius: 4px;
        border: 1px solid;
        border-color: #9D9D9D;
        background-color: #FFFFFF;
        color: #AAB6C1;
        font-family: "Poppins", sans-serif;
        font-size: 13px;
        font-weight: 400;
    }

    .custom-input::placeholder,
    .division-description::placeholder {
        color: #9D9D9D;
    }

    .outside-label {
        font-size: 16px !important;
        color: #9D9D9D;

    }

    .custom-input-label,
    .outside-label {
        font-family: "Poppins", sans-serif;
        font-size: 18px;
        font-weight: 500;
    }

    .division-action-btn {
        margin-top: 150px;
    }

    .division-action-btn .submit,
    .division-action-btn .cancel {
        width: 262px;
        height: 52px;
        padding: 15.62px;
        border-radius: 5.47px;
        color: #FFFFFF;
        font-family: "Inter", sans-serif;
        font-size: 18px;
        font-weight: 600;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .division-action-btn .submit {
        background-color: #CC0000;
    }

    .division-action-btn .cancel {
        background-color: #000000;
    }

    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 32px;
        width: 32px;
        font-family: 'Poppins';
        border-radius: 8px;
        font-size: 13px;
        margin: 3px;
        background-color: #FFFFFF;
        color: #000000;

    }

    .laravel-pagination .small {
        display: none;

    }

    .active>.page-link {
        background-color: #CC0000;
        border: #CC0000;
        color: #FFFFFF;

    }

    .access-control-checks .form-check-input {
        height: 20px;
        width: 20px;
        border-color: #D2D5DA;
        margin-right: 15px;
    }

    .access-control-checks .form-check-input:focus {
        border-color: #dc3545 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 2.1px #dc354533 !important;
    }

    .access-control-checks .form-check-input:checked {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .access-control-checks .form-check-label {
        font-family: "Inter", sans-serif;
        font-size: 20px;
        font-weight: 400;


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
        width: 450px;
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

    @media only screen and (max-width: 768px) {
        .offcanvas-filter {
            width: 80%;
        }

        .offcanvas-filter {
            width: 95% !important;
        }
    }
</style>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><img src="{{ asset('assets/images/wruth-logo.png') }}" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">


                </div>
            </div>
        </div>
    </nav>

    <div class="wrapper">

        <!-- Sidebar  -->
        <nav id="sidebar">
            <ul>
                <div class="d-flex justify-content-between">
                    <p>Admin Dashboard</p>
                    <button type="button" id="sidebarCollapse" class="btn ">
                        <i class="fas fa-align-left"></i>
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-list" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                            </svg></span>

                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>
                </div>

                <li class="active"> <a href="{{url('dashboard')}}">Dashboard</a></li>
                <li class="active"> <a href="{{url('user-managment')}}">User Management</a></li>
                <li class="active"> <a href="{{url('access-control')}}">Access Control</a></li>
                <li class="active"> <a href="{{url('customers')}}">Customers</a></li>
                <li class="active"> <a href="{{url('division-managment')}}">Division Management</a></li>
                <li class="active"> <a href="">All Collections</a></li>
                <li class="active"> <a href="{{url('return-cheques')}}">Return Cheques</a></li>
                <li class="active"> <a href="{{url('create-return-cheque')}}">Create Return Cheques</a></li>
                <li class="active"> <a href="{{url('create-reminder')}}">Reminders</a></li>
                <li class="active"> <a href="{{url('reminders')}}">All Reminders</a></li>


            </ul>

        </nav>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
                </script>

                <!-- jQuery (required for Select2) -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


                <!-- Select2 JS -->
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

                <!-- Flatpickr JS -->
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

                <script src="{{ asset('js/main-script.js') }}"></script>

                <!-- Initialize Select2 -->
                <script>
                    $(document).ready(function() {
                        $('.select2').select2({
                            placeholder: "Select options",
                            tags: true,
                            width: '100%'
                        });
                    });
                </script>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        flatpickr("#filter-date", {
                            mode: "range",
                            dateFormat: "Y-m-d",
                            allowInput: true
                        });
                    });
                </script>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        function checkScreenSize() {
                            const errorDiv = document.getElementById("screen-error");
                            const mainContent = document.querySelector(".main");

                            console.log("Screen width:", window.innerWidth);


                            if (!errorDiv || !mainContent) {
                                console.error("Elements not found!");
                                return;
                            }

                            if (window.innerWidth < 1000) {
                                errorDiv.style.display = "flex";
                                mainContent.style.display = "none";
                            } else {
                                errorDiv.style.display = "none";
                                mainContent.style.display = "flex";
                            }
                        }

                        // Run on load
                        checkScreenSize();

                        // Run on resize
                        window.addEventListener("resize", checkScreenSize);
                    });
                </script>





                <script>
                    function checkScreenSize() {
                        const errorDiv = document.getElementById("screen-error");
                        const mainContent = document.querySelector(".main");

                        if (!errorDiv || !mainContent) {
                            console.error("Elements not found!");
                            return;
                        }

                        if (window.innerWidth < 1000) {
                            errorDiv.style.display = "flex";
                            mainContent.style.display = "none";
                        } else {
                            errorDiv.style.display = "none";
                            mainContent.style.display = "flex";
                        }
                    }

                    // Run immediately
                    checkScreenSize();

                    // Run on resize
                    window.addEventListener("resize", checkScreenSize);
                </script>
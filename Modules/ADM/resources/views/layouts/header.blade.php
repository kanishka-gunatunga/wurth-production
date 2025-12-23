<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wurth Lanka | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.css">
    <link rel="stylesheet" href="{{ asset('adm_assets/css/adm_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('adm_assets/css/calander.css') }}">
    <link rel="stylesheet" href="{{ asset('adm_assets/css/custom.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>

</head>

<body>
    <!-- body wrapper -->
    <div class="mobile-wrapper">
        <!-- header -->
        <div class="header">
            <div class="d-flex flex-row justify-content-between align-items-center w-100">
                <img src="{{ asset('adm_assets/assests/wurth_logo.svg') }}" alt="Logo" class="img-fluid pwa_header_logo">
                <button class="" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                    aria-controls="offcanvasRight">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect width="25" height="25" fill="url(#pattern0_386_9231)" />
                        <defs>
                            <pattern id="pattern0_386_9231" patternContentUnits="objectBoundingBox" width="1"
                                height="1">
                                <use xlink:href="#image0_386_9231" transform="scale(0.01)" />
                            </pattern>
                            <image id="image0_386_9231" width="100" height="100"
                                xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAABYklEQVR4nO3bwU0bQRQG4KUSzyCF99wKlyglJEWASOoIEicEKCUABy6BMhAdeLYDEkdrkWPitbBXPnyfNA28f2ZO/+s6AAAAAAAAAAAAAACA7Wiz/NRK/uhLPDixtRkMMx1mOzqIZdcd9DWu+5pLJ3c4g7gaZr3+ZdT4Ioic5DK2w/i8PpASTwLJaQKp8XNEIPkikJzmuy7xPOLLyjuB5DQvpMTt2kD6w/mxQHKC7yp/D7NeG8gqlJKnrearYHJXYbz2ZX7SbWIxO8pW86yVPG81L5x8/wxKfB9muigRG4UBAAAAAAAAAPxbm32YtxrflBxyewWPVWEkvg4Fko3u3qptUvOXGlDusAaUp6PCUJTL6c6Yolyree9l5P5USZWtc9/K1vHoheQerSNY2Fnu1cLO20rblVeSuw7kctRK21+LcvSxlbyx8BnbXXqtcT3MdnQQAAAAAAAAAAAAAAAA3X/9AZIuj39RBe7yAAAAAElFTkSuQmCC" />
                        </defs>
                    </svg>

                </button>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>


        <!-- jQuery (required for Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
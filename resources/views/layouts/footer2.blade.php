</div>
<div class="bottom-bar">
    <div class="py-3">
        <div class="action-button-lg-row">
            @yield('footer-buttons')
        </div>
    </div>
</div>
</div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script src="{{ asset('new-assets/js/main-script.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const toggleButton = document.getElementById("sidebarToggle");

        // Toggle sidebar visibility
        if (toggleButton) {
            toggleButton.addEventListener("click", function() {
                sidebar.classList.toggle("collapsed");
            });
        }
    });
</script>

@stack('scripts')

</body>

</html>
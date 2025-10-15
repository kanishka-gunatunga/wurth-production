 </div>

        </div>

    </div>





    <script src="{{ asset('new-assets/js/main-script.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>




    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const toggleButton = document.getElementById("sidebarToggle");
            const sidebarLinks = document.querySelectorAll(".sidebar-list li a");

            // Toggle sidebar visibility
            if (toggleButton) {
                toggleButton.addEventListener("click", function() {
                    sidebar.classList.toggle("collapsed");
                });
            }

            // Set active class on navigation
            const currentPath = window.location.pathname;
            sidebarLinks.forEach((link) => {
                const href = link.getAttribute("href");
                if (
                    href === currentPath ||
                    (currentPath === "/" && href === "/dashboard")
                ) {
                    link.parentElement.classList.add("active");
                } else {
                    link.parentElement.classList.remove("active");
                }
            });

            // Handle dropdown functionality
            const dropdownItems = document.querySelectorAll(
                ".sidebar-list li a .dropdown-arrow"
            );
            dropdownItems.forEach((arrow) => {
                arrow.parentElement.addEventListener("click", function(e) {
                    e.preventDefault();
                    const listItem = this.parentElement;
                    listItem.classList.toggle("expanded");
                });
            });
        });
    </script>

</body>

</html>

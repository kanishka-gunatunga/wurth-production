

// add new collection page
    const searchInput = document.getElementById('searchInput');
    const searchDropdown = document.getElementById('searchDropdown');

    const items = ['Apple', 'Banana', 'Cherry', 'Date', 'Grape', 'Mango', 'Orange', 'Pineapple', 'Strawberry'];

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        searchDropdown.innerHTML = '';

        if (query) {
            const filteredItems = items.filter(item => item.toLowerCase().includes(query));
            if (filteredItems.length > 0) {
                filteredItems.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'search-item';
                    div.textContent = item;
                    div.addEventListener('click', function() {
                        searchInput.value = item;
                        searchDropdown.classList.remove('show');
                    });
                    searchDropdown.appendChild(div);
                });
                searchDropdown.classList.add('show');
            } else {
                searchDropdown.classList.remove('show');
            }
        } else {
            searchDropdown.classList.remove('show');
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchDropdown.contains(e.target) && e.target !== searchInput) {
            searchDropdown.classList.remove('show');
        }
    });



    // Cash deposit data
    const addNewPaymentTableData = [{
            invoiceNumber: "2024-06-01",
            customerName: "Cash",
            invoiceDate: "ADM1001",
            totalInvoiceAmount: 12000,
            outstandingBalance: 1500.00,
            outstandingDate: "2024-06-15",
            additionalNotes: "Inquiry ID : Qwdff23ehjbjhbsfd81222bhj"

        },

        {
            invoiceNumber: "2024-06-01",
            customerName: "Cash",
            invoiceDate: "ADM1001",
            totalInvoiceAmount: 12000,
            outstandingBalance: 1500.00,
            outstandingDate: "2024-06-15",
            additionalNotes: "Inquiry ID : Qwdff23ehjbjhbsfd81222bhj"

        },

        {
            invoiceNumber: "2024-06-01",
            customerName: "Cash",
            invoiceDate: "ADM1001",
            totalInvoiceAmount: 12000,
            outstandingBalance: 1500.00,
            outstandingDate: "2024-06-15",
            additionalNotes: "Inquiry ID : Qwdff23ehjbjhbsfd81222bhj"

        },

        {
            invoiceNumber: "2024-06-01",
            customerName: "Cash",
            invoiceDate: "ADM1001",
            totalInvoiceAmount: 12000,
            outstandingBalance: 1500.00,
            outstandingDate: "2024-06-15",
            additionalNotes: "Inquiry ID : Qwdff23ehjbjhbsfd81222bhj"

        },

    ];

    const rowsPerPage = 10;
    const currentPages = {
        cashDeposite: 1
    }; // track pages separately

    // Table render function
    function renderTable(tableId, data, page) {
        const tableBody = document.getElementById(`addNewPaymentTableBody`);
        tableBody.innerHTML = '';

        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = Math.min(startIndex + rowsPerPage, data.length);

        for (let i = startIndex; i < endIndex; i++) {
            const row = `
                <tr>
                    <td>${data[i].invoiceNumber}</td>
                    <td>${data[i].customerName}</td>
                    <td>${data[i].invoiceDate}</td>
                    <td>${data[i].totalInvoiceAmount.toFixed(2)}</td>
                    <td>${data[i].outstandingBalance.toFixed(2)}</td>
                    <td>${data[i].outstandingDate}</td>
                    <td>${data[i].additionalNotes}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        }
    }

    // Pagination render
    function renderPagination(tableId, data) {
        const pagination = document.getElementById(`addNewPaymentPagination`);
        pagination.innerHTML = '';

        const totalPages = Math.ceil(data.length / rowsPerPage);
        const currentPage = currentPages[tableId];

        // Prev button
        pagination.innerHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage('${tableId}', ${currentPage - 1})">Prev</a>
            </li>
        `;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage('${tableId}', ${i})">${i}</a>
                </li>
            `;
        }

        // Next button
        pagination.innerHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage('${tableId}', ${currentPage + 1})">Next</a>
            </li>
        `;
    }

    // Page change
    function changePage(tableId, page) {
        const data = getTableData(tableId);
        const totalPages = Math.ceil(data.length / rowsPerPage);

        if (page < 1 || page > totalPages) return;
        currentPages[tableId] = page;

        renderTable(tableId, data, page);
        renderPagination(tableId, data);
    }

    // Helper to get data by tableId
    function getTableData(tableId) {
        if (tableId === 'addNewPaymentTable') return addNewPaymentTableData;
        return [];
    }

    // Initial load after page ready
    window.onload = function() {
        changePage('addNewPaymentTable', 1);
    };

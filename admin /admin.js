document.addEventListener('DOMContentLoaded', function() {
    loadRequests();
});

// 1. Fetch data from PHP and build the table
function loadRequests() {
    fetch('get_request.php')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('requestsTableBody');
        tbody.innerHTML = ''; // Clear existing rows
        
        let total = 0, pending = 0, approved = 0;

        data.forEach(row => {
            total++;
            if(row.status === 'Pending') pending++;
            if(row.status === 'Approved') approved++;

            // Create the row
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.request_id}</td>
                <td>${row.name}</td>
                <td>${row.aid_type}</td>
                <td>
                    <select class="status-dropdown status-${row.status}" onchange="updateStatus(${row.request_id}, this)">
                        <option value="Pending" ${row.status === 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value="Approved" ${row.status === 'Approved' ? 'selected' : ''}>Approved</option>
                        <option value="Delivered" ${row.status === 'Delivered' ? 'selected' : ''}>Delivered</option>
                    </select>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Update the top summary cards
        document.getElementById('totalCount').innerText = total;
        document.getElementById('pendingCount').innerText = pending;
        document.getElementById('approvedCount').innerText = approved;
    })
    .catch(error => console.error("Error loading data:", error));
}

// 2. Send the new status to the server when changed
function updateStatus(requestId, selectElement) {
    const newStatus = selectElement.value;
    
    // Update the color class immediately for good UX
    selectElement.className = `status-dropdown status-${newStatus}`;

    // Send the update to PHP
    const formData = new FormData();
    formData.append('request_id', requestId);
    formData.append('status', newStatus);

    fetch('update_request.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== 'success') {
            alert("Error updating status: " + data.message);
            loadRequests(); // Reload if failed
        } else {
            // Update the summary numbers without reloading the page
            loadRequests(); 
        }
    })
    .catch(error => alert("Server Error updating status."));
}
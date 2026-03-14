document.addEventListener('DOMContentLoaded', loadRequests);

function loadRequests() {
    fetch('get_request.php')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('requestsTableBody');
        tbody.innerHTML = ''; 
        
        let total = 0, pending = 0, approved = 0, rejected = 0;

        data.forEach(row => {
            total++;
            if(row.status === 'Pending') pending++;
            if(row.status === 'Approved') approved++;
            if(row.status === 'Rejected') rejected++;

            const dateOptions = { year: 'numeric', month: 'short', day: 'numeric' };
            const formattedDate = new Date(row.created_at).toLocaleDateString('en-US', dateOptions);

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.id}</td> 
                <td>${formattedDate}</td> 
                <td>${row.contact_person}</td> 
                <td>${row.relief_type}</td>
                <td>${row.divisional_secretariat}</td>
                <td>
                    <select class="status-dropdown status-${row.status}" onchange="updateStatus(${row.id}, this)">
                        <option value="Pending" ${row.status === 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value="Approved" ${row.status === 'Approved' ? 'selected' : ''}>Approved</option>
                        <option value="Delivered" ${row.status === 'Delivered' ? 'selected' : ''}>Delivered</option>
                        <option value="Rejected" ${row.status === 'Rejected' ? 'selected' : ''}>Rejected</option>
                    </select>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('totalCount').innerText = total;
        document.getElementById('pendingCount').innerText = pending;
        document.getElementById('approvedCount').innerText = approved;
        document.getElementById('rejectedCount').innerText = rejected;
    })
    .catch(err => console.error("Load Error:", err));
}

function updateStatus(requestId, selectElement) {
    const newStatus = selectElement.value;
    
    // 1. Immediately update visual state so it feels snappy
    const originalClass = selectElement.className;
    selectElement.className = `status-dropdown status-${newStatus}`;

    const formData = new FormData();
    formData.append('id', requestId);
    formData.append('status', newStatus);

    fetch('update_request.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Server returned ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            console.log("Update confirmed for ID:", requestId);
            // 2. Only refresh totals, don't rebuild the whole table yet 
            // to avoid the "jumping" sensation
            updateTotalCounters(); 
        } else {
            alert("Database Error: " + data.message);
            location.reload(); // Force reload to show correct data on failure
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert("Server Error: Check if update_request.php exists and has no errors.");
        selectElement.className = originalClass; // Revert color on error
    });
}

// Helper to refresh just the top cards
function updateTotalCounters() {
    fetch('get_request.php')
    .then(res => res.json())
    .then(data => {
        let total = 0, pending = 0, approved = 0, rejected = 0;
        data.forEach(row => {
            total++;
            if(row.status === 'Pending') pending++;
            if(row.status === 'Approved') approved++;
            if(row.status === 'Rejected') rejected++;
        });
        document.getElementById('totalCount').innerText = total;
        document.getElementById('pendingCount').innerText = pending;
        document.getElementById('approvedCount').innerText = approved;
        if(document.getElementById('rejectedCount')) {
            document.getElementById('rejectedCount').innerText = rejected;
        }
    });
}
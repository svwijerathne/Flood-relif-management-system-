document.addEventListener('DOMContentLoaded', () => {
    loadRequests();
    loadUsers();
});

let allRequests = [];

function loadRequests() {
    fetch('get_request.php')
    .then(res => res.json())
    .then(data => {
        allRequests = data;
        renderTable(allRequests);
        updateCounters(allRequests);
    });
}

function renderTable(data) {
    const tbody = document.getElementById('requestsTableBody');
    tbody.innerHTML = '';
    data.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>#${row.id}</td>
            <td>${new Date(row.created_at).toLocaleDateString()}</td>
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
}

function updateStatus(id, selectElement) {
    const newStatus = selectElement.value;
    selectElement.className = `status-dropdown status-${newStatus}`; // Immediate color change

    const formData = new FormData();
    formData.append('id', id);
    formData.append('status', newStatus);

    fetch('update_request.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') loadRequests(); // Update counters
    });
}

function filterRequests() {
    const term = document.getElementById('adminSearch').value.toLowerCase();
    const area = document.getElementById('filterArea').value.toLowerCase();
    const type = document.getElementById('filterType').value.toLowerCase();

    const filtered = allRequests.filter(req => {
        // 1. Search Logic (ID or Name)
        const name = req.contact_person ? req.contact_person.toLowerCase() : "";
        const id = req.id ? req.id.toString() : "";
        const matchesSearch = name.includes(term) || id.includes(term);
        
        // 2. Area Logic
        const secretariat = req.divisional_secretariat ? req.divisional_secretariat.toLowerCase() : "";
        const matchesArea = area === "" || secretariat.includes(area);
        
        // 3. Aid Type Logic (The fix is here: force both to lowercase)
        const reliefType = req.relief_type ? req.relief_type.toLowerCase() : "";
        const matchesType = type === "" || reliefType === type;

        return matchesSearch && matchesArea && matchesType;
    });

    renderTable(filtered);
    updateCounters(filtered);
}

function loadUsers() {
    fetch('get_users.php').then(res => res.json()).then(users => {
        const tbody = document.getElementById('usersTableBody');
        tbody.innerHTML = users.map(u => `<tr><td>U-${u.id}</td><td>${u.full_name}</td><td>${u.email}</td></tr>`).join('');
    });
}

function updateCounters(data) {
    document.getElementById('totalCount').innerText = data.length;
    document.getElementById('pendingCount').innerText = data.filter(r => r.status === 'Pending').length;
    document.getElementById('approvedCount').innerText = data.filter(r => r.status === 'Approved' || r.status === "Delivered").length;
    document.getElementById('rejectedCount').innerText = data.filter(r => r.status === 'Rejected').length;
}

function showSection(section) {
    document.getElementById('dashboardSection').style.display = section === 'dashboard' ? 'block' : 'none';
    document.getElementById('usersSection').style.display = section === 'users' ? 'block' : 'none';
}
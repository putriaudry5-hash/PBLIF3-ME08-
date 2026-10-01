document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const successAlert = document.getElementById('successAlert');

    if (successAlert) {
        setTimeout(function () {
            successAlert.classList.add('hide');

            setTimeout(function () {
                successAlert.remove();
            }, 300);
        }, 3000);
    }
});

function openTenantModal() {
    showModal('tenantModal');
}

function closeTenantModal() {
    hideModal('tenantModal');
}

function openEditTenant(button) {
    document.getElementById('editTenantName').value =
        button.dataset.name || '';

    document.getElementById('editTenantOwner').value =
        button.dataset.owner || '';

    document.getElementById('editTenantEmail').value =
        button.dataset.email || '';

    document.getElementById('editTenantPhone').value =
        button.dataset.phone || '';

    document.getElementById('editTenantType').value =
        button.dataset.type || 'satuan';

    document.getElementById('editTenantLocation').value =
        button.dataset.location || '';

    document.getElementById('editTenantForm').action =
        `/admin/tenant/${button.dataset.id}`;

    showModal('editTenantModal');
}

function closeEditTenant() {
    hideModal('editTenantModal');
}

function openDetailTenant(button) {
    document.getElementById('detailTenantName').textContent =
        button.dataset.name || '-';

    document.getElementById('detailTenantOwner').textContent =
        button.dataset.owner || '-';

    document.getElementById('detailTenantEmail').textContent =
        button.dataset.email || '-';

    document.getElementById('detailTenantPhone').textContent =
        button.dataset.phone || '-';

    document.getElementById('detailTenantType').textContent =
        button.dataset.type === 'prasmanan'
            ? 'Prasmanan'
            : 'Menu Satuan';

    document.getElementById('detailTenantLocation').textContent =
        button.dataset.location || '-';

    document.getElementById('detailTenantStatus').textContent =
        button.dataset.status === 'aktif'
            ? 'Aktif'
            : 'Nonaktif';

    document.getElementById('detailTenantCreated').textContent =
        button.dataset.created || '-';

    showModal('detailTenantModal');
}

function closeDetailTenant() {
    hideModal('detailTenantModal');
}

function changeTenantStatus(button, action) {
    const message = action === 'nonaktifkan'
        ? 'Yakin ingin menonaktifkan tenant ini?'
        : 'Yakin ingin mengaktifkan kembali tenant ini?';

    if (confirm(message)) {
        button.closest('form').submit();
    }
}

function showModal(id) {
    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function hideModal(id) {
    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.remove('show');
    document.body.style.overflow = '';
}
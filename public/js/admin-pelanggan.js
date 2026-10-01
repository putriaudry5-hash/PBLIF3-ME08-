document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const sourceInput = document.getElementById('transactionSource');

    if (sourceInput) {
        selectTransactionSource(sourceInput.value || 'tenant');
    }

    calculateChange();

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

function selectTransactionSource(source) {
    const sourceInput = document.getElementById('transactionSource');
    const tenantButton = document.getElementById('sourceTenantButton');
    const kasirButton = document.getElementById('sourceKasirButton');
    const tenantField = document.getElementById('tenantField');
    const tenantSelect = document.getElementById('offlineTenant');

    if (!sourceInput || !tenantButton || !kasirButton || !tenantField || !tenantSelect) {
        return;
    }

    sourceInput.value = source;

    if (source === 'tenant') {
        tenantButton.classList.add('active');
        kasirButton.classList.remove('active');

        tenantField.style.display = 'flex';
        tenantSelect.required = true;
    } else {
        kasirButton.classList.add('active');
        tenantButton.classList.remove('active');

        tenantField.style.display = 'none';
        tenantSelect.required = false;
        tenantSelect.value = '';
    }
}

function calculateChange() {
    const totalInput = document.getElementById('offlineTotal');
    const paymentInput = document.getElementById('offlinePayment');
    const changeText = document.getElementById('offlineChange');
    const infoText = document.getElementById('offlineChangeInfo');

    if (!totalInput || !paymentInput || !changeText || !infoText) {
        return;
    }

    const total = parseInt(totalInput.value) || 0;
    const payment = parseInt(paymentInput.value) || 0;

    if (total === 0 || payment === 0) {
        changeText.textContent = 'Rp 0';
        infoText.textContent = 'Masukkan total belanja dan uang diterima.';
        infoText.classList.remove('is-error');
        return;
    }

    if (payment < total) {
        const kurang = total - payment;

        changeText.textContent = 'Rp 0';
        infoText.textContent = `Uang diterima kurang Rp ${formatRupiah(kurang)}`;
        infoText.classList.add('is-error');
        return;
    }

    const change = payment - total;

    changeText.textContent = `Rp ${formatRupiah(change)}`;

    if (change === 0) {
        infoText.textContent = 'Uang pembayaran pas.';
    } else {
        infoText.textContent = 'Kembalian pelanggan.';
    }

    infoText.classList.remove('is-error');
}

function resetOfflineForm() {
    setTimeout(function () {
        selectTransactionSource('tenant');
        calculateChange();
    }, 0);
}

function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID').format(value);
}
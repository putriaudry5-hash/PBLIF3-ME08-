document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    calculateChange();

    const transactionView = document.getElementById('transactionView');

    if (transactionView) {
        showTransactionGroup(transactionView.value);

        const params = new URLSearchParams(window.location.search);

        if (params.has('lihat')) {
            const historySection = document.getElementById(
                'offlineHistorySection'
            );

            if (historySection) {
                setTimeout(function () {
                    historySection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 200);
            }
        }
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


function calculateChange() {
    const totalInput = document.getElementById('offlineTotal');
    const paymentInput = document.getElementById('offlinePayment');
    const changeText = document.getElementById('offlineChange');
    const infoText = document.getElementById('offlineChangeInfo');

    if (
        !totalInput ||
        !paymentInput ||
        !changeText ||
        !infoText
    ) {
        return;
    }

    const total = parseInt(totalInput.value) || 0;
    const payment = parseInt(paymentInput.value) || 0;

    if (total === 0 || payment === 0) {
        changeText.textContent = 'Rp 0';
        infoText.textContent =
            'Masukkan total belanja dan uang diterima.';

        infoText.classList.remove('is-error');

        return;
    }

    if (payment < total) {
        const kurang = total - payment;

        changeText.textContent = 'Rp 0';
        infoText.textContent =
            `Uang diterima kurang Rp ${formatRupiah(kurang)}`;

        infoText.classList.add('is-error');

        return;
    }

    const change = payment - total;

    changeText.textContent =
        `Rp ${formatRupiah(change)}`;

    if (change === 0) {
        infoText.textContent =
            'Uang pembayaran pas.';
    } else {
        infoText.textContent =
            'Kembalian pelanggan.';
    }

    infoText.classList.remove('is-error');
}


function resetOfflineForm() {
    setTimeout(function () {
        calculateChange();
    }, 0);
}


function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID').format(value);
}


function showTransactionGroup(group) {
    const groups = document.querySelectorAll('.transaction-group');

    groups.forEach(function (item) {
        item.classList.toggle(
            'active',
            item.dataset.group === group
        );
    });

    const select = document.getElementById('transactionView');

    if (!select) {
        return;
    }

    const selectedOption = select.options[select.selectedIndex];
    const title = document.getElementById('selectedTransactionTitle');

    if (selectedOption && title) {
        title.textContent =
            selectedOption.dataset.title || '';
    }
}
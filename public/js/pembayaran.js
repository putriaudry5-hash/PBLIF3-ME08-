
document.addEventListener('DOMContentLoaded', function () {
    const tombol = document.getElementById('konfirmasi-pembayaran');

    if (!tombol) {
        return;
    }

    tombol.addEventListener('click', function () {
        const konfirmasi = confirm(
            'Apakah kamu ingin melanjutkan proses pembayaran ini?'
        );

        if (!konfirmasi) {
            return;
        }

        alert(
            'Metode pembayaran sudah dipilih. Integrasi payment gateway belum tersedia.'
        );
    });
});
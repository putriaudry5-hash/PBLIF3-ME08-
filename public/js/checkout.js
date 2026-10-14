
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-pembayaran');
    const metode = document.getElementById('metode-pembayaran');
    const tombol = document.getElementById('tombol-pembayaran');

    if (!form || !metode || !tombol) {
        return;
    }

    form.addEventListener('submit', function (event) {
        if (!metode.value) {
            event.preventDefault();
            alert('Silakan pilih metode pembayaran terlebih dahulu.');
            metode.focus();
            return;
        }

        tombol.disabled = true;
        tombol.textContent = 'Memproses...';
    });
});
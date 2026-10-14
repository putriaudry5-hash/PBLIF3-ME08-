window.tambahKeranjang = async function (menuIndex, hargaPilihan = null) {
    const data = {
        tenant_id: Number(window.tenantId),
        menu_index: Number(menuIndex)
    };

    if (hargaPilihan !== null) {
        data.harga_pilihan = Number(hargaPilihan);
    }

    try {
        const response = await fetch(window.urlTambahKeranjang, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                ).content
            },
            body: JSON.stringify(data)
        });

        const hasil = await response.json();

        if (!response.ok) {
            alert(hasil.message || 'Menu gagal ditambahkan.');
            return;
        }

        alert(hasil.message || 'Menu berhasil ditambahkan.');

        const jumlah = document.getElementById('cart-count');
        const total = document.getElementById('cart-total');

        if (jumlah) {
            jumlah.textContent = hasil.jumlah + ' item';
        }

        if (total) {
            total.textContent = 'Total: Rp' +
                Number(hasil.total).toLocaleString('id-ID');
        }
    } catch (error) {
        console.error('Gagal menambahkan menu:', error);
        alert('Gagal menghubungi server. Periksa koneksi dan console browser.');
    }
};

window.lihatKeranjang = function () {
    window.location.href = window.urlKeranjang;
};
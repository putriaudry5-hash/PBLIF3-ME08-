document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const container = document.getElementById('itemRows');
    const addButton = document.getElementById('addItemButton');
    const grandTotal = document.getElementById('grandTotal');

    if (!container || !addButton || !grandTotal) {
        return;
    }

    function formatRupiah(value) {
        return 'Rp ' + new Intl.NumberFormat(
            'id-ID'
        ).format(value);
    }

    function calculateTotal() {
        let total = 0;

        const rows = container.querySelectorAll(
            '.tenant-item-row'
        );

        rows.forEach(function (row) {
            const hargaInput = row.querySelector(
                '.item-price'
            );

            const jumlahInput = row.querySelector(
                '.item-quantity'
            );

            const subtotalText = row.querySelector(
                '.item-subtotal'
            );

            const harga = Number(
                hargaInput.value || 0
            );

            const jumlah = Number(
                jumlahInput.value || 0
            );

            const subtotal = harga * jumlah;

            total += subtotal;

            subtotalText.textContent =
                formatRupiah(subtotal);
        });

        grandTotal.textContent =
            formatRupiah(total);
    }

    function createItemRow() {
        const row = document.createElement('div');

        row.className = 'tenant-item-row';

        row.innerHTML = `
            <div class="tenant-field">
                <label>Nama Item</label>
                <input
                    type="text"
                    name="nama_item[]"
                    placeholder="Contoh: Ayam Goreng"
                    required
                >
            </div>

            <div class="tenant-field">
                <label>Harga</label>
                <input
                    type="number"
                    name="harga[]"
                    class="item-price"
                    min="1"
                    placeholder="10000"
                    required
                >
            </div>

            <div class="tenant-field">
                <label>Jumlah</label>
                <input
                    type="number"
                    name="jumlah[]"
                    class="item-quantity"
                    min="1"
                    max="100"
                    value="1"
                    required
                >
            </div>

            <div class="tenant-field tenant-subtotal-field">
                <label>Subtotal</label>
                <strong class="item-subtotal">
                    Rp 0
                </strong>
            </div>

            <button
                type="button"
                class="tenant-remove-item"
                aria-label="Hapus item"
            >
                <i data-lucide="trash-2"></i>
            </button>
        `;

        container.appendChild(row);

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        calculateTotal();
    }

    addButton.addEventListener(
        'click',
        function () {
            createItemRow();
        }
    );

    container.addEventListener(
        'input',
        function () {
            calculateTotal();
        }
    );

    container.addEventListener(
        'click',
        function (event) {
            const removeButton =
                event.target.closest(
                    '.tenant-remove-item'
                );

            if (!removeButton) {
                return;
            }

            const rows = container.querySelectorAll(
                '.tenant-item-row'
            );

            if (rows.length <= 1) {
                window.alert(
                    'Minimal harus ada satu item.'
                );

                return;
            }

            removeButton.closest(
                '.tenant-item-row'
            ).remove();

            calculateTotal();
        }
    );

    createItemRow();
});
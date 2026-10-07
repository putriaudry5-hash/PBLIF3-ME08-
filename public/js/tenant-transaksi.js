document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') lucide.createIcons();

    const container = document.getElementById('itemRows');
    const addButton = document.getElementById('addItemButton');
    const grandTotal = document.getElementById('grandTotal');
    const menuData = document.getElementById('tenantMenusData');
    const menus = menuData ? JSON.parse(menuData.textContent) : [];

    if (!container || !addButton || !grandTotal) return;

    if (container.dataset.initialized === 'true') return;
    container.dataset.initialized = 'true';
    container.innerHTML = '';

    function formatRupiah(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value) || 0);
    }

    function getMenu(id) {
        return menus.find(menu => String(menu.id) === String(id));
    }

    function calculateTotal() {
        let total = 0;

        container.querySelectorAll('.tenant-item-row').forEach(row => {
            const harga = Number(row.querySelector('.item-price-value')?.value || 0);
            const jumlah = Number(row.querySelector('.item-quantity')?.value || 0);
            const subtotal = harga * jumlah;

            total += subtotal;
            row.querySelector('.item-subtotal').textContent = formatRupiah(subtotal);
        });

        grandTotal.textContent = formatRupiah(total);
    }

    function updateRow(row) {
        const menuSelect = row.querySelector('.item-menu');
        const priceArea = row.querySelector('.item-price-area');
        const priceValue = row.querySelector('.item-price-value');
        const quantity = row.querySelector('.item-quantity');
        const menu = getMenu(menuSelect.value);

        priceArea.innerHTML = '';
        priceValue.value = '';
        quantity.removeAttribute('max');

        if (!menu) {
            priceArea.innerHTML = '<div class="tenant-price-display">Rp 0</div>';
            calculateTotal();
            return;
        }

        if (menu.jenis_menu === 'satuan' && menu.stok !== null) {
            quantity.max = Number(menu.stok);
            if (Number(quantity.value) > Number(menu.stok)) quantity.value = menu.stok;
        }

        if (menu.tipe_harga === 'fleksibel') {
            const select = document.createElement('select');
            select.className = 'item-flex-price';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Pilih harga / porsi';
            select.appendChild(placeholder);

            (menu.opsi_harga || []).forEach(harga => {
                const option = document.createElement('option');
                option.value = harga;
                option.textContent = formatRupiah(harga);
                select.appendChild(option);
            });

            priceArea.appendChild(select);
        } else {
            const harga = Number(menu.harga || 0);
            priceValue.value = harga;

            const display = document.createElement('div');
            display.className = 'tenant-price-display';
            display.textContent = formatRupiah(harga);
            priceArea.appendChild(display);
        }

        calculateTotal();
    }

    function createItemRow() {
        const row = document.createElement('div');
        row.className = 'tenant-item-row';

        row.innerHTML = `
            <div class="tenant-field">
                <label>Pilih Menu</label>
                <select name="menu_id[]" class="item-menu" required>
                    <option value="">Pilih menu</option>
                </select>
            </div>

            <div class="tenant-field">
                <label>Harga / Porsi</label>
                <div class="item-price-area"></div>
                <input type="hidden" name="harga_pilihan[]" class="item-price-value" value="">
            </div>

            <div class="tenant-field">
                <label>Jumlah</label>
                <input type="number" name="jumlah[]" class="item-quantity" min="1" max="100" value="1" required>
            </div>

            <div class="tenant-field tenant-subtotal-field">
                <label>Subtotal</label>
                <strong class="item-subtotal">Rp 0</strong>
            </div>

            <button type="button" class="tenant-remove-item" aria-label="Hapus item">
                <i data-lucide="trash-2"></i>
            </button>
        `;

        const menuSelect = row.querySelector('.item-menu');

        if (menus.length === 0) {
            const option = document.createElement('option');
            option.disabled = true;
            option.textContent = 'Belum ada menu tersedia';
            menuSelect.appendChild(option);
        }

        menus.forEach(menu => {
            const option = document.createElement('option');
            option.value = menu.id;

            let label = menu.nama_menu;

            if (menu.jenis_menu === 'satuan' && menu.stok !== null) {
                label += ` - Stok ${menu.stok}`;

                if (Number(menu.stok) <= 0) {
                    option.disabled = true;
                    label += ' (Habis)';
                }
            }

            option.textContent = label;
            menuSelect.appendChild(option);
        });

        container.appendChild(row);

        if (typeof lucide !== 'undefined') lucide.createIcons();
        updateRow(row);
    }

    addButton.addEventListener('click', createItemRow);

    container.addEventListener('change', function (event) {
        const row = event.target.closest('.tenant-item-row');
        if (!row) return;

        if (event.target.classList.contains('item-menu')) {
            updateRow(row);
            return;
        }

        if (event.target.classList.contains('item-flex-price')) {
            row.querySelector('.item-price-value').value = event.target.value;
            calculateTotal();
        }
    });

    container.addEventListener('input', function (event) {
        if (event.target.classList.contains('item-quantity')) calculateTotal();
    });

    container.addEventListener('click', function (event) {
        const removeButton = event.target.closest('.tenant-remove-item');
        if (!removeButton) return;

        const rows = container.querySelectorAll('.tenant-item-row');

        if (rows.length <= 1) {
            window.alert('Minimal harus ada satu item.');
            return;
        }

        removeButton.closest('.tenant-item-row').remove();
        calculateTotal();
    });

    createItemRow();
});
document.addEventListener("DOMContentLoaded", function () {

    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    const openButtons = document.querySelectorAll("[data-open-modal]");
    const closeButtons = document.querySelectorAll("[data-close-modal]");

    function openModal(id) {
        const modal = document.getElementById(id);

        if (!modal) {
            return;
        }

        modal.classList.add("show");

        document.body.style.overflow = "hidden";

        const form = modal.querySelector(".menu-form");

        if (form) {
            updateMenuForm(form);
        }
    }

    function closeModal(modal) {
        if (!modal) {
            return;
        }

        modal.classList.remove("show");

        document.body.style.overflow = "";
    }

    openButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const modalId = button.dataset.openModal;

            openModal(modalId);

        });

    });

    closeButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const modal = button.closest(".modal");

            closeModal(modal);

        });

    });

    document.addEventListener("keydown", function (event) {

        if (event.key !== "Escape") {
            return;
        }

        document
            .querySelectorAll(".modal.show")
            .forEach(function (modal) {
                closeModal(modal);
            });

    });


    /*
    |--------------------------------------------------------------------------
    | FILTER MENU
    |--------------------------------------------------------------------------
    */

    const filterButtons = document.querySelectorAll("[data-menu-filter]");
    const menuCards = document.querySelectorAll(".menu-card");

    filterButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const filter = button.dataset.menuFilter;

            filterButtons.forEach(function (item) {
                item.classList.remove("active");
            });

            button.classList.add("active");

            menuCards.forEach(function (card) {

                const type = card.dataset.menuType;

                if (
                    filter === "semua" ||
                    type === filter
                ) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }

            });

        });

    });


    /*
    |--------------------------------------------------------------------------
    | FORM MENU
    |--------------------------------------------------------------------------
    */

    const forms = document.querySelectorAll(".menu-form");

    forms.forEach(function (form) {

        const jenisSelect = form.querySelector(".jenis-menu");
        const tipeSelect = form.querySelector(".tipe-harga");

        if (jenisSelect) {

            jenisSelect.addEventListener("change", function () {
                updateMenuForm(form);
            });

        }

        if (tipeSelect) {

            tipeSelect.addEventListener("change", function () {
                updateMenuForm(form);
            });

        }

        updateMenuForm(form);

    });


    function updateMenuForm(form) {

        const jenisSelect = form.querySelector(".jenis-menu");
        const tipeSelect = form.querySelector(".tipe-harga");

        const normalPriceField =
            form.querySelector(".normal-price-field");

        const flexiblePriceField =
            form.querySelector(".flexible-price-field");

        const stockField =
            form.querySelector(".stock-field");

        if (!jenisSelect || !tipeSelect) {
            return;
        }

        const jenis = jenisSelect.value;

        /*
        |--------------------------------------------------------------------------
        | MENU SATUAN
        |--------------------------------------------------------------------------
        */

        if (jenis === "satuan") {

            tipeSelect.value = "tetap";

            Array.from(tipeSelect.options).forEach(function (option) {

                if (option.value === "tetap") {
                    option.disabled = false;
                }

                if (
                    option.value === "fleksibel" ||
                    option.value === "per_potong"
                ) {
                    option.disabled = true;
                }

            });

        }

        /*
        |--------------------------------------------------------------------------
        | PRASMANAN
        |--------------------------------------------------------------------------
        */

        if (jenis === "prasmanan") {

            Array.from(tipeSelect.options).forEach(function (option) {

                if (option.value === "tetap") {
                    option.disabled = true;
                }

                if (
                    option.value === "fleksibel" ||
                    option.value === "per_potong"
                ) {
                    option.disabled = false;
                }

            });

            if (tipeSelect.value === "tetap") {
                tipeSelect.value = "fleksibel";
            }

        }


        const tipe = tipeSelect.value;


        /*
        |--------------------------------------------------------------------------
        | HARGA NORMAL
        |--------------------------------------------------------------------------
        */

        if (
            tipe === "tetap" ||
            tipe === "per_potong"
        ) {

            if (normalPriceField) {
                normalPriceField.style.display = "";
            }

            if (flexiblePriceField) {
                flexiblePriceField.style.display = "none";
            }

        }


        /*
        |--------------------------------------------------------------------------
        | HARGA FLEKSIBEL
        |--------------------------------------------------------------------------
        */

        if (tipe === "fleksibel") {

            if (normalPriceField) {
                normalPriceField.style.display = "none";
            }

            if (flexiblePriceField) {
                flexiblePriceField.style.display = "";
            }

        }


        /*
        |--------------------------------------------------------------------------
        | STOK
        |--------------------------------------------------------------------------
        */

        if (stockField) {

            if (jenis === "satuan") {
                stockField.style.display = "";
            } else {
                stockField.style.display = "none";
            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH PILIHAN HARGA
    |--------------------------------------------------------------------------
    */

    document.addEventListener("click", function (event) {

        const addButton =
            event.target.closest(".add-price-option");

        if (addButton) {

            const form =
                addButton.closest(".menu-form");

            const list =
                form.querySelector(".price-options-list");

            if (!list) {
                return;
            }

            const row = document.createElement("div");

            row.className = "price-option-row";

            row.innerHTML = `
                <div class="money-input">
                    <span>Rp</span>

                    <input
                        type="number"
                        name="opsi_harga[]"
                        min="1"
                        placeholder="3000"
                    >
                </div>

                <button
                    type="button"
                    class="remove-price-option"
                >
                    ×
                </button>
            `;

            list.appendChild(row);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS PILIHAN HARGA
        |--------------------------------------------------------------------------
        */

        const removeButton =
            event.target.closest(".remove-price-option");

        if (removeButton) {

            const row =
                removeButton.closest(".price-option-row");

            const list =
                removeButton.closest(".price-options-list");

            if (!row || !list) {
                return;
            }

            const rows =
                list.querySelectorAll(".price-option-row");

            if (rows.length === 1) {

                const input =
                    row.querySelector("input");

                if (input) {
                    input.value = "";
                }

                return;
            }

            row.remove();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI HAPUS
    |--------------------------------------------------------------------------
    */

    const deleteForms =
        document.querySelectorAll(".delete-menu-form");

    deleteForms.forEach(function (form) {

        form.addEventListener("submit", function (event) {

            const confirmed = window.confirm(
                "Yakin ingin menghapus menu ini?"
            );

            if (!confirmed) {
                event.preventDefault();
            }

        });

    });

});
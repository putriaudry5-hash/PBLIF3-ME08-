<div class="modal-overlay" id="editTenantModal">

    <div class="modal-box">

        <div class="modal-header">

            <div>
                <h2>Edit Tenant</h2>
                <p>Ubah informasi akun tenant.</p>
            </div>

            <button
                type="button"
                class="modal-close"
                onclick="closeEditTenant()"
            >
                &times;
            </button>

        </div>


        <form
            id="editTenantForm"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="form-group">

                <label for="editNamaTenant">
                    Nama Tenant
                </label>

                <input
                    type="text"
                    id="editNamaTenant"
                    name="nama_tenant"
                    required
                >

            </div>


            <div class="form-group">

                <label for="editEmailTenant">
                    Email
                </label>

                <input
                    type="email"
                    id="editEmailTenant"
                    name="email"
                    required
                >

            </div>


            <div class="modal-actions">

                <button
                    type="button"
                    class="btn-cancel"
                    onclick="closeEditTenant()"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn-save"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>
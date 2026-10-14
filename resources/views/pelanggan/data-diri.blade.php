<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Diri - KantinKita</title>

    <link rel="stylesheet"
          href="{{ asset('css/data-diri.css') }}?v={{ filemtime(public_path('css/data-diri.css')) }}">
</head>
<body>
    <header class="topbar">
        <a href="{{ route('pelanggan.dashboard') }}" class="brand">
            Kantin<span>Kita</span>
        </a>

        <div class="account">
            <div class="account-avatar">E</div>
            <div class="account-info">
                <strong>Enting</strong>
                <span>Pelanggan</span>
            </div>
        </div>
    </header>

    <main class="container">
        <a href="{{ route('pelanggan.profil') }}" class="back">
            ← Kembali ke Profil
        </a>

        <div class="page-header">
            <h1>Data Diri</h1>
            <p>Lihat dan ubah informasi pribadi Anda.</p>
        </div>

        <div class="form-card">
            @if (session('success'))
                <div class="notice">{{ session('success') }}</div>
            @endif

            <form>
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="Enting Renta"
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="enting@gmail.com"
                    >
                </div>

                <div class="form-group">
                    <label for="telepon">Nomor Telepon</label>
                    <input
                        type="tel"
                        id="telepon"
                        name="telepon"
                        placeholder="Masukkan nomor telepon"
                    >
                </div>

                <button type="button" class="btn-save"
                        onclick="alert('Form ini belum terhubung ke database.')">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </main>
</body>
</html>

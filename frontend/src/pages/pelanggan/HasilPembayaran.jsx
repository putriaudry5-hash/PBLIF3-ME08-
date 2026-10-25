import { useEffect } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import "../../styles/HasilPembayaran.css";

const KEY_PESANAN = "kantinkita_pesanan";

function bacaPesanan() {
  try {
    const data = localStorage.getItem(KEY_PESANAN);
    const daftar = data ? JSON.parse(data) : [];
    return Array.isArray(daftar) ? daftar : [];
  } catch {
    return [];
  }
}

function HasilPembayaran() {
  const navigate = useNavigate();
  const location = useLocation();

  const dataPembayaran = location.state || {};

  const {
    items = [],
    nomorSimulasi = "DEMO-82793996",
    metode = "QRIS",
    totalItem,
    totalHarga = 0,
    namaTenant,
    tenant,
    tenantName,
  } = dataPembayaran;

  const formatRupiah = (angka) =>
    new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      maximumFractionDigits: 0,
    }).format(Number(angka) || 0);

  const jumlahItem =
    totalItem ??
    items.reduce(
      (total, item) =>
        total +
        Number(item.quantity ?? item.jumlah ?? item.qty ?? 1),
      0
    );

  // Simpan pesanan setelah pembayaran simulasi berhasil.
  useEffect(() => {
    if (
      !Array.isArray(dataPembayaran.items) ||
      dataPembayaran.items.length === 0 ||
      !dataPembayaran.nomorSimulasi
    ) {
      return;
    }

    try {
      const daftarPesanan = bacaPesanan();

      // Hindari menyimpan nomor pesanan yang sama dua kali.
      const sudahAda = daftarPesanan.some(
        (pesanan) =>
          pesanan.nomorSimulasi ===
          dataPembayaran.nomorSimulasi
      );

      if (sudahAda) return;

      const jumlah =
        dataPembayaran.totalItem ??
        dataPembayaran.items.reduce(
          (total, item) =>
            total +
            Number(item.quantity ?? item.jumlah ?? item.qty ?? 1),
          0
        );

      const pesananBaru = {
        id: dataPembayaran.nomorSimulasi,
        nomorSimulasi: dataPembayaran.nomorSimulasi,
        items: dataPembayaran.items,
        namaTenant:
          dataPembayaran.namaTenant ||
          dataPembayaran.tenant?.nama ||
          dataPembayaran.tenantName ||
          "",
        metode: dataPembayaran.metode || "QRIS",
        totalItem: jumlah,
        totalHarga: Number(dataPembayaran.totalHarga) || 0,
        status: "Pembayaran Berhasil",
        statusIndex: 0,
        tanggal: new Date().toISOString(),
      };

      localStorage.setItem(
        KEY_PESANAN,
        JSON.stringify([pesananBaru, ...daftarPesanan])
      );
    } catch (error) {
      console.error("Gagal menyimpan pesanan:", error);
    }
  }, [dataPembayaran]);

  function lihatDetailPesanan() {
    navigate("/detail-pesanan", {
      state: {
        items,
        nomorSimulasi,
        metode,
        totalItem: jumlahItem,
        totalHarga,
        namaTenant,
        tenant,
        tenantName,
      },
    });
  }

  return (
    <div className="hasil-pembayaran-page">
      <div className="hasil-pembayaran-card">
        <div className="pembayaran-header">
          <div className="pembayaran-icon">✓</div>

          <h1>Pembayaran Berhasil!</h1>

          <p>
            Ini hanya hasil simulasi, bukan bukti pembayaran
            sungguhan. Integrasi PayKita belum aktif.
          </p>
        </div>

        <div className="pembayaran-informasi">
          <div className="pembayaran-row">
            <span>Nomor simulasi</span>
            <strong>{nomorSimulasi}</strong>
          </div>

          <div className="pembayaran-row">
            <span>Metode</span>
            <strong>{metode}</strong>
          </div>

          <div className="pembayaran-row">
            <span>Total item</span>
            <strong>{jumlahItem} item</strong>
          </div>

          <div className="pembayaran-row">
            <span>Total simulasi</span>
            <strong>{formatRupiah(totalHarga)}</strong>
          </div>

          <div className="pembayaran-row">
            <span>Status</span>
            <strong className="status-pembayaran">
              SIMULASI BERHASIL
            </strong>
          </div>
        </div>

        <div className="pembayaran-actions">
          <button
            type="button"
            className="btn-pembayaran"
            onClick={() => navigate("/keranjang")}
          >
            ← Kembali ke Keranjang
          </button>

          <button
            type="button"
            className="btn-pembayaran"
            onClick={lihatDetailPesanan}
          >
            Lihat Detail Pesanan →
          </button>
        </div>
      </div>
    </div>
  );
}

export default HasilPembayaran;

import { useEffect, useRef, useState } from "react";
import {
  CalendarDays, ReceiptText, Wallet, CreditCard, Banknote,
  ChartNoAxesCombined, Filter, RotateCcw, ChevronLeft,
  ChevronRight, Clock3, RefreshCw
} from "lucide-react";
import { useTenants } from "../../context/useTenants.js";
import { apiRequest } from "../../services/api.js";
import "../../styles/Laporan.css";

const labels = {
  harian: "Harian",
  mingguan: "Mingguan",
  bulanan: "Bulanan",
  tahunan: "Tahunan"
};

const rupiah = (value) =>
  `Rp ${Number(value || 0).toLocaleString("id-ID")}`;

const dateKey = (value) => String(value || "").slice(0, 10);
const toDate = (value) => new Date(`${value}T00:00:00`);

function toISO(date) {
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, "0");
  const d = String(date.getDate()).padStart(2, "0");
  return `${y}-${m}-${d}`;
}

function waktuWIB() {
  const parts = new Intl.DateTimeFormat("en-GB", {
    timeZone: "Asia/Jakarta",
    year: "numeric", month: "2-digit", day: "2-digit",
    hour: "2-digit", minute: "2-digit", second: "2-digit",
    hourCycle: "h23"
  }).formatToParts(new Date());

  const get = (type) =>
    parts.find((part) => part.type === type)?.value || "00";

  return {
    tanggal: `${get("year")}-${get("month")}-${get("day")}`,
    jam: `${get("hour")}:${get("minute")}:${get("second")}`
  };
}

const today = () => waktuWIB().tanggal;

function formatDate(value) {
  if (!value || isNaN(toDate(value).getTime())) return "-";
  return toDate(value).toLocaleDateString("id-ID", {
    day: "2-digit", month: "long", year: "numeric"
  });
}

function weekStart(value) {
  const date = toDate(value);
  date.setDate(date.getDate() - ((date.getDay() + 6) % 7));
  return date;
}

function weekEnd(value) {
  const date = weekStart(value);
  date.setDate(date.getDate() + 6);
  return date;
}

function periodText(periode, tanggal) {
  if (periode === "mingguan") {
    return `${formatDate(toISO(weekStart(tanggal)))} - ${formatDate(toISO(weekEnd(tanggal)))}`;
  }
  if (periode === "bulanan") {
    return toDate(tanggal).toLocaleDateString("id-ID", {
      month: "long", year: "numeric"
    });
  }
  if (periode === "tahunan") return tanggal.slice(0, 4);
  return formatDate(tanggal);
}

function WeeklyCalendar({ value, onChange }) {
  const [open, setOpen] = useState(false);
  const [month, setMonth] = useState(() => {
    const selected = toDate(value);
    return new Date(selected.getFullYear(), selected.getMonth(), 1);
  });
  const wrapper = useRef(null);
  const monday = weekStart(value);
  const sunday = weekEnd(value);
  const first = new Date(month.getFullYear(), month.getMonth(), 1);
  const calendarStart = new Date(first);

  calendarStart.setDate(first.getDate() - ((first.getDay() + 6) % 7));

  const days = Array.from({ length: 42 }, (_, index) => {
    const day = new Date(calendarStart);
    day.setDate(calendarStart.getDate() + index);
    return day;
  });

  useEffect(() => {
    const close = (event) => {
      if (wrapper.current && !wrapper.current.contains(event.target)) {
        setOpen(false);
      }
    };
    const escape = (event) => {
      if (event.key === "Escape") setOpen(false);
    };
    document.addEventListener("mousedown", close);
    document.addEventListener("keydown", escape);
    return () => {
      document.removeEventListener("mousedown", close);
      document.removeEventListener("keydown", escape);
    };
  }, []);

  const selectDay = (day) => {
    onChange(toISO(day));
    setOpen(false);
  };

  const changeMonth = (offset) => {
    setMonth(new Date(month.getFullYear(), month.getMonth() + offset, 1));
  };

  const toggleCalendar = () => {
    if (!open) {
      const selected = toDate(value);
      setMonth(new Date(selected.getFullYear(), selected.getMonth(), 1));
    }
    setOpen(!open);
  };

  return (
    <div className="laporan-weekly" ref={wrapper}>
      <button type="button" className="laporan-weekly-input"
        onClick={toggleCalendar} aria-expanded={open}>
        <span>{periodText("mingguan", value)}</span>
        <CalendarDays size={17} />
      </button>

      {open && (
        <div className="laporan-weekly-popup">
          <div className="laporan-weekly-header">
            <button type="button" onClick={() => changeMonth(-1)}
              aria-label="Bulan sebelumnya">
              <ChevronLeft size={18} />
            </button>
            <strong>
              {month.toLocaleDateString("id-ID", {
                month: "long", year: "numeric"
              })}
            </strong>
            <button type="button" onClick={() => changeMonth(1)}
              aria-label="Bulan berikutnya">
              <ChevronRight size={18} />
            </button>
          </div>

          <div className="laporan-weekly-grid">
            {["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"].map((day) => (
              <span className="laporan-weekday" key={day}>{day}</span>
            ))}
            {days.map((day) => {
              const key = toISO(day);
              const selected = day >= monday && day <= sunday;
              const currentMonth = day.getMonth() === month.getMonth();

              return (
                <button key={key} type="button"
                  className={[
                    "laporan-weekday-btn",
                    selected ? "selected" : "",
                    key === value ? "picked" : "",
                    !currentMonth ? "outside" : ""
                  ].join(" ")}
                  onClick={() => selectDay(day)}
                  aria-pressed={selected}>
                  {day.getDate()}
                </button>
              );
            })}
          </div>

          <div className="laporan-weekly-footer">
            <span>Senin - Minggu (7 hari)</span>
            <button type="button" onClick={() => selectDay(toDate(today()))}>
              Minggu Ini
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

function filterPeriod(items, periode, tanggal, tenant) {
  const selected = toDate(tanggal);
  const start = weekStart(tanggal);
  const end = new Date(start);
  end.setDate(start.getDate() + 7);

  return items.filter((item) => {
    const current = toDate(dateKey(item.tanggal));
    if (isNaN(current.getTime())) return false;
    if (tenant && String(item.tenant_id) !== String(tenant)) return false;
    if (periode === "harian") return dateKey(item.tanggal) === tanggal;
    if (periode === "mingguan") return current >= start && current < end;
    if (periode === "bulanan") {
      return current.getMonth() === selected.getMonth() &&
        current.getFullYear() === selected.getFullYear();
    }
    return current.getFullYear() === selected.getFullYear();
  });
}

function makeChartData(items, periode, tanggal) {
  const selected = toDate(tanggal);
  let points = [];

  if (periode === "harian") {
    points = Array.from({ length: 24 }, (_, i) => ({
      label: `${String(i).padStart(2, "0")}:00`, key: i, value: 0
    }));
  } else if (periode === "mingguan") {
    const start = weekStart(tanggal);
    points = Array.from({ length: 7 }, (_, i) => {
      const day = new Date(start);
      day.setDate(start.getDate() + i);
      return {
        label: day.toLocaleDateString("id-ID", { weekday: "short" }),
        key: toISO(day), value: 0
      };
    });
  } else if (periode === "bulanan") {
    const count = new Date(
      selected.getFullYear(), selected.getMonth() + 1, 0
    ).getDate();
    points = Array.from({ length: count }, (_, i) => ({
      label: String(i + 1), key: i + 1, value: 0
    }));
  } else {
    points = Array.from({ length: 12 }, (_, i) => ({
      label: new Date(2026, i, 1).toLocaleDateString("id-ID", {
        month: "short"
      }),
      key: i, value: 0
    }));
  }

  items.filter((item) => item.pembayaran === "Lunas").forEach((item) => {
    const date = toDate(dateKey(item.tanggal));
    let key;

    if (periode === "harian") {
      key = Number(String(item.waktu || "00:00").slice(0, 2));
    } else if (periode === "mingguan") {
      key = dateKey(item.tanggal);
    } else if (periode === "bulanan") {
      key = date.getDate();
    } else {
      key = date.getMonth();
    }

    const point = points.find((p) => p.key === key);
    if (point) point.value += Number(item.total || 0);
  });

  return points;
}

function GrafikPendapatan({ data, periode }) {
  const max = Math.max(...data.map((item) => item.value), 1);
  const left = 70, right = 850, top = 20, bottom = 205;

  const coords = data.map((item, index) => ({
    x: left + index / Math.max(data.length - 1, 1) * (right - left),
    y: bottom - item.value / max * (bottom - top)
  }));

  const line = coords.map((point) => `${point.x},${point.y}`).join(" ");
  const interval = periode === "harian" ? 4 :
    periode === "bulanan" ? 5 : 1;

  return (
    <div className="laporan-line-chart">
      <svg viewBox="0 0 900 265" role="img"
        aria-label={`Grafik pendapatan ${labels[periode]}`}>
        {[0, 1, 2, 3, 4].map((i) => {
          const y = top + i * (bottom - top) / 4;
          return (
            <g key={i}>
              <line x1={left} y1={y} x2={right} y2={y}
                stroke="#F1E8DE" strokeDasharray="4 4" />
              <text x="60" y={y + 4} textAnchor="end"
                fontSize="10" fill="#938B97">
                {Math.round(max * (4 - i) / 4).toLocaleString("id-ID")}
              </text>
            </g>
          );
        })}
        <polyline points={line} fill="none" stroke="#CD1C18"
          strokeWidth="2.7" strokeLinejoin="round" strokeLinecap="round" />
        {coords.map((point, index) => (
          <g key={index}>
            {data.length <= 12 && (
              <circle cx={point.x} cy={point.y} r="4"
                fill="#CD1C18" stroke="#FFFFFF" strokeWidth="2" />
            )}
            {(index % interval === 0 || index === data.length - 1) && (
              <text x={point.x} y="233" textAnchor="middle"
                fontSize="11" fill="#77717D">
                {data[index].label}
              </text>
            )}
          </g>
        ))}
      </svg>
      <p>Nominal pendapatan dalam rupiah (Rp).</p>
    </div>
  );
}

function Laporan() {
  const { tenants = [], error: tenantError } = useTenants();
  const [sekarang, setSekarang] = useState(waktuWIB);
  const [periode, setPeriode] = useState("harian");
  const [tanggal, setTanggal] = useState(today);
  const [tenant, setTenant] = useState("");
  const [applied, setApplied] = useState(() => ({
    periode: "harian", tanggal: today(), tenant: ""
  }));
  const [transactions, setTransactions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [refreshKey, setRefreshKey] = useState(0);

  useEffect(() => {
    const interval = setInterval(() => setSekarang(waktuWIB()), 1000);
    return () => clearInterval(interval);
  }, []);

  useEffect(() => {
    let active = true;

    const fetchReport = async () => {
      try {
        const result = await apiRequest("/admin/reports");
        if (!active) return;
        const data = Array.isArray(result)
          ? result
          : Array.isArray(result?.data)
            ? result.data
            : [];
        setTransactions(data);
        setError("");
      } catch (err) {
        if (!active) return;
        setError(err.message || "Gagal memuat laporan.");
        setTransactions([]);
      } finally {
        if (active) setLoading(false);
      }
    };

    fetchReport();
    return () => { active = false; };
  }, [refreshKey]);

  const changePeriod = (value) => {
    setPeriode(value);
    setApplied({ periode: value, tanggal, tenant });
  };

  const resetReport = () => {
    const next = { periode: "harian", tanggal: today(), tenant: "" };
    setPeriode(next.periode);
    setTanggal(next.tanggal);
    setTenant(next.tenant);
    setApplied(next);
  };

  const refreshReport = () => {
    setLoading(true);
    setRefreshKey((prev) => prev + 1);
  };

  const filtered = filterPeriod(
    transactions, applied.periode, applied.tanggal, applied.tenant
  );
  const paid = filtered.filter((item) => item.pembayaran === "Lunas");
  const online = paid.filter((item) => item.jenis === "Online");
  const offline = paid.filter((item) => item.jenis === "Offline");
  const total = paid.reduce((sum, item) => sum + Number(item.total || 0), 0);
  const onlineTotal = online.reduce((sum, item) => sum + Number(item.total || 0), 0);
  const offlineTotal = offline.reduce((sum, item) => sum + Number(item.total || 0), 0);
  const chartData = makeChartData(filtered, applied.periode, applied.tanggal);

  const stats = [
    {
      title: "Total Transaksi",
      value: `${filtered.length} Transaksi`,
      icon: ReceiptText,
      color: "yellow"
    },
    {
      title: "Total Pendapatan",
      value: rupiah(total),
      icon: Wallet,
      color: "red"
    },
    {
      title: "Pendapatan Online",
      value: rupiah(onlineTotal),
      detail: `${online.length} transaksi`,
      icon: CreditCard,
      color: "yellow"
    },
    {
      title: "Pendapatan Offline",
      value: rupiah(offlineTotal),
      detail: `${offline.length} transaksi`,
      icon: Banknote,
      color: "green"
    }
  ];

  return (
    <div className="laporan-page">
      <div className="laporan-heading">
        <h1>Laporan</h1>
        <p>Pantau transaksi dan pendapatan seluruh tenant.</p>
      </div>

      {error && (
        <div className="laporan-panel" style={{ padding: 16 }}
          role="alert">
          <p>Gagal memuat laporan: {error}</p>
          <button type="button" onClick={refreshReport}>
            <RefreshCw size={15} /> Coba Lagi
          </button>
        </div>
      )}

      {tenantError && (
        <div className="laporan-panel" style={{ padding: 16 }}
          role="alert">
          <p>Gagal memuat daftar tenant: {tenantError}</p>
        </div>
      )}

      <section className="laporan-panel laporan-filter-panel">
        <div className="laporan-panel-header">
          <div>
            <h2>Periode Laporan</h2>
            <p>Pilih laporan harian, mingguan, bulanan, atau tahunan.</p>
          </div>
          <div style={{
            display: "flex", alignItems: "center", gap: 8,
            flexWrap: "wrap", justifyContent: "flex-end"
          }}>
            <span className="laporan-date-badge">
              <Clock3 size={14} /> {sekarang.jam} WIB
            </span>
            <span className="laporan-date-badge">
              <CalendarDays size={14} /> {formatDate(sekarang.tanggal)}
            </span>
          </div>
        </div>

        <form className="laporan-filter-form" onSubmit={(event) => {
          event.preventDefault();
          setApplied({ periode, tanggal, tenant });
        }}>
          <div className="laporan-period-tabs">
            {Object.entries(labels).map(([key, label]) => (
              <button key={key} type="button"
                className={periode === key ? "active" : ""}
                onClick={() => changePeriod(key)}>
                <CalendarDays size={15} /> {label}
              </button>
            ))}
          </div>

          <div className="laporan-filter-grid">
            <div className="laporan-field">
              <label htmlFor={
                periode === "mingguan" ? undefined : "laporan-date"
              }>
                {periode === "harian" ? "Tanggal" :
                  periode === "mingguan" ? "Pilih Minggu" :
                  periode === "bulanan" ? "Bulan" : "Tahun"}
              </label>

              {periode === "mingguan" ? (
                <WeeklyCalendar value={tanggal} onChange={setTanggal} />
              ) : periode === "tahunan" ? (
                <input id="laporan-date" type="number"
                  min="2000" max="2100"
                  value={tanggal.slice(0, 4)}
                  onChange={(event) =>
                    setTanggal(`${event.target.value}-01-01`)}
                  required />
              ) : (
                <input id="laporan-date"
                  type={periode === "bulanan" ? "month" : "date"}
                  value={periode === "bulanan"
                    ? tanggal.slice(0, 7) : tanggal}
                  onChange={(event) => setTanggal(
                    periode === "bulanan"
                      ? `${event.target.value}-01` : event.target.value
                  )}
                  required />
              )}
            </div>

            <div className="laporan-field">
              <label htmlFor="laporan-tenant">Tenant</label>
              <select id="laporan-tenant" value={tenant}
                onChange={(event) => setTenant(event.target.value)}>
                <option value="">Semua Tenant</option>
                {tenants.map((item) => (
                  <option key={item.id} value={String(item.id)}>
                    {item.nama}
                  </option>
                ))}
              </select>
            </div>
          </div>

          <div className="laporan-filter-actions">
            <button type="button" className="laporan-reset"
              onClick={resetReport}>
              <RotateCcw size={14} /> Reset
            </button>
            <button type="submit" className="laporan-submit">
              <Filter size={15} /> Tampilkan Laporan
            </button>
          </div>
        </form>
      </section>

      <div className="laporan-stats">
        {stats.map(({ title, value, detail, icon: Icon, color }) => (
          <div className="laporan-stat-card" key={title}>
            <span className={`laporan-stat-icon ${color}`}>
              <Icon size={20} strokeWidth={1.8} />
            </span>
            <div>
              <small>{title}</small>
              <strong>{loading || error ? "-" : value}</strong>
              {detail && !loading && !error && <span>{detail}</span>}
            </div>
          </div>
        ))}
      </div>

      <section className="laporan-panel laporan-chart-panel">
        <div className="laporan-panel-header">
          <div>
            <h2>Grafik Pendapatan {labels[applied.periode]}</h2>
            <p>
              {applied.periode === "harian"
                ? `Pendapatan per jam pada ${formatDate(applied.tanggal)}.`
                : applied.periode === "mingguan"
                  ? `Pendapatan dari ${periodText("mingguan", applied.tanggal)}.`
                  : applied.periode === "bulanan"
                    ? `Pendapatan setiap hari pada ${periodText("bulanan", applied.tanggal)}.`
                    : `Pendapatan setiap bulan pada tahun ${applied.tanggal.slice(0, 4)}.`}
            </p>
          </div>
          <span className="laporan-chart-badge">
            <ChartNoAxesCombined size={14} /> {labels[applied.periode]}
          </span>
        </div>

        <div className="laporan-chart-body">
          {loading || error || paid.length === 0 ? (
            <div className="laporan-chart-empty">
              <ChartNoAxesCombined size={27} strokeWidth={1.5} />
              <p>
                {loading ? "Memuat laporan..."
                  : error ? "Laporan belum dapat ditampilkan."
                    : `Belum ada pendapatan ${applied.periode}.`}
              </p>
            </div>
          ) : (
            <GrafikPendapatan data={chartData} periode={applied.periode} />
          )}
        </div>
      </section>
    </div>
  );
}

export default Laporan;

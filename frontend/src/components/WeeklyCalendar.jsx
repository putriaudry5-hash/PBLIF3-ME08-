
import { useState } from "react";
import { CalendarDays, ChevronLeft, ChevronRight } from "lucide-react";
import "./WeeklyCalendar.css";

const toDate = (value) => new Date(`${value}T00:00:00`);
const formatISO = (date) => {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
};
const formatID = (date) => date.toLocaleDateString("id-ID", {
  day: "2-digit", month: "short", year: "numeric"
});
const startOfWeek = (date) => {
  const monday = new Date(date);
  monday.setDate(monday.getDate() - ((monday.getDay() + 6) % 7));
  return monday;
};

function WeeklyCalendar({ value, onChange }) {
  const [open, setOpen] = useState(false);
  const [month, setMonth] = useState(() => {
    const date = toDate(value);
    return new Date(date.getFullYear(), date.getMonth(), 1);
  });

  const selected = toDate(value);
  const monday = startOfWeek(selected);
  const sunday = new Date(monday);
  sunday.setDate(monday.getDate() + 6);

  const firstDay = startOfWeek(new Date(month.getFullYear(), month.getMonth(), 1));
  const days = Array.from({ length: 42 }, (_, index) => {
    const date = new Date(firstDay);
    date.setDate(firstDay.getDate() + index);
    return date;
  });

  const changeMonth = (offset) => {
    setMonth(new Date(month.getFullYear(), month.getMonth() + offset, 1));
  };

  const selectDay = (date) => {
    onChange(formatISO(date));
    setOpen(false);
  };

  return (
    <div className="weekly-calendar">
      <button type="button" className="weekly-input" onClick={() => setOpen(!open)}>
        <span>{formatID(monday)} – {formatID(sunday)}</span>
        <CalendarDays size={17} />
      </button>

      {open && (
        <div className="weekly-popup">
          <div className="weekly-popup-header">
            <button type="button" onClick={() => changeMonth(-1)} aria-label="Bulan sebelumnya">
              <ChevronLeft size={19} />
            </button>
            <strong>{month.toLocaleDateString("id-ID", { month: "long", year: "numeric" })}</strong>
            <button type="button" onClick={() => changeMonth(1)} aria-label="Bulan berikutnya">
              <ChevronRight size={19} />
            </button>
          </div>

          <div className="weekly-calendar-grid">
            {["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"].map((day) => (
              <span className="weekly-day-name" key={day}>{day}</span>
            ))}
            {days.map((date) => {
              const isSelected = date >= monday && date <= sunday;
              const isCurrentMonth = date.getMonth() === month.getMonth();
              const isClicked = formatISO(date) === value;

              return (
                <button
                  type="button"
                  key={formatISO(date)}
                  className={[
                    "weekly-day",
                    isSelected ? "selected" : "",
                    isClicked ? "clicked" : "",
                    !isCurrentMonth ? "outside" : ""
                  ].join(" ")}
                  onClick={() => selectDay(date)}
                >
                  {date.getDate()}
                </button>
              );
            })}
          </div>

          <div className="weekly-popup-footer">
            <span>Periode: Senin – Minggu</span>
            <button type="button" onClick={() => selectDay(new Date())}>Minggu Ini</button>
          </div>
        </div>
      )}
    </div>
  );
}

export default WeeklyCalendar;

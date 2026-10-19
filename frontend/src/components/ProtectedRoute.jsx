
import { useEffect, useState } from "react";
import { Navigate, Outlet } from "react-router-dom";

export default function ProtectedRoute({ allowedRoles = [] }) {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let active = true;

    fetch("/backend/auth/me", {
      credentials: "include",
      headers: {
        Accept: "application/json",
      },
    })
      .then(async (response) => {
        if (!response.ok) {
          throw new Error("Belum login.");
        }

        return response.json();
      })
      .then((data) => {
        if (active) {
          setUser(data.user);
        }
      })
      .catch(() => {
        if (active) {
          setUser(null);
        }
      })
      .finally(() => {
        if (active) {
          setLoading(false);
        }
      });

    return () => {
      active = false;
    };
  }, []);

  if (loading) {
    return (
      <div style={{ padding: "40px", textAlign: "center" }}>
        Memeriksa sesi login...
      </div>
    );
  }

  if (!user) {
    return <Navigate to="/login/internal" replace />;
  }

  if (!allowedRoles.includes(user.role)) {
    return <Navigate to="/login/internal" replace />;
  }

  return <Outlet />;
}

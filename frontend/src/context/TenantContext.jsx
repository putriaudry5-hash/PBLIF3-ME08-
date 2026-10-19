
import {
  useCallback,
  useEffect,
  useState
} from "react";

import { TenantContext } from "./TenantContext.js";
import { apiRequest } from "../services/api.js";

export function TenantProvider({ children }) {
  const [tenants, setTenants] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  // Mengambil data tenant terbaru dari Laravel
  const refreshTenants = useCallback(async () => {
    setLoading(true);
    setError("");

    try {
      const result = await apiRequest("/tenants");

      if (!Array.isArray(result)) {
        throw new Error("Format data tenant tidak sesuai.");
      }

      setTenants(result);
      return result;
    } catch (err) {
      setError(err.message || "Gagal mengambil data tenant.");
      throw err;
    } finally {
      setLoading(false);
    }
  }, []);

  // Memuat data saat aplikasi pertama kali dibuka
  useEffect(() => {
    let active = true;

    apiRequest("/tenants")
      .then((result) => {
        if (!active) return;

        if (!Array.isArray(result)) {
          throw new Error("Format data tenant tidak sesuai.");
        }

        setTenants(result);
        setError("");
      })
      .catch((err) => {
        if (active) {
          setError(
            err.message || "Gagal mengambil data tenant."
          );
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

  // Menambahkan tenant ke MySQL
  const tambahTenant = async (data) => {
    const response = await apiRequest("/tenants", {
      method: "POST",
      body: JSON.stringify(data)
    });

    await refreshTenants();
    return response.data;
  };

  // Memperbarui informasi tenant
  const editTenant = async (id, data) => {
    const response = await apiRequest(`/tenants/${id}`, {
      method: "PUT",
      body: JSON.stringify(data)
    });

    await refreshTenants();
    return response.data;
  };

  // Mengaktifkan atau menonaktifkan tenant
  const ubahStatusTenant = async (id) => {
    const response = await apiRequest(
      `/tenants/${id}/toggle-status`,
      {
        method: "PATCH"
      }
    );

    await refreshTenants();
    return response.data;
  };

  return (
    <TenantContext.Provider
      value={{
        tenants,
        loading,
        error,
        refreshTenants,
        tambahTenant,
        editTenant,
        ubahStatusTenant
      }}
    >
      {children}
    </TenantContext.Provider>
  );
}

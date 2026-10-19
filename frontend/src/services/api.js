const API_BASE_URL = "/backend/api";
const BASE_URL = "/backend";

async function getCsrfToken() {
  const response = await fetch(`${BASE_URL}/auth/csrf`, {
    credentials: "include",
    headers: {
      Accept: "application/json",
    },
  });

  if (!response.ok) {
    throw new Error("Gagal mengambil token keamanan Laravel.");
  }

  const data = await response.json();

  return data.csrf_token;
}

export async function apiRequest(endpoint, options = {}) {
  const method = (options.method || "GET").toUpperCase();

  const headers = {
    Accept: "application/json",
    ...options.headers,
  };

  if (options.body && !(options.body instanceof FormData)) {
    headers["Content-Type"] = "application/json";
  }

  if (!["GET", "HEAD", "OPTIONS"].includes(method)) {
    headers["X-CSRF-TOKEN"] = await getCsrfToken();
  }

  let response;

  try {
    response = await fetch(`${API_BASE_URL}${endpoint}`, {
      ...options,
      method,
      credentials: "include",
      headers,
    });
  } catch {
    throw new Error("Tidak dapat terhubung ke Laravel.");
  }

  const result = await response.json().catch(() => null);

  if (!response.ok) {
    const validationError = result?.errors
      ? Object.values(result.errors).flat()[0]
      : null;

    throw new Error(
      validationError ||
      result?.message ||
      `Terjadi kesalahan server (${response.status}).`
    );
  }

  return result;
}

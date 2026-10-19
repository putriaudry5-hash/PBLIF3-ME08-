const BASE_URL = "/backend";

export async function loginInternal(email, password) {
  // Ambil CSRF token sekaligus membuat session Laravel
  const csrfResponse = await fetch(`${BASE_URL}/auth/csrf`, {
    credentials: "include",
    headers: {
      Accept: "application/json",
    },
  });

  if (!csrfResponse.ok) {
    throw new Error("Gagal mengambil token keamanan Laravel.");
  }

  const { csrf_token } = await csrfResponse.json();

  // Proses login menggunakan session dan cookie
  const response = await fetch(`${BASE_URL}/login/internal`, {
    method: "POST",
    credentials: "include",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrf_token,
    },
    body: JSON.stringify({
      email,
      password,
    }),
  });

  const result = await response.json().catch(() => ({}));

  if (!response.ok) {
    const validationError = result.errors
      ? Object.values(result.errors).flat()[0]
      : null;

    throw new Error(
      validationError || result.message || "Login gagal."
    );
  }

  return result;
}
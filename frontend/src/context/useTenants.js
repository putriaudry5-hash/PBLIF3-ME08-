
import { useContext } from "react";
import { TenantContext } from "./TenantContext.js";

export function useTenants() {
  const context = useContext(TenantContext);

  if (!context) {
    throw new Error(
      "useTenants harus digunakan di dalam TenantProvider"
    );
  }

  return context;
}

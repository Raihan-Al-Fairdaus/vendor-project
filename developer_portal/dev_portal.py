# -*- coding: utf-8 -*-
"""
=============================================================================
DNA ADVERTISING - DEVELOPER PORTAL (DESKTOP APPLICATION)
=============================================================================
Aplikasi Desktop Resmi Khusus Developer / Super Administrator
- Tampilan Modern 100% Identik dengan Web Portal DNA:
  * Deep Navy Brand (#1B3A60) & Gold Amber (#F59E0B)
  * Split-screen Premium Login View dengan Logo DNA & Billboard Overlay
  * Full Dashboard Layout dengan Sidebar, Top Header Bar, & KPI Cards
- Native Windows Desktop WebView2 Engine (Microsoft Edge Chromium)
- Live Realtime Clock WIB dengan Indikator Status Hijau
- Monitoring Riwayat Login Realtime & Deteksi Sandi yang Digunakan
- Manajemen Akun Admin:
  * Lihat Kredensial & Sandi Tersimpan (Fitur Intip 👁️)
  * Salin Kredensial untuk Dikirim ke WhatsApp HRD
  * Blokir / Buka Blokir Akun Admin yang Mencurigakan
  * Tendang / Hapus Akun Tak Dikenal Secara Permanen
  * Ganti / Reset Sandi Instan (Dengan Generator Password Acak)
  * Tambah Admin / HRD Baru Langsung ke Database Online
=============================================================================
"""

import sys
import os
import json
import subprocess
import requests
import webview
from ui_template import get_html_content

# Konfigurasi Default
DEFAULT_SERVER_URL = "https://dna-vendor.vercel.app"
DEFAULT_SECRET_KEY = "dna_dev_sec_2026_secret_key_dna_portal"
CONFIG_FILE = os.path.join(os.path.expanduser("~"), ".dna_dev_portal_config.json")


def get_resource_path(relative_path: str) -> str:
    """ Dapatkan path absolut resource, baik saat dev maupun setelah dicompile ke .exe """
    try:
        base_path = sys._MEIPASS
    except Exception:
        base_path = os.path.abspath(os.path.dirname(__file__))

    p1 = os.path.join(base_path, relative_path)
    if os.path.exists(p1):
        return p1

    root_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
    p2 = os.path.join(root_dir, relative_path)
    if os.path.exists(p2):
        return p2

    return p1


def load_local_config() -> dict:
    if os.path.exists(CONFIG_FILE):
        try:
            with open(CONFIG_FILE, "r", encoding="utf-8") as f:
                return json.load(f)
        except Exception:
            pass
    return {
        "server_url": DEFAULT_SERVER_URL,
        "remember_email": "developer@dna-advertising.com",
    }


def save_local_config(data: dict) -> None:
    try:
        with open(CONFIG_FILE, "w", encoding="utf-8") as f:
            json.dump(data, f, indent=2)
    except Exception:
        pass


class DeveloperApi:
    """ API Bridge Python yang dapat dipanggil langsung dari JavaScript webview """

    def __init__(self):
        self.config = load_local_config()
        self.server_url = self.config.get("server_url", DEFAULT_SERVER_URL).rstrip("/")
        self.secret_key = DEFAULT_SECRET_KEY
        self.auth_token = None
        self.dev_email = None

    def api_request(self, method: str, subpath: str, **kwargs) -> dict:
        base = self.server_url.rstrip("/")
        timeout = kwargs.pop("timeout", 15)

        headers = kwargs.get("headers", {})
        headers["X-Dev-Secret-Key"] = self.secret_key
        headers["Accept"] = "application/json"
        if self.auth_token:
            headers["Authorization"] = f"Bearer {self.auth_token}"
        kwargs["headers"] = headers

        # Coba endpoint /api/developer terlebih dahulu, lalu fallback ke /developer
        for prefix in ["/api/developer", "/developer"]:
            url = f"{base}{prefix}{subpath}"
            try:
                resp = requests.request(method, url, timeout=timeout, **kwargs)
                if resp.status_code != 404:
                    try:
                        return resp.json()
                    except Exception:
                        return {"status": "error", "message": f"Server status: {resp.status_code}"}
            except Exception as e:
                pass

        return {"status": "error", "message": "Gagal terhubung ke server backend DNA."}

    def get_config(self) -> dict:
        return self.config

    def save_config(self, new_url: str) -> dict:
        new_url = new_url.strip().rstrip("/")
        self.server_url = new_url
        self.config["server_url"] = new_url
        save_local_config(self.config)
        return {"status": "success", "message": "Server URL saved"}

    def copy_to_clipboard(self, text: str) -> bool:
        try:
            p = subprocess.Popen(["clip"], stdin=subprocess.PIPE, close_fds=True)
            p.communicate(input=text.encode("utf-8"))
            return True
        except Exception:
            return False

    def login(self, email: str, password: str, secret_key: str = None) -> dict:
        payload = {
            "email": email.strip(),
            "password": password,
            "secret_key": secret_key or self.secret_key,
        }
        res = self.api_request("POST", "/login", json=payload)
        if res.get("status") == "success":
            data = res.get("data", {})
            self.auth_token = data.get("token")
            self.dev_email = email.strip()
            self.config["remember_email"] = self.dev_email
            save_local_config(self.config)
        return res

    def get_stats(self) -> dict:
        return self.api_request("GET", "/stats")

    def get_logs(self, search: str = "", status: str = "Semua", limit: int = 150) -> dict:
        params = {"limit": limit}
        if search:
            params["search"] = search
        if status and status != "Semua":
            params["status"] = status
        return self.api_request("GET", "/logs", params=params)

    def clear_logs(self) -> dict:
        return self.api_request("DELETE", "/logs")

    def get_admins(self) -> dict:
        return self.api_request("GET", "/admins")

    def create_admin(self, name: str, email: str, password: str) -> dict:
        payload = {"name": name.strip(), "email": email.strip(), "password": password}
        return self.api_request("POST", "/admins", json=payload)

    def reset_password(self, admin_id: int, new_password: str) -> dict:
        payload = {"new_password": new_password.strip()}
        return self.api_request("POST", f"/admins/{admin_id}/reset-password", json=payload)

    def toggle_block(self, admin_id: int) -> dict:
        return self.api_request("POST", f"/admins/{admin_id}/toggle-block")

    def delete_admin(self, admin_id: int) -> dict:
        return self.api_request("DELETE", f"/admins/{admin_id}")


def main():
    api = DeveloperApi()
    html_content = get_html_content()

    window = webview.create_window(
        title="DNA Advertising - Developer Central Security Portal",
        html=html_content,
        js_api=api,
        width=1240,
        height=780,
        min_size=(1050, 680),
    )

    # Jalankan WebView2
    webview.start(gui="edgechromium")


if __name__ == "__main__":
    main()

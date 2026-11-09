"""
=============================================================================
DNA ADVERTISING - DEVELOPER PORTAL (DESKTOP APPLICATION)
=============================================================================
Aplikasi Desktop Khusus Developer / Super Administrator
- Login Eksklusif Developer (Email, Password, Secret Key)
- Dilengkapi Fitur Intip/Lihat Password (👁️ Show/Hide Password)
- Server URL Fleksibel (Bisa untuk Vercel, Hosting Pribadi/VPS/cPanel, atau Localhost)
- Live Monitoring Riwayat Login Admin
- Manajemen Akun Admin & Reset Password Jika Admin Lupa Password
=============================================================================
"""

import sys
import os
import json
import time
import secrets
import string
import threading
import requests
import tkinter as tk
from tkinter import ttk, messagebox

# Aktifkan DPI Awareness agar UI tajam dan tidak buram di Windows 10/11
try:
    import ctypes
    ctypes.windll.shcore.SetProcessDpiAwareness(1)
except Exception:
    pass

# =============================================================================
# KONFIGURASI TEMA WARNA MODERN (DARK THEME)
# =============================================================================
COLOR_BG_DARK       = "#0B1120"  # Dark Slate (Latar Belakang Luar)
COLOR_BG_CARD       = "#1E293B"  # Slate 800 (Card & Panel)
COLOR_BG_CARD_HOVER = "#334155"  # Slate 700 (Border & Hover)
COLOR_BG_INPUT      = "#0F172A"  # Dark Slate (Input Field)
COLOR_TEXT_PRIMARY  = "#F8FAFC"  # Putih Bersih
COLOR_TEXT_MUTED    = "#94A3B8"  # Slate 400 (Teks Sekunder)
COLOR_PRIMARY       = "#2563EB"  # Royal Blue (Tombol Utama)
COLOR_PRIMARY_HOVER = "#1D4ED8"  # Darker Blue
COLOR_SUCCESS       = "#10B981"  # Emerald Green (Status Sukses)
COLOR_DANGER        = "#EF4444"  # Merah (Hapus / Status Gagal)
COLOR_DANGER_HOVER  = "#DC2626"
COLOR_WARNING       = "#F59E0B"  # Amber (Peringatan)
COLOR_BORDER        = "#334155"  # Border Slate

FONT_TITLE = ("Segoe UI", 16, "bold")
FONT_HEADING = ("Segoe UI", 12, "bold")
FONT_BODY = ("Segoe UI", 10)
FONT_BODY_BOLD = ("Segoe UI", 10, "bold")
FONT_SMALL = ("Segoe UI", 9)
FONT_MONO = ("Consolas", 9)

# Lokasi File Konfigurasi Lokal untuk menyimpan URL Server & Email terakhir
CONFIG_FILE = os.path.join(os.path.expanduser("~"), ".dna_dev_portal_config.json")


def load_local_config():
    if os.path.exists(CONFIG_FILE):
        try:
            with open(CONFIG_FILE, "r", encoding="utf-8") as f:
                return json.load(f)
        except Exception:
            pass
    return {
        "server_url": "https://dna-vendor.vercel.app",
        "remember_email": "developer@dna-advertising.com",
    }


def save_local_config(data):
    try:
        with open(CONFIG_FILE, "w", encoding="utf-8") as f:
            json.dump(data, f, indent=2)
    except Exception:
        pass


# =============================================================================
# KELAS UTAMA APLIKASI DEVELOPER PORTAL
# =============================================================================
class DeveloperPortalApp(tk.Tk):
    def __init__(self):
        super().__init__()
        self.title("DNA Advertising - Developer Portal")
        self.geometry("1120x740")
        self.minsize(980, 640)
        self.configure(bg=COLOR_BG_DARK)

        # State Aplikasi
        self.server_url = "https://dna-vendor.vercel.app"
        self.auth_token = None
        self.dev_email = None
        self.auto_refresh_timer = None
        self.auto_refresh_enabled = False

        # Load konfigurasi sebelumnya
        self.local_config = load_local_config()
        self.server_url = self.local_config.get("server_url", "https://dna-vendor.vercel.app")

        # Inisialisasi Tampilan
        self.setup_styles()
        self.show_login_frame()

    # -------------------------------------------------------------------------
    # STYLING TTK
    # -------------------------------------------------------------------------
    def setup_styles(self):
        self.style = ttk.Style(self)
        try:
            self.style.theme_use("clam")
        except Exception:
            pass

        self.style.configure(
            "TNotebook",
            background=COLOR_BG_DARK,
            borderwidth=0,
        )
        self.style.configure(
            "TNotebook.Tab",
            background=COLOR_BG_CARD,
            foreground=COLOR_TEXT_MUTED,
            font=("Segoe UI", 10, "bold"),
            padding=[18, 9],
            borderwidth=0,
        )
        self.style.map(
            "TNotebook.Tab",
            background=[("selected", COLOR_PRIMARY), ("active", COLOR_BG_CARD_HOVER)],
            foreground=[("selected", COLOR_TEXT_PRIMARY), ("active", COLOR_TEXT_PRIMARY)],
        )

        self.style.configure(
            "Treeview",
            background=COLOR_BG_CARD,
            foreground=COLOR_TEXT_PRIMARY,
            fieldbackground=COLOR_BG_CARD,
            font=("Segoe UI", 9),
            rowheight=32,
            borderwidth=0,
        )
        self.style.configure(
            "Treeview.Heading",
            background=COLOR_BG_CARD_HOVER,
            foreground=COLOR_TEXT_PRIMARY,
            font=("Segoe UI", 9, "bold"),
            relief="flat",
            padding=[8, 6],
        )
        self.style.map("Treeview.Heading", background=[("active", COLOR_PRIMARY)])
        self.style.map(
            "Treeview",
            background=[("selected", COLOR_PRIMARY)],
            foreground=[("selected", COLOR_TEXT_PRIMARY)],
        )

        self.style.configure(
            "Vertical.TScrollbar",
            background=COLOR_BG_CARD,
            troughcolor=COLOR_BG_DARK,
            borderwidth=0,
            arrowsize=12,
        )

    # -------------------------------------------------------------------------
    # HELPER REQUEST DENGAN DUAL ROUTE FALLBACK (/api/developer ATAU /developer)
    # -------------------------------------------------------------------------
    def api_request(self, method: str, subpath: str, **kwargs):
        """
        Mengirim request dengan otomatis mencoba /api/developer/{subpath},
        dan jika 404 Not Found, otomatis fallback ke /developer/{subpath}.
        Memastikan kompatibel dengan Vercel, Laragon, cPanel, Nginx, maupun VPS!
        """
        base = self.server_url.rstrip("/")
        timeout = kwargs.pop("timeout", 15)

        # Rute 1: /api/developer/...
        url1 = f"{base}/api/developer{subpath}"
        try:
            resp = requests.request(method, url1, timeout=timeout, **kwargs)
            if resp.status_code != 404:
                return resp
        except Exception as e:
            # Jika rute 1 gagal koneksi atau error selain 404, coba rute 2
            pass

        # Rute 2 Fallback: /developer/...
        url2 = f"{base}/developer{subpath}"
        return requests.request(method, url2, timeout=timeout, **kwargs)

    # -------------------------------------------------------------------------
    # TAMPILAN LOGIN DEVELOPER
    # -------------------------------------------------------------------------
    def show_login_frame(self):
        if hasattr(self, "main_frame") and self.main_frame:
            self.main_frame.destroy()

        self.login_frame = tk.Frame(self, bg=COLOR_BG_DARK)
        self.login_frame.pack(fill="both", expand=True)

        # Container Tengah
        center = tk.Frame(self.login_frame, bg=COLOR_BG_DARK)
        center.place(relx=0.5, rely=0.5, anchor="center")

        card = tk.Frame(
            center,
            bg=COLOR_BG_CARD,
            padx=42,
            pady=34,
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
        )
        card.pack()

        # Badge Developer
        badge = tk.Frame(card, bg="#312E81", padx=14, pady=4)
        badge.pack(anchor="center", pady=(0, 10))
        tk.Label(
            badge,
            text="⚡ DNA DEVELOPER SECURITY ACCESS",
            font=("Segoe UI", 8, "bold"),
            fg="#A5B4FC",
            bg="#312E81",
        ).pack()

        tk.Label(
            card,
            text="DNA Developer Portal",
            font=FONT_TITLE,
            fg=COLOR_TEXT_PRIMARY,
            bg=COLOR_BG_CARD,
        ).pack(anchor="center")

        tk.Label(
            card,
            text="Khusus Developer / Super Administrator",
            font=FONT_SMALL,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="center", pady=(4, 18))

        # ---------------------------------------------------------------------
        # 1. INPUT ALAMAT WEBSITE / DOMAIN BACKEND
        # ---------------------------------------------------------------------
        server_lbl_frame = tk.Frame(card, bg=COLOR_BG_CARD)
        server_lbl_frame.pack(fill="x", pady=(0, 3))

        tk.Label(
            server_lbl_frame,
            text="ALAMAT WEBSITE (DOMAIN HOSTING / BACKEND)",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(side="left")

        # Tombol Cepat (Presets)
        presets_frame = tk.Frame(server_lbl_frame, bg=COLOR_BG_CARD)
        presets_frame.pack(side="right")

        def set_preset(url):
            self.server_entry.delete(0, tk.END)
            self.server_entry.insert(0, url)

        btn_vercel = tk.Button(
            presets_frame,
            text="Vercel",
            font=("Segoe UI", 7, "bold"),
            bg=COLOR_BG_CARD_HOVER,
            fg=COLOR_TEXT_PRIMARY,
            relief="flat",
            padx=5,
            pady=1,
            cursor="hand2",
            command=lambda: set_preset("https://dna-vendor.vercel.app"),
        )
        btn_vercel.pack(side="left", padx=2)

        btn_local = tk.Button(
            presets_frame,
            text="Localhost",
            font=("Segoe UI", 7, "bold"),
            bg=COLOR_BG_CARD_HOVER,
            fg=COLOR_TEXT_PRIMARY,
            relief="flat",
            padx=5,
            pady=1,
            cursor="hand2",
            command=lambda: set_preset("http://127.0.0.1:8000"),
        )
        btn_local.pack(side="left", padx=2)

        self.server_entry = tk.Entry(
            card,
            font=FONT_BODY,
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_PRIMARY,
            insertbackground=COLOR_TEXT_PRIMARY,
            relief="flat",
            highlightthickness=1,
            highlightbackground=COLOR_BORDER,
            highlightcolor=COLOR_PRIMARY,
            width=46,
        )
        self.server_entry.insert(0, self.server_url)
        self.server_entry.pack(fill="x", ipady=6, pady=(0, 2))

        tk.Label(
            card,
            text="*Jika nanti website sudah dipindah ke hosting baru, cukup ganti alamat URL di atas.",
            font=("Segoe UI", 7),
            fg="#64748B",
            bg=COLOR_BG_CARD,
        ).pack(anchor="w", pady=(0, 12))

        # ---------------------------------------------------------------------
        # 2. INPUT EMAIL DEVELOPER
        # ---------------------------------------------------------------------
        tk.Label(
            card,
            text="EMAIL DEVELOPER",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="w", pady=(0, 3))

        self.email_entry = tk.Entry(
            card,
            font=FONT_BODY,
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_PRIMARY,
            insertbackground=COLOR_TEXT_PRIMARY,
            relief="flat",
            highlightthickness=1,
            highlightbackground=COLOR_BORDER,
            highlightcolor=COLOR_PRIMARY,
            width=46,
        )
        saved_email = self.local_config.get("remember_email", "developer@dna-advertising.com")
        self.email_entry.insert(0, saved_email)
        self.email_entry.pack(fill="x", ipady=6, pady=(0, 12))

        # ---------------------------------------------------------------------
        # 3. INPUT PASSWORD DEVELOPER (DENGAN FITUR LIHAT PASSWORD 👁️)
        # ---------------------------------------------------------------------
        tk.Label(
            card,
            text="PASSWORD DEVELOPER",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="w", pady=(0, 3))

        pass_row = tk.Frame(card, bg=COLOR_BG_INPUT, highlightbackground=COLOR_BORDER, highlightthickness=1)
        pass_row.pack(fill="x", pady=(0, 12))

        self.pass_entry = tk.Entry(
            pass_row,
            font=FONT_BODY,
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_PRIMARY,
            insertbackground=COLOR_TEXT_PRIMARY,
            relief="flat",
            show="•",
        )
        self.pass_entry.insert(0, "DevDNA@2026!")
        self.pass_entry.pack(side="left", fill="x", expand=True, ipady=6, padx=(8, 0))

        self.btn_eye_pass = tk.Button(
            pass_row,
            text="👁️",
            font=("Segoe UI Emoji", 10),
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_MUTED,
            activebackground=COLOR_BG_CARD_HOVER,
            activeforeground=COLOR_TEXT_PRIMARY,
            relief="flat",
            bd=0,
            cursor="hand2",
            padx=8,
            command=lambda: self.toggle_input_visibility(self.pass_entry, self.btn_eye_pass),
        )
        self.btn_eye_pass.pack(side="right", padx=(0, 4))

        # ---------------------------------------------------------------------
        # 4. INPUT SECRET KEY DEVELOPER (DENGAN FITUR LIHAT PASSWORD 👁️)
        # ---------------------------------------------------------------------
        tk.Label(
            card,
            text="DEVELOPER SECRET KEY",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="w", pady=(0, 3))

        secret_row = tk.Frame(card, bg=COLOR_BG_INPUT, highlightbackground=COLOR_BORDER, highlightthickness=1)
        secret_row.pack(fill="x", pady=(0, 16))

        self.secret_entry = tk.Entry(
            secret_row,
            font=FONT_BODY,
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_PRIMARY,
            insertbackground=COLOR_TEXT_PRIMARY,
            relief="flat",
            show="•",
        )
        self.secret_entry.insert(0, "dna_dev_sec_2026_secret_key_dna_portal")
        self.secret_entry.pack(side="left", fill="x", expand=True, ipady=6, padx=(8, 0))

        self.btn_eye_secret = tk.Button(
            secret_row,
            text="👁️",
            font=("Segoe UI Emoji", 10),
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_MUTED,
            activebackground=COLOR_BG_CARD_HOVER,
            activeforeground=COLOR_TEXT_PRIMARY,
            relief="flat",
            bd=0,
            cursor="hand2",
            padx=8,
            command=lambda: self.toggle_input_visibility(self.secret_entry, self.btn_eye_secret),
        )
        self.btn_eye_secret.pack(side="right", padx=(0, 4))

        # Status / Feedback Label
        self.login_status_lbl = tk.Label(
            card,
            text="",
            font=FONT_SMALL,
            fg=COLOR_DANGER,
            bg=COLOR_BG_CARD,
            wraplength=380,
            justify="center",
        )
        self.login_status_lbl.pack(pady=(0, 12))

        # Tombol Login
        self.btn_login = tk.Button(
            card,
            text="MASUK KE DEVELOPER PORTAL",
            font=FONT_BODY_BOLD,
            bg=COLOR_PRIMARY,
            fg="#FFFFFF",
            activebackground=COLOR_PRIMARY_HOVER,
            activeforeground="#FFFFFF",
            relief="flat",
            cursor="hand2",
            command=self.handle_login_clicked,
        )
        self.btn_login.pack(fill="x", ipady=9)

        # Shortcut Enter
        self.pass_entry.bind("<Return>", lambda e: self.handle_login_clicked())
        self.secret_entry.bind("<Return>", lambda e: self.handle_login_clicked())

    def toggle_input_visibility(self, entry_widget, button_widget):
        """Fitur Intip/Lihat Password yang disembunyikan"""
        if entry_widget.cget("show") == "":
            entry_widget.config(show="•")
            button_widget.config(text="👁️", fg=COLOR_TEXT_MUTED)
        else:
            entry_widget.config(show="")
            button_widget.config(text="🔒", fg=COLOR_PRIMARY)

    def set_login_loading(self, loading: bool, message: str = "", is_error: bool = False):
        if loading:
            self.btn_login.config(state="disabled", text="Memverifikasi Kredensial...", bg=COLOR_BG_CARD_HOVER)
            self.login_status_lbl.config(text="Menghubungi Server Backend...", fg=COLOR_TEXT_MUTED)
        else:
            self.btn_login.config(state="normal", text="MASUK KE DEVELOPER PORTAL", bg=COLOR_PRIMARY)
            self.login_status_lbl.config(
                text=message,
                fg=COLOR_DANGER if is_error else COLOR_SUCCESS,
            )

    def handle_login_clicked(self):
        server = self.server_entry.get().strip().rstrip("/")
        email = self.email_entry.get().strip()
        password = self.pass_entry.get().strip()
        secret_key = self.secret_entry.get().strip()

        if not server or not email or not password:
            self.set_login_loading(False, "Harap isi Server URL, Email, dan Password!", is_error=True)
            return

        self.server_url = server
        self.set_login_loading(True)

        def worker():
            headers = {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Dev-Secret-Key": secret_key,
            }
            payload = {
                "email": email,
                "password": password,
                "secret_key": secret_key,
            }

            try:
                resp = self.api_request("POST", "/login", json=payload, headers=headers, timeout=12)
                data = resp.json() if resp.headers.get("content-type", "").startswith("application/json") else {}

                if resp.status_code == 200 and data.get("status") == "success":
                    token = data.get("data", {}).get("token")
                    dev_email = data.get("data", {}).get("email")

                    save_local_config({
                        "server_url": self.server_url,
                        "remember_email": email,
                    })

                    self.after(0, lambda: self.on_login_success(token, dev_email))
                elif resp.status_code == 404:
                    err_msg = (
                        "Server merespons 404 (Route not found).\n"
                        "Kemungkinan update kode belum di-push ke server tersebut,\n"
                        "atau alamat server belum tepat."
                    )
                    self.after(0, lambda: self.set_login_loading(False, err_msg, is_error=True))
                else:
                    err_msg = data.get("message") or f"Gagal masuk (Kode: {resp.status_code})"
                    self.after(0, lambda: self.set_login_loading(False, err_msg, is_error=True))
            except requests.exceptions.ConnectionError:
                self.after(0, lambda: self.set_login_loading(
                    False,
                    f"Tidak dapat terhubung ke server:\n{self.server_url}\nPastikan server aktif dan URL benar.",
                    is_error=True
                ))
            except requests.exceptions.Timeout:
                self.after(0, lambda: self.set_login_loading(
                    False, "Koneksi timeout. Server memakan waktu terlalu lama.", is_error=True
                ))
            except Exception as e:
                self.after(0, lambda: self.set_login_loading(False, f"Terjadi kesalahan: {str(e)}", is_error=True))

        threading.Thread(target=worker, daemon=True).start()

    def on_login_success(self, token, dev_email):
        self.auth_token = token
        self.dev_email = dev_email
        self.show_dashboard_frame()

    # -------------------------------------------------------------------------
    # TAMPILAN DASHBOARD UTAMA DEVELOPER
    # -------------------------------------------------------------------------
    def show_dashboard_frame(self):
        if hasattr(self, "login_frame") and self.login_frame:
            self.login_frame.destroy()

        self.main_frame = tk.Frame(self, bg=COLOR_BG_DARK)
        self.main_frame.pack(fill="both", expand=True)

        # 1. HEADER BAR
        header = tk.Frame(self.main_frame, bg=COLOR_BG_CARD, padx=22, pady=12)
        header.pack(fill="x")

        # Judul & Badge Kiri
        title_box = tk.Frame(header, bg=COLOR_BG_CARD)
        title_box.pack(side="left")

        tk.Label(
            title_box,
            text="DNA ADVERTISING",
            font=("Segoe UI", 12, "bold"),
            fg=COLOR_TEXT_PRIMARY,
            bg=COLOR_BG_CARD,
        ).pack(side="left")

        dev_badge = tk.Frame(title_box, bg="#312E81", padx=8, pady=2)
        dev_badge.pack(side="left", padx=10)
        tk.Label(
            dev_badge,
            text="DEVELOPER CONSOLE",
            font=("Segoe UI", 8, "bold"),
            fg="#A5B4FC",
            bg="#312E81",
        ).pack()

        # Status Koneksi Server
        server_short = "Production (Vercel)" if "vercel" in self.server_url else ("Localhost" if "127.0.0.1" in self.server_url or "localhost" in self.server_url else "Custom Server")
        tk.Label(
            header,
            text=f"🟢 {server_short} ({self.server_url})",
            font=FONT_SMALL,
            fg=COLOR_SUCCESS,
            bg=COLOR_BG_CARD,
        ).pack(side="left", padx=16)

        # Kanan: Identitas & Tombol Logout
        right_box = tk.Frame(header, bg=COLOR_BG_CARD)
        right_box.pack(side="right")

        tk.Label(
            right_box,
            text=f"Developer: {self.dev_email}",
            font=FONT_SMALL,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(side="left", padx=12)

        btn_logout = tk.Button(
            right_box,
            text="Logout",
            font=FONT_SMALL,
            bg=COLOR_BG_CARD_HOVER,
            fg=COLOR_TEXT_PRIMARY,
            activebackground=COLOR_DANGER,
            activeforeground="#FFFFFF",
            relief="flat",
            padx=12,
            pady=3,
            cursor="hand2",
            command=self.handle_logout,
        )
        btn_logout.pack(side="right")

        # 2. STATS CARDS BAR
        self.stats_bar = tk.Frame(self.main_frame, bg=COLOR_BG_DARK, padx=22, pady=14)
        self.stats_bar.pack(fill="x")

        self.card_admins_val = self.create_stat_card(self.stats_bar, "TOTAL ADMIN", "0", COLOR_PRIMARY, 0)
        self.card_logs_val = self.create_stat_card(self.stats_bar, "TOTAL RIWAYAT LOGIN", "0", COLOR_TEXT_PRIMARY, 1)
        self.card_success_val = self.create_stat_card(self.stats_bar, "LOGIN BERHASIL", "0", COLOR_SUCCESS, 2)
        self.card_failed_val = self.create_stat_card(self.stats_bar, "PERCOBAAN GAGAL", "0", COLOR_DANGER, 3)

        # 3. TABS CONTAINER
        self.notebook = ttk.Notebook(self.main_frame)
        self.notebook.pack(fill="both", expand=True, padx=22, pady=(0, 16))

        # TAB 1: RIWAYAT LOGIN ADMIN
        self.tab_logs = tk.Frame(self.notebook, bg=COLOR_BG_DARK)
        self.notebook.add(self.tab_logs, text="  📋 Riwayat Login Admin  ")
        self.setup_logs_tab()

        # TAB 2: MANAJEMEN AKUN ADMIN
        self.tab_admins = tk.Frame(self.notebook, bg=COLOR_BG_DARK)
        self.notebook.add(self.tab_admins, text="  👥 Manajemen Akun Admin  ")
        self.setup_admins_tab()

        # Muat Data Awal
        self.refresh_all_data()

    def create_stat_card(self, parent, title, initial_val, val_color, col):
        card = tk.Frame(
            parent,
            bg=COLOR_BG_CARD,
            padx=16,
            pady=12,
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
        )
        card.grid(row=0, column=col, sticky="ew", padx=(0 if col == 0 else 10, 0))
        parent.grid_columnconfigure(col, weight=1)

        tk.Label(
            card,
            text=title,
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="w")

        lbl_val = tk.Label(
            card,
            text=initial_val,
            font=("Segoe UI", 18, "bold"),
            fg=val_color,
            bg=COLOR_BG_CARD,
        )
        lbl_val.pack(anchor="w", pady=(2, 0))
        return lbl_val

    # -------------------------------------------------------------------------
    # TAB 1: RIWAYAT LOGIN ADMIN
    # -------------------------------------------------------------------------
    def setup_logs_tab(self):
        toolbar = tk.Frame(self.tab_logs, bg=COLOR_BG_DARK, pady=10)
        toolbar.pack(fill="x")

        # Pencarian
        tk.Label(toolbar, text="Cari:", font=FONT_BODY_BOLD, fg=COLOR_TEXT_PRIMARY, bg=COLOR_BG_DARK).pack(side="left", padx=(0, 6))

        self.log_search_var = tk.StringVar()
        self.log_search_entry = tk.Entry(
            toolbar,
            textvariable=self.log_search_var,
            font=FONT_BODY,
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_PRIMARY,
            insertbackground=COLOR_TEXT_PRIMARY,
            relief="flat",
            highlightthickness=1,
            highlightbackground=COLOR_BORDER,
            highlightcolor=COLOR_PRIMARY,
            width=22,
        )
        self.log_search_entry.pack(side="left", ipady=4, padx=(0, 10))
        self.log_search_entry.bind("<Return>", lambda e: self.fetch_logs())

        # Filter Status
        tk.Label(toolbar, text="Status:", font=FONT_BODY_BOLD, fg=COLOR_TEXT_PRIMARY, bg=COLOR_BG_DARK).pack(side="left", padx=(6, 6))

        self.log_status_filter_var = tk.StringVar(value="Semua")
        status_dropdown = ttk.Combobox(
            toolbar,
            textvariable=self.log_status_filter_var,
            values=["Semua", "success", "failed"],
            state="readonly",
            width=10,
            font=FONT_BODY,
        )
        status_dropdown.pack(side="left", padx=(0, 12))
        status_dropdown.bind("<<ComboboxSelected>>", lambda e: self.fetch_logs())

        btn_filter = tk.Button(
            toolbar,
            text="🔍 Cari",
            font=FONT_SMALL,
            bg=COLOR_BG_CARD_HOVER,
            fg=COLOR_TEXT_PRIMARY,
            relief="flat",
            padx=10,
            pady=4,
            cursor="hand2",
            command=self.fetch_logs,
        )
        btn_filter.pack(side="left", padx=(0, 8))

        btn_refresh = tk.Button(
            toolbar,
            text="🔄 Refresh Riwayat",
            font=FONT_SMALL,
            bg=COLOR_PRIMARY,
            fg="#FFFFFF",
            relief="flat",
            padx=12,
            pady=4,
            cursor="hand2",
            command=self.fetch_logs,
        )
        btn_refresh.pack(side="left", padx=(0, 8))

        self.auto_refresh_var = tk.BooleanVar(value=False)
        chk_auto = tk.Checkbutton(
            toolbar,
            text="Auto-refresh (15 dtk)",
            variable=self.auto_refresh_var,
            command=self.toggle_auto_refresh,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_DARK,
            selectcolor=COLOR_BG_CARD,
            activebackground=COLOR_BG_DARK,
            activeforeground=COLOR_TEXT_PRIMARY,
            font=FONT_SMALL,
        )
        chk_auto.pack(side="left", padx=(10, 0))

        btn_clear = tk.Button(
            toolbar,
            text="🗑️ Bersihkan Riwayat",
            font=FONT_SMALL,
            bg=COLOR_BG_CARD,
            fg=COLOR_DANGER,
            relief="flat",
            padx=12,
            pady=4,
            cursor="hand2",
            command=self.handle_clear_logs,
        )
        btn_clear.pack(side="right")

        table_frame = tk.Frame(self.tab_logs, bg=COLOR_BG_CARD, highlightbackground=COLOR_BORDER, highlightthickness=1)
        table_frame.pack(fill="both", expand=True)

        columns = ("id", "waktu", "email", "admin_name", "status", "ip", "pesan")
        self.logs_tree = ttk.Treeview(table_frame, columns=columns, show="headings", selectmode="browse")

        self.logs_tree.heading("id", text="ID", anchor="center")
        self.logs_tree.heading("waktu", text="Waktu & Tanggal", anchor="w")
        self.logs_tree.heading("email", text="Email Admin", anchor="w")
        self.logs_tree.heading("admin_name", text="Nama Admin", anchor="w")
        self.logs_tree.heading("status", text="Status", anchor="center")
        self.logs_tree.heading("ip", text="IP Address", anchor="w")
        self.logs_tree.heading("pesan", text="Keterangan / Pesan", anchor="w")

        self.logs_tree.column("id", width=50, anchor="center")
        self.logs_tree.column("waktu", width=150, anchor="w")
        self.logs_tree.column("email", width=190, anchor="w")
        self.logs_tree.column("admin_name", width=130, anchor="w")
        self.logs_tree.column("status", width=90, anchor="center")
        self.logs_tree.column("ip", width=120, anchor="w")
        self.logs_tree.column("pesan", width=300, anchor="w")

        self.logs_tree.tag_configure("success_tag", foreground=COLOR_SUCCESS)
        self.logs_tree.tag_configure("failed_tag", foreground=COLOR_DANGER)

        scrollbar = ttk.Scrollbar(table_frame, orient="vertical", command=self.logs_tree.yview, style="Vertical.TScrollbar")
        self.logs_tree.configure(yscrollcommand=scrollbar.set)

        self.logs_tree.pack(side="left", fill="both", expand=True)
        scrollbar.pack(side="right", fill="y")

        self.logs_tree.bind("<Double-1>", self.on_log_double_click)

        footer_lbl = tk.Label(
            self.tab_logs,
            text="💡 Tip: Klik ganda pada baris log untuk melihat rincian lengkap (User-Agent, Perangkat, dll).",
            font=FONT_SMALL,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_DARK,
        )
        footer_lbl.pack(anchor="w", pady=(6, 0))

    def on_log_double_click(self, event):
        selected = self.logs_tree.selection()
        if not selected:
            return
        item = self.logs_tree.item(selected[0])
        values = item.get("values", [])
        raw = getattr(self, "_logs_raw_map", {}).get(values[0])
        if not raw:
            return

        dialog = tk.Toplevel(self)
        dialog.title(f"Detail Log #{raw.get('id')} - {raw.get('email')}")
        dialog.geometry("560x420")
        dialog.minsize(500, 380)
        dialog.configure(bg=COLOR_BG_DARK)
        dialog.transient(self)
        dialog.grab_set()

        card = tk.Frame(dialog, bg=COLOR_BG_CARD, padx=24, pady=20, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=20, pady=20)

        tk.Label(card, text=f"Detail Riwayat Login #{raw.get('id')}", font=FONT_HEADING, fg=COLOR_TEXT_PRIMARY, bg=COLOR_BG_CARD).pack(anchor="w", pady=(0, 14))

        fields = [
            ("Waktu Kejadian", raw.get("created_at")),
            ("Email Sasaran", raw.get("email")),
            ("Nama Admin", raw.get("admin_name")),
            ("Status", "BERHASIL (SUCCESS)" if raw.get("status") == "success" else "GAGAL (FAILED)"),
            ("IP Address", raw.get("ip_address")),
            ("Pesan", raw.get("message")),
            ("User-Agent / Device", raw.get("user_agent")),
        ]

        for label, val in fields:
            row = tk.Frame(card, bg=COLOR_BG_CARD, pady=3)
            row.pack(fill="x")
            tk.Label(row, text=f"{label}:", font=FONT_BODY_BOLD, fg=COLOR_TEXT_MUTED, bg=COLOR_BG_CARD, width=18, anchor="w").pack(side="left")
            val_color = COLOR_SUCCESS if val == "BERHASIL (SUCCESS)" else (COLOR_DANGER if val == "GAGAL (FAILED)" else COLOR_TEXT_PRIMARY)
            tk.Label(row, text=str(val or "-"), font=FONT_BODY, fg=val_color, bg=COLOR_BG_CARD, wraplength=320, justify="left").pack(side="left", fill="x", expand=True)

        tk.Button(card, text="Tutup", font=FONT_BODY, bg=COLOR_PRIMARY, fg="#FFFFFF", relief="flat", padx=16, pady=4, cursor="hand2", command=dialog.destroy).pack(anchor="e", pady=(20, 0))

    # -------------------------------------------------------------------------
    # TAB 2: MANAJEMEN AKUN ADMIN
    # -------------------------------------------------------------------------
    def setup_admins_tab(self):
        toolbar = tk.Frame(self.tab_admins, bg=COLOR_BG_DARK, pady=10)
        toolbar.pack(fill="x")

        # Tombol Aksi Utama: Ganti / Reset Password Admin
        btn_reset = tk.Button(
            toolbar,
            text="🔑 Ganti / Reset Password Admin",
            font=FONT_BODY_BOLD,
            bg=COLOR_PRIMARY,
            fg="#FFFFFF",
            activebackground=COLOR_PRIMARY_HOVER,
            activeforeground="#FFFFFF",
            relief="flat",
            padx=14,
            pady=6,
            cursor="hand2",
            command=self.open_reset_password_modal,
        )
        btn_reset.pack(side="left", padx=(0, 10))

        # Tombol Tambah Admin
        btn_add = tk.Button(
            toolbar,
            text="➕ Tambah Admin Baru",
            font=FONT_BODY,
            bg=COLOR_BG_CARD_HOVER,
            fg=COLOR_TEXT_PRIMARY,
            relief="flat",
            padx=12,
            pady=6,
            cursor="hand2",
            command=self.open_add_admin_modal,
        )
        btn_add.pack(side="left", padx=(0, 10))

        # Tombol Hapus Admin
        btn_delete = tk.Button(
            toolbar,
            text="🗑️ Hapus Admin Terpilih",
            font=FONT_BODY,
            bg=COLOR_BG_CARD,
            fg=COLOR_DANGER,
            relief="flat",
            padx=12,
            pady=6,
            cursor="hand2",
            command=self.handle_delete_admin,
        )
        btn_delete.pack(side="left", padx=(0, 10))

        # Tombol Refresh
        btn_refresh = tk.Button(
            toolbar,
            text="🔄 Refresh Data Admin",
            font=FONT_SMALL,
            bg=COLOR_BG_CARD,
            fg=COLOR_TEXT_PRIMARY,
            relief="flat",
            padx=12,
            pady=6,
            cursor="hand2",
            command=self.fetch_admins,
        )
        btn_refresh.pack(side="right")

        table_frame = tk.Frame(self.tab_admins, bg=COLOR_BG_CARD, highlightbackground=COLOR_BORDER, highlightthickness=1)
        table_frame.pack(fill="both", expand=True)

        columns = ("id", "name", "email", "created_at", "last_login", "last_ip")
        self.admins_tree = ttk.Treeview(table_frame, columns=columns, show="headings", selectmode="browse")

        self.admins_tree.heading("id", text="ID", anchor="center")
        self.admins_tree.heading("name", text="Nama Admin", anchor="w")
        self.admins_tree.heading("email", text="Email Login", anchor="w")
        self.admins_tree.heading("created_at", text="Tanggal Dibuat", anchor="w")
        self.admins_tree.heading("last_login", text="Terakhir Login", anchor="w")
        self.admins_tree.heading("last_ip", text="IP Terakhir", anchor="w")

        self.admins_tree.column("id", width=60, anchor="center")
        self.admins_tree.column("name", width=200, anchor="w")
        self.admins_tree.column("email", width=260, anchor="w")
        self.admins_tree.column("created_at", width=160, anchor="w")
        self.admins_tree.column("last_login", width=180, anchor="w")
        self.admins_tree.column("last_ip", width=140, anchor="w")

        scrollbar = ttk.Scrollbar(table_frame, orient="vertical", command=self.admins_tree.yview, style="Vertical.TScrollbar")
        self.admins_tree.configure(yscrollcommand=scrollbar.set)

        self.admins_tree.pack(side="left", fill="both", expand=True)
        scrollbar.pack(side="right", fill="y")

        info_box = tk.Frame(self.tab_admins, bg=COLOR_BG_CARD, padx=16, pady=12, highlightbackground=COLOR_BORDER, highlightthickness=1)
        info_box.pack(fill="x", pady=(12, 0))

        tk.Label(info_box, text="💡 Fitur Reset Password Admin:", font=FONT_BODY_BOLD, fg="#60A5FA", bg=COLOR_BG_CARD).pack(anchor="w")
        tk.Label(
            info_box,
            text="Jika ada admin yang lupa kata sandinya, pilih admin tersebut di tabel lalu klik tombol [🔑 Ganti / Reset Password Admin]. Anda bisa memasukkan kata sandi baru atau mengacak password yang kuat.",
            font=FONT_SMALL,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
            wraplength=850,
            justify="left",
        ).pack(anchor="w", pady=(2, 0))

    # -------------------------------------------------------------------------
    # MODAL: GANTI / RESET PASSWORD ADMIN (DENGAN TOMBOL LIHAT PASSWORD 👁️)
    # -------------------------------------------------------------------------
    def open_reset_password_modal(self):
        selected = self.admins_tree.selection()
        if not selected:
            messagebox.showwarning("Pilih Admin", "Silakan klik salah satu akun admin di tabel terlebih dahulu!", parent=self)
            return

        item = self.admins_tree.item(selected[0])
        values = item.get("values", [])
        admin_id = values[0]
        admin_name = values[1]
        admin_email = values[2]

        modal = tk.Toplevel(self)
        modal.title(f"Ganti Password Admin: {admin_name}")
        modal.geometry("520x400")
        modal.minsize(480, 370)
        modal.configure(bg=COLOR_BG_DARK)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_BG_CARD, padx=28, pady=24, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=20, pady=20)

        tk.Label(card, text="🔑 Reset / Ganti Password Admin", font=FONT_TITLE, fg=COLOR_TEXT_PRIMARY, bg=COLOR_BG_CARD).pack(anchor="w")
        tk.Label(card, text=f"Admin Target: {admin_name} ({admin_email})", font=FONT_BODY_BOLD, fg="#93C5FD", bg=COLOR_BG_CARD).pack(anchor="w", pady=(4, 16))

        tk.Label(card, text="PASSWORD BARU (Minimal 6 Karakter):", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_BG_CARD).pack(anchor="w", pady=(0, 4))

        input_wrapper = tk.Frame(card, bg=COLOR_BG_CARD)
        input_wrapper.pack(fill="x", pady=(0, 6))

        pass_row = tk.Frame(input_wrapper, bg=COLOR_BG_INPUT, highlightbackground=COLOR_BORDER, highlightthickness=1)
        pass_row.pack(side="left", fill="x", expand=True)

        new_pass_entry = tk.Entry(
            pass_row,
            font=FONT_BODY,
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_PRIMARY,
            insertbackground=COLOR_TEXT_PRIMARY,
            relief="flat",
        )
        new_pass_entry.pack(side="left", fill="x", expand=True, ipady=6, padx=(8, 0))

        btn_eye_modal = tk.Button(
            pass_row,
            text="👁️",
            font=("Segoe UI Emoji", 10),
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_MUTED,
            relief="flat",
            bd=0,
            cursor="hand2",
            padx=8,
            command=lambda: self.toggle_input_visibility(new_pass_entry, btn_eye_modal),
        )
        btn_eye_modal.pack(side="right", padx=(0, 4))

        def generate_random_pwd():
            chars = string.ascii_letters + string.digits + "@#$!"
            pwd = "".join(secrets.choice(chars) for _ in range(12))
            new_pass_entry.delete(0, tk.END)
            new_pass_entry.insert(0, pwd)
            # Pastikan terbuka terlihat saat diacak agar dev bisa baca
            new_pass_entry.config(show="")
            btn_eye_modal.config(text="🔒", fg=COLOR_PRIMARY)

        btn_random = tk.Button(
            input_wrapper,
            text="🎲 Acak Sandi",
            font=FONT_SMALL,
            bg=COLOR_BG_CARD_HOVER,
            fg=COLOR_TEXT_PRIMARY,
            relief="flat",
            padx=8,
            pady=5,
            cursor="hand2",
            command=generate_random_pwd,
        )
        btn_random.pack(side="left", padx=(8, 0))

        status_lbl = tk.Label(card, text="", font=FONT_SMALL, fg=COLOR_DANGER, bg=COLOR_BG_CARD, wraplength=420)
        status_lbl.pack(anchor="w", pady=(8, 12))

        actions = tk.Frame(card, bg=COLOR_BG_CARD)
        actions.pack(fill="x", pady=(10, 0))

        def submit_reset():
            new_pass = new_pass_entry.get().strip()
            if not new_pass or len(new_pass) < 6:
                status_lbl.config(text="Password baru harus diisi minimal 6 karakter!", fg=COLOR_DANGER)
                return

            status_lbl.config(text="Memperbarui password di database...", fg=COLOR_TEXT_MUTED)
            btn_save.config(state="disabled")

            def worker():
                headers = {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": f"Bearer {self.auth_token}",
                }
                try:
                    resp = self.api_request(
                        "POST",
                        f"/admins/{admin_id}/reset-password",
                        json={"new_password": new_pass},
                        headers=headers,
                        timeout=12,
                    )
                    data = resp.json() if resp.headers.get("content-type", "").startswith("application/json") else {}

                    if resp.status_code == 200 and data.get("status") == "success":
                        def on_done():
                            modal.destroy()
                            messagebox.showinfo(
                                "Password Berhasil Diganti!",
                                f"Password untuk admin '{admin_name}' ({admin_email}) berhasil diganti!\n\n"
                                f"Password Baru: {new_pass}\n\n"
                                f"Admin sekarang dapat langsung login di website dengan password baru ini.",
                                parent=self,
                            )
                            self.fetch_admins()
                            self.fetch_logs()
                        self.after(0, on_done)
                    else:
                        err = data.get("message") or f"Gagal mengubah password (HTTP {resp.status_code})"
                        self.after(0, lambda: [status_lbl.config(text=err, fg=COLOR_DANGER), btn_save.config(state="normal")])
                except Exception as e:
                    self.after(0, lambda: [status_lbl.config(text=f"Koneksi gagal: {str(e)}", fg=COLOR_DANGER), btn_save.config(state="normal")])

            threading.Thread(target=worker, daemon=True).start()

        btn_save = tk.Button(actions, text="Simpan Password Baru", font=FONT_BODY_BOLD, bg=COLOR_PRIMARY, fg="#FFFFFF", relief="flat", padx=16, pady=8, cursor="hand2", command=submit_reset)
        btn_save.pack(side="left")

        btn_cancel = tk.Button(actions, text="Batal", font=FONT_BODY, bg=COLOR_BG_CARD_HOVER, fg=COLOR_TEXT_PRIMARY, relief="flat", padx=14, pady=8, cursor="hand2", command=modal.destroy)
        btn_cancel.pack(side="right")

    # -------------------------------------------------------------------------
    # MODAL: TAMBAH ADMIN BARU (DENGAN TOMBOL LIHAT PASSWORD 👁️)
    # -------------------------------------------------------------------------
    def open_add_admin_modal(self):
        modal = tk.Toplevel(self)
        modal.title("Tambah Akun Admin Baru")
        modal.geometry("500x440")
        modal.minsize(460, 400)
        modal.configure(bg=COLOR_BG_DARK)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_BG_CARD, padx=28, pady=24, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=20, pady=20)

        tk.Label(card, text="➕ Tambah Admin Baru", font=FONT_TITLE, fg=COLOR_TEXT_PRIMARY, bg=COLOR_BG_CARD).pack(anchor="w", pady=(0, 16))

        tk.Label(card, text="NAMA LENGKAP ADMIN:", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_BG_CARD).pack(anchor="w", pady=(0, 4))
        name_entry = tk.Entry(card, font=FONT_BODY, bg=COLOR_BG_INPUT, fg=COLOR_TEXT_PRIMARY, insertbackground=COLOR_TEXT_PRIMARY, relief="flat", highlightthickness=1, highlightbackground=COLOR_BORDER, highlightcolor=COLOR_PRIMARY)
        name_entry.pack(fill="x", ipady=5, pady=(0, 12))

        tk.Label(card, text="EMAIL ADMIN:", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_BG_CARD).pack(anchor="w", pady=(0, 4))
        email_entry = tk.Entry(card, font=FONT_BODY, bg=COLOR_BG_INPUT, fg=COLOR_TEXT_PRIMARY, insertbackground=COLOR_TEXT_PRIMARY, relief="flat", highlightthickness=1, highlightbackground=COLOR_BORDER, highlightcolor=COLOR_PRIMARY)
        email_entry.pack(fill="x", ipady=5, pady=(0, 12))

        tk.Label(card, text="PASSWORD AWAL (Minimal 6 Karakter):", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_BG_CARD).pack(anchor="w", pady=(0, 4))

        pwd_row = tk.Frame(card, bg=COLOR_BG_INPUT, highlightbackground=COLOR_BORDER, highlightthickness=1)
        pwd_row.pack(fill="x", pady=(0, 14))

        pwd_entry = tk.Entry(pwd_row, font=FONT_BODY, bg=COLOR_BG_INPUT, fg=COLOR_TEXT_PRIMARY, insertbackground=COLOR_TEXT_PRIMARY, relief="flat", show="•")
        pwd_entry.pack(side="left", fill="x", expand=True, ipady=5, padx=(8, 0))

        btn_eye_add = tk.Button(
            pwd_row,
            text="👁️",
            font=("Segoe UI Emoji", 10),
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_MUTED,
            relief="flat",
            bd=0,
            cursor="hand2",
            padx=8,
            command=lambda: self.toggle_input_visibility(pwd_entry, btn_eye_add),
        )
        btn_eye_add.pack(side="right", padx=(0, 4))

        status_lbl = tk.Label(card, text="", font=FONT_SMALL, fg=COLOR_DANGER, bg=COLOR_BG_CARD, wraplength=420)
        status_lbl.pack(anchor="w", pady=(0, 10))

        actions = tk.Frame(card, bg=COLOR_BG_CARD)
        actions.pack(fill="x")

        def submit():
            name = name_entry.get().strip()
            email = email_entry.get().strip()
            pwd = pwd_entry.get().strip()

            if not name or not email or not pwd:
                status_lbl.config(text="Semua kolom wajib diisi!", fg=COLOR_DANGER)
                return

            status_lbl.config(text="Menyimpan admin baru...", fg=COLOR_TEXT_MUTED)
            btn_sub.config(state="disabled")

            def worker():
                headers = {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": f"Bearer {self.auth_token}",
                }
                try:
                    resp = self.api_request(
                        "POST",
                        "/admins",
                        json={"name": name, "email": email, "password": pwd},
                        headers=headers,
                        timeout=12,
                    )
                    data = resp.json() if resp.headers.get("content-type", "").startswith("application/json") else {}

                    if resp.status_code == 201 and data.get("status") == "success":
                        def on_done():
                            modal.destroy()
                            messagebox.showinfo("Berhasil", f"Admin '{name}' ({email}) berhasil dibuat!", parent=self)
                            self.fetch_admins()
                            self.fetch_logs()
                        self.after(0, on_done)
                    else:
                        err = data.get("message") or f"Gagal menambahkan admin (HTTP {resp.status_code})"
                        self.after(0, lambda: [status_lbl.config(text=err, fg=COLOR_DANGER), btn_sub.config(state="normal")])
                except Exception as e:
                    self.after(0, lambda: [status_lbl.config(text=f"Error: {str(e)}", fg=COLOR_DANGER), btn_sub.config(state="normal")])

            threading.Thread(target=worker, daemon=True).start()

        btn_sub = tk.Button(actions, text="Simpan Admin", font=FONT_BODY_BOLD, bg=COLOR_SUCCESS, fg="#FFFFFF", relief="flat", padx=16, pady=7, cursor="hand2", command=submit)
        btn_sub.pack(side="left")

        btn_can = tk.Button(actions, text="Batal", font=FONT_BODY, bg=COLOR_BG_CARD_HOVER, fg=COLOR_TEXT_PRIMARY, relief="flat", padx=14, pady=7, cursor="hand2", command=modal.destroy)
        btn_can.pack(side="right")

    # -------------------------------------------------------------------------
    # AKSI: HAPUS ADMIN
    # -------------------------------------------------------------------------
    def handle_delete_admin(self):
        selected = self.admins_tree.selection()
        if not selected:
            messagebox.showwarning("Pilih Admin", "Silakan klik salah satu akun admin yang ingin dihapus!", parent=self)
            return

        item = self.admins_tree.item(selected[0])
        values = item.get("values", [])
        admin_id = values[0]
        admin_name = values[1]
        admin_email = values[2]

        if not messagebox.askyesno(
            "Konfirmasi Hapus Admin",
            f"PERINGATAN:\nApakah Anda yakin ingin menghapus akun admin berikut?\n\n"
            f"Nama: {admin_name}\n"
            f"Email: {admin_email}\n\n"
            f"Tindakan ini tidak dapat dibatalkan!",
            icon="warning",
            parent=self,
        ):
            return

        def worker():
            headers = {
                "Accept": "application/json",
                "Authorization": f"Bearer {self.auth_token}",
            }
            try:
                resp = self.api_request("DELETE", f"/admins/{admin_id}", headers=headers, timeout=12)
                data = resp.json() if resp.headers.get("content-type", "").startswith("application/json") else {}

                if resp.status_code == 200 and data.get("status") == "success":
                    def on_done():
                        messagebox.showinfo("Berhasil", data.get("message", "Admin berhasil dihapus."), parent=self)
                        self.fetch_admins()
                        self.fetch_logs()
                    self.after(0, on_done)
                else:
                    err = data.get("message") or f"Gagal menghapus admin (HTTP {resp.status_code})"
                    self.after(0, lambda: messagebox.showerror("Gagal", err, parent=self))
            except Exception as e:
                self.after(0, lambda: messagebox.showerror("Error", f"Koneksi gagal: {str(e)}", parent=self))

        threading.Thread(target=worker, daemon=True).start()

    # -------------------------------------------------------------------------
    # AKSI: BERSIHKAN RIWAYAT LOG
    # -------------------------------------------------------------------------
    def handle_clear_logs(self):
        if not messagebox.askyesno(
            "Konfirmasi Bersihkan Log",
            "Apakah Anda yakin ingin menghapus SELURUH riwayat login admin dari database?",
            icon="warning",
            parent=self,
        ):
            return

        def worker():
            headers = {
                "Accept": "application/json",
                "Authorization": f"Bearer {self.auth_token}",
            }
            try:
                resp = self.api_request("DELETE", "/logs", headers=headers, timeout=12)
                data = resp.json() if resp.headers.get("content-type", "").startswith("application/json") else {}

                if resp.status_code == 200 and data.get("status") == "success":
                    self.after(0, lambda: [
                        messagebox.showinfo("Berhasil", data.get("message"), parent=self),
                        self.fetch_logs(),
                        self.fetch_stats(),
                    ])
                else:
                    err = data.get("message") or "Gagal membersihkan riwayat."
                    self.after(0, lambda: messagebox.showerror("Gagal", err, parent=self))
            except Exception as e:
                self.after(0, lambda: messagebox.showerror("Error", str(e), parent=self))

        threading.Thread(target=worker, daemon=True).start()

    # -------------------------------------------------------------------------
    # FETCH DATA
    # -------------------------------------------------------------------------
    def refresh_all_data(self):
        self.fetch_stats()
        self.fetch_logs()
        self.fetch_admins()

    def fetch_stats(self):
        def worker():
            headers = {"Accept": "application/json", "Authorization": f"Bearer {self.auth_token}"}
            try:
                resp = self.api_request("GET", "/stats", headers=headers, timeout=10)
                if resp.status_code == 200:
                    d = resp.json().get("data", {})
                    self.after(0, lambda: [
                        self.card_admins_val.config(text=str(d.get("total_admins", 0))),
                        self.card_logs_val.config(text=str(d.get("total_logs", 0))),
                        self.card_success_val.config(text=str(d.get("successful_logs", 0))),
                        self.card_failed_val.config(text=str(d.get("failed_logs", 0))),
                    ])
            except Exception:
                pass

        threading.Thread(target=worker, daemon=True).start()

    def fetch_logs(self):
        search_kw = self.log_search_var.get().strip()
        status_flt = self.log_status_filter_var.get()

        def worker():
            params = {}
            if search_kw:
                params["search"] = search_kw
            if status_flt in ["success", "failed"]:
                params["status"] = status_flt

            headers = {"Accept": "application/json", "Authorization": f"Bearer {self.auth_token}"}
            try:
                resp = self.api_request("GET", "/logs", params=params, headers=headers, timeout=12)
                if resp.status_code == 200:
                    logs = resp.json().get("data", [])
                    raw_map = {item["id"]: item for item in logs}

                    def update_tree():
                        self._logs_raw_map = raw_map
                        for row in self.logs_tree.get_children():
                            self.logs_tree.delete(row)

                        for l in logs:
                            is_success = l.get("status") == "success"
                            status_badge = "🟢 SUCCESS" if is_success else "🔴 FAILED"
                            tag = "success_tag" if is_success else "failed_tag"

                            self.logs_tree.insert(
                                "",
                                "end",
                                values=(
                                    l.get("id"),
                                    l.get("created_at"),
                                    l.get("email"),
                                    l.get("admin_name"),
                                    status_badge,
                                    l.get("ip_address"),
                                    l.get("message"),
                                ),
                                tags=(tag,),
                            )
                    self.after(0, update_tree)
            except Exception:
                pass

        threading.Thread(target=worker, daemon=True).start()

    def fetch_admins(self):
        def worker():
            headers = {"Accept": "application/json", "Authorization": f"Bearer {self.auth_token}"}
            try:
                resp = self.api_request("GET", "/admins", headers=headers, timeout=12)
                if resp.status_code == 200:
                    admins = resp.json().get("data", [])

                    def update_tree():
                        for row in self.admins_tree.get_children():
                            self.admins_tree.delete(row)

                        for a in admins:
                            self.admins_tree.insert(
                                "",
                                "end",
                                values=(
                                    a.get("id"),
                                    a.get("name"),
                                    a.get("email"),
                                    a.get("created_at"),
                                    a.get("last_login_at"),
                                    a.get("last_login_ip"),
                                ),
                            )
                    self.after(0, update_tree)
            except Exception:
                pass

        threading.Thread(target=worker, daemon=True).start()

    # -------------------------------------------------------------------------
    # AUTO REFRESH
    # -------------------------------------------------------------------------
    def toggle_auto_refresh(self):
        self.auto_refresh_enabled = self.auto_refresh_var.get()
        if self.auto_refresh_enabled:
            self.schedule_next_auto_refresh()
        else:
            if self.auto_refresh_timer:
                self.after_cancel(self.auto_refresh_timer)
                self.auto_refresh_timer = None

    def schedule_next_auto_refresh(self):
        if not self.auto_refresh_enabled:
            return
        self.fetch_logs()
        self.fetch_stats()
        self.auto_refresh_timer = self.after(15000, self.schedule_next_auto_refresh)

    # -------------------------------------------------------------------------
    # LOGOUT
    # -------------------------------------------------------------------------
    def handle_logout(self):
        if self.auto_refresh_timer:
            self.after_cancel(self.auto_refresh_timer)
            self.auto_refresh_timer = None
        self.auth_token = None
        self.dev_email = None
        self.show_login_frame()


# =============================================================================
# ENTRY POINT
# =============================================================================
if __name__ == "__main__":
    app = DeveloperPortalApp()
    app.mainloop()

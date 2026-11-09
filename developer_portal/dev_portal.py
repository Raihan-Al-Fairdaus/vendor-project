"""
=============================================================================
DNA ADVERTISING - DEVELOPER PORTAL (DESKTOP APPLICATION)
=============================================================================
Aplikasi Desktop Resmi Khusus Developer / Super Administrator
- Tampilan Modern Sesuai Tema Website DNA: Deep Navy, Gold Accent & Clean White
- Logo DNA Advertising & Ikon Resmi Desktop
- Live Monitoring Riwayat Login Admin dengan Tanggal & Jam Realtime
- Deteksi & Tampilan Sandi yang Digunakan saat Login (👁️ Show/Hide Sandi)
- Manajemen Akun Admin/HRD:
  * Lihat Email & Sandi Akun
  * Salin Kredensial untuk Dikirim ke HRD
  * Blokir / Buka Blokir Akun Admin yang Mencurigakan
  * Tendang / Hapus Akun Tak Dikenal
  * Ganti / Reset Password Instan
=============================================================================
"""

import sys
import os
import json
import time
import datetime
import secrets
import string
import threading
import requests
import tkinter as tk
from tkinter import ttk, messagebox
from PIL import Image, ImageTk

# Aktifkan DPI Awareness agar UI tajam di Windows 10/11
try:
    import ctypes
    ctypes.windll.shcore.SetProcessDpiAwareness(1)
except Exception:
    pass


# =============================================================================
# HELPER PATH RESOURCE (KOMPATIBEL DENGAN PYINSTALLER BUNDLED EXE)
# =============================================================================
def get_resource_path(relative_path):
    """ Dapatkan path absolut resource, baik saat dev maupun setelah dicompile ke .exe """
    try:
        base_path = sys._MEIPASS
    except Exception:
        base_path = os.path.abspath(os.path.dirname(__file__))

    p1 = os.path.join(base_path, relative_path)
    if os.path.exists(p1):
        return p1

    # Cek folder project lokal
    root_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
    p2 = os.path.join(root_dir, relative_path)
    if os.path.exists(p2):
        return p2

    return p1


# =============================================================================
# KONFIGURASI TEMA WARNA BRAND DNA ADVERTISING
# =============================================================================
COLOR_BG            = "#F1F5F9"  # Latar belakang Slate 100
COLOR_CARD          = "#FFFFFF"  # Card putih bersih
COLOR_NAVY          = "#1B3A60"  # Brand Deep Navy DNA
COLOR_NAVY_DARK     = "#0F2744"  # Dark Navy Header
COLOR_NAVY_HOVER    = "#234B7C"  # Hover Navy
COLOR_NAVY_LIGHT    = "#EBF2FA"  # Navy Tint
COLOR_GOLD          = "#D97706"  # Gold / Amber Accent
COLOR_GOLD_LIGHT    = "#FEF3C7"  # Gold Tint
COLOR_TEXT_MAIN     = "#0F172A"  # Slate 900
COLOR_TEXT_MUTED    = "#64748B"  # Slate 500
COLOR_BORDER        = "#E2E8F0"  # Slate 200
COLOR_INPUT_BG      = "#FFFFFF"  # Input field
COLOR_INPUT_BORDER  = "#CBD5E1"  # Border Slate 300
COLOR_SUCCESS       = "#10B981"  # Emerald 500 (Aktif / Sukses)
COLOR_SUCCESS_LIGHT = "#D1FAE5"
COLOR_DANGER        = "#EF4444"  # Red 500 (Blokir / Hapus)
COLOR_DANGER_HOVER  = "#DC2626"
COLOR_DANGER_LIGHT  = "#FEE2E2"
COLOR_WARNING       = "#F59E0B"  # Amber 500

FONT_TITLE   = ("Segoe UI", 16, "bold")
FONT_HEADING = ("Segoe UI", 12, "bold")
FONT_BODY    = ("Segoe UI", 10)
FONT_BODY_BOLD = ("Segoe UI", 10, "bold")
FONT_SMALL   = ("Segoe UI", 9)
FONT_MONO    = ("Consolas", 10)

DEFAULT_SERVER_URL = "https://dna-vendor.vercel.app"
DEFAULT_SECRET_KEY = "dna_dev_sec_2026_secret_key_dna_portal"
CONFIG_FILE = os.path.join(os.path.expanduser("~"), ".dna_dev_portal_config.json")


def load_local_config():
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


def save_local_config(data):
    try:
        with open(CONFIG_FILE, "w", encoding="utf-8") as f:
            json.dump(data, f, indent=2)
    except Exception:
        pass


# =============================================================================
# KELAS UTAMA APLIKASI
# =============================================================================
class DeveloperPortalApp(tk.Tk):
    def __init__(self):
        super().__init__()
        self.title("DNA Advertising - Developer Central Security Portal")
        self.geometry("1180x760")
        self.minsize(1020, 660)
        self.configure(bg=COLOR_BG)

        # Set Window Icon
        ico_path = get_resource_path("dna_icon.ico")
        if os.path.exists(ico_path):
            try:
                self.iconbitmap(ico_path)
            except Exception:
                pass

        # State Aplikasi
        self.local_config = load_local_config()
        self.server_url = self.local_config.get("server_url", DEFAULT_SERVER_URL)
        self.secret_key = DEFAULT_SECRET_KEY
        self.auth_token = None
        self.dev_email = None
        self.auto_refresh_timer = None
        self.auto_refresh_enabled = False

        # State visibilitas sandi di tabel
        self.show_passwords_logs = True
        self.show_passwords_admins = True

        # Cache data
        self.raw_logs_data = []
        self.raw_admins_data = []

        # Load Logo Assets
        self.logo_img_login = None
        self.logo_img_header = None
        self.load_brand_assets()

        self.setup_styles()
        self.show_login_frame()

    def load_brand_assets(self):
        logo_path = get_resource_path(os.path.join("public", "images", "dna-logo.png"))
        if not os.path.exists(logo_path):
            logo_path = get_resource_path(os.path.join("images", "dna-logo.png"))

        if os.path.exists(logo_path):
            try:
                pil_logo = Image.open(logo_path)
                # Logo untuk Login: width ~220px
                w, h = pil_logo.size
                ratio = h / w
                target_w = 220
                target_h = int(target_w * ratio)
                img_resized = pil_logo.resize((target_w, target_h), Image.Resampling.LANCZOS)
                self.logo_img_login = ImageTk.PhotoImage(img_resized)

                # Logo untuk Header: width ~140px
                hdr_w = 140
                hdr_h = int(hdr_w * ratio)
                img_hdr = pil_logo.resize((hdr_w, hdr_h), Image.Resampling.LANCZOS)
                self.logo_img_header = ImageTk.PhotoImage(img_hdr)
            except Exception as e:
                print("Notice loading logo:", e)

    # -------------------------------------------------------------------------
    # STYLING TTK
    # -------------------------------------------------------------------------
    def setup_styles(self):
        self.style = ttk.Style(self)
        try:
            self.style.theme_use("clam")
        except Exception:
            pass

        self.style.configure("TNotebook", background=COLOR_BG, borderwidth=0)
        self.style.configure(
            "TNotebook.Tab",
            background=COLOR_CARD,
            foreground=COLOR_TEXT_MUTED,
            font=("Segoe UI", 10, "bold"),
            padding=[24, 11],
            borderwidth=0,
        )
        self.style.map(
            "TNotebook.Tab",
            background=[("selected", COLOR_NAVY), ("active", COLOR_NAVY_LIGHT)],
            foreground=[("selected", "#FFFFFF"), ("active", COLOR_NAVY)],
        )

        self.style.configure(
            "Treeview",
            background=COLOR_CARD,
            foreground=COLOR_TEXT_MAIN,
            fieldbackground=COLOR_CARD,
            font=("Segoe UI", 9),
            rowheight=34,
            borderwidth=0,
        )
        self.style.configure(
            "Treeview.Heading",
            background=COLOR_NAVY,
            foreground="#FFFFFF",
            font=("Segoe UI", 9, "bold"),
            relief="flat",
            padding=[8, 8],
        )
        self.style.map("Treeview.Heading", background=[("active", COLOR_NAVY_HOVER)])
        self.style.map(
            "Treeview",
            background=[("selected", COLOR_NAVY_LIGHT)],
            foreground=[("selected", COLOR_NAVY)],
        )

    # -------------------------------------------------------------------------
    # HELPER HTTP DUAL-ROUTE
    # -------------------------------------------------------------------------
    def api_request(self, method: str, subpath: str, **kwargs):
        base = self.server_url.rstrip("/")
        timeout = kwargs.pop("timeout", 15)

        url1 = f"{base}/api/developer{subpath}"
        try:
            resp = requests.request(method, url1, timeout=timeout, **kwargs)
            if resp.status_code != 404:
                return resp
        except Exception:
            pass

        url2 = f"{base}/developer{subpath}"
        return requests.request(method, url2, timeout=timeout, **kwargs)

    # -------------------------------------------------------------------------
    # TAMPILAN LOGIN DEVELOPER
    # -------------------------------------------------------------------------
    def show_login_frame(self):
        if hasattr(self, "main_frame") and self.main_frame:
            self.main_frame.destroy()

        self.login_frame = tk.Frame(self, bg=COLOR_BG)
        self.login_frame.pack(fill="both", expand=True)

        center = tk.Frame(self.login_frame, bg=COLOR_BG)
        center.place(relx=0.5, rely=0.5, anchor="center")

        card = tk.Frame(
            center,
            bg=COLOR_CARD,
            padx=46,
            pady=36,
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
        )
        card.pack()

        # Logo DNA Advertising
        if self.logo_img_login:
            logo_lbl = tk.Label(card, image=self.logo_img_login, bg=COLOR_CARD)
            logo_lbl.pack(anchor="center", pady=(0, 14))

        # Badge Keamanan
        badge = tk.Frame(card, bg=COLOR_NAVY_LIGHT, padx=14, pady=4)
        badge.pack(anchor="center", pady=(0, 10))
        tk.Label(
            badge,
            text="⚡ DNA DEVELOPER SECURITY ACCESS",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_NAVY,
            bg=COLOR_NAVY_LIGHT,
        ).pack()

        tk.Label(
            card,
            text="DNA Developer Portal",
            font=FONT_TITLE,
            fg=COLOR_NAVY,
            bg=COLOR_CARD,
        ).pack(anchor="center")

        tk.Label(
            card,
            text="Aplikasi Khusus Developer untuk Monitoring Kredensial & Keamanan Admin",
            font=FONT_SMALL,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_CARD,
        ).pack(anchor="center", pady=(4, 20))

        # Email
        tk.Label(
            card,
            text="EMAIL DEVELOPER",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_CARD,
        ).pack(anchor="w", pady=(0, 4))

        self.email_entry = tk.Entry(
            card,
            font=FONT_BODY,
            bg=COLOR_INPUT_BG,
            fg=COLOR_TEXT_MAIN,
            insertbackground=COLOR_TEXT_MAIN,
            relief="flat",
            highlightthickness=1,
            highlightbackground=COLOR_INPUT_BORDER,
            highlightcolor=COLOR_NAVY,
            width=42,
        )
        saved_email = self.local_config.get("remember_email", "developer@dna-advertising.com")
        self.email_entry.insert(0, saved_email)
        self.email_entry.pack(fill="x", ipady=7, pady=(0, 14))

        # Password
        tk.Label(
            card,
            text="PASSWORD DEVELOPER",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_CARD,
        ).pack(anchor="w", pady=(0, 4))

        pass_row = tk.Frame(card, bg=COLOR_CARD, highlightbackground=COLOR_INPUT_BORDER, highlightthickness=1)
        pass_row.pack(fill="x", pady=(0, 18))

        self.pass_entry = tk.Entry(
            pass_row,
            font=FONT_BODY,
            bg=COLOR_INPUT_BG,
            fg=COLOR_TEXT_MAIN,
            insertbackground=COLOR_TEXT_MAIN,
            relief="flat",
            show="•",
        )
        self.pass_entry.insert(0, "DevDNA@2026!")
        self.pass_entry.pack(side="left", fill="x", expand=True, ipady=7, padx=(8, 0))

        self.btn_eye_pass = tk.Button(
            pass_row,
            text="👁️",
            font=("Segoe UI Emoji", 10),
            bg=COLOR_CARD,
            fg=COLOR_TEXT_MUTED,
            relief="flat",
            bd=0,
            cursor="hand2",
            padx=10,
            command=lambda: self.toggle_input_visibility(self.pass_entry, self.btn_eye_pass),
        )
        self.btn_eye_pass.pack(side="right", padx=(0, 2))

        self.login_status_lbl = tk.Label(
            card,
            text="",
            font=FONT_SMALL,
            fg=COLOR_DANGER,
            bg=COLOR_CARD,
            wraplength=380,
            justify="center",
        )
        self.login_status_lbl.pack(pady=(0, 12))

        # Tombol Masuk
        self.btn_login = tk.Button(
            card,
            text="MASUK KE DEVELOPER PORTAL",
            font=FONT_BODY_BOLD,
            bg=COLOR_NAVY,
            fg="#FFFFFF",
            activebackground=COLOR_NAVY_HOVER,
            activeforeground="#FFFFFF",
            relief="flat",
            cursor="hand2",
            command=self.handle_login_clicked,
        )
        self.btn_login.pack(fill="x", ipady=9)

        # Pengaturan Server
        gear_frame = tk.Frame(card, bg=COLOR_CARD)
        gear_frame.pack(fill="x", pady=(14, 0))

        btn_settings = tk.Button(
            gear_frame,
            text="⚙️ Pengaturan Server",
            font=FONT_SMALL,
            bg=COLOR_CARD,
            fg=COLOR_TEXT_MUTED,
            relief="flat",
            bd=0,
            cursor="hand2",
            command=self.open_server_settings_modal,
        )
        btn_settings.pack(side="right")

        self.pass_entry.bind("<Return>", lambda e: self.handle_login_clicked())
        self.email_entry.bind("<Return>", lambda e: self.handle_login_clicked())

    def toggle_input_visibility(self, entry_widget: tk.Entry, btn_widget: tk.Button):
        if entry_widget.cget("show") == "":
            entry_widget.config(show="•")
            btn_widget.config(text="👁️", fg=COLOR_TEXT_MUTED)
        else:
            entry_widget.config(show="")
            btn_widget.config(text="🙈", fg=COLOR_NAVY)

    def open_server_settings_modal(self):
        modal = tk.Toplevel(self)
        modal.title("Pengaturan Server API")
        modal.geometry("480x250")
        modal.configure(bg=COLOR_BG)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_CARD, padx=24, pady=20, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=16, pady=16)

        tk.Label(card, text="⚙️ Konfigurasi URL Server", font=FONT_HEADING, fg=COLOR_NAVY, bg=COLOR_CARD).pack(anchor="w", pady=(0, 10))

        tk.Label(card, text="URL SERVER (Vercel atau Localhost):", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w", pady=(0, 4))
        url_entry = tk.Entry(card, font=FONT_BODY, bg=COLOR_INPUT_BG, fg=COLOR_TEXT_MAIN, relief="flat", highlightthickness=1, highlightbackground=COLOR_INPUT_BORDER, highlightcolor=COLOR_NAVY)
        url_entry.insert(0, self.server_url)
        url_entry.pack(fill="x", ipady=6, pady=(0, 16))

        btn_row = tk.Frame(card, bg=COLOR_CARD)
        btn_row.pack(fill="x")

        def save():
            new_url = url_entry.get().strip().rstrip("/")
            if new_url:
                self.server_url = new_url
                self.local_config["server_url"] = new_url
                save_local_config(self.local_config)
            modal.destroy()

        tk.Button(btn_row, text="Simpan", font=FONT_BODY_BOLD, bg=COLOR_NAVY, fg="#FFFFFF", relief="flat", padx=16, pady=6, cursor="hand2", command=save).pack(side="left")
        tk.Button(btn_row, text="Batal", font=FONT_BODY, bg=COLOR_BG, fg=COLOR_TEXT_MAIN, relief="flat", padx=14, pady=6, cursor="hand2", command=modal.destroy).pack(side="right")

    def handle_login_clicked(self):
        email = self.email_entry.get().strip()
        pwd = self.pass_entry.get()

        if not email or not pwd:
            self.login_status_lbl.config(text="Email dan Password wajib diisi!", fg=COLOR_DANGER)
            return

        self.login_status_lbl.config(text="Menghubungkan ke server DNA...", fg=COLOR_TEXT_MUTED)
        self.btn_login.config(state="disabled", text="MEMVERIFIKASI...")

        def worker():
            headers = {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Dev-Secret-Key": self.secret_key,
            }
            payload = {
                "email": email,
                "password": pwd,
                "secret_key": self.secret_key,
            }

            try:
                resp = self.api_request("POST", "/login", json=payload, headers=headers, timeout=12)
                if resp.status_code == 200:
                    data = resp.json().get("data", {})
                    self.auth_token = data.get("token")
                    self.dev_email = email
                    self.local_config["remember_email"] = email
                    save_local_config(self.local_config)
                    self.after(0, self.on_login_success)
                else:
                    try:
                        err_msg = resp.json().get("message", "Login gagal.")
                    except Exception:
                        err_msg = f"Server Error (HTTP {resp.status_code})"
                    self.after(0, lambda: self.set_login_error(err_msg))
            except Exception as e:
                self.after(0, lambda: self.set_login_error(f"Gagal koneksi ke server: {str(e)}"))

        threading.Thread(target=worker, daemon=True).start()

    def set_login_error(self, message):
        self.login_status_lbl.config(text=message, fg=COLOR_DANGER)
        self.btn_login.config(state="normal", text="MASUK KE DEVELOPER PORTAL")

    def on_login_success(self):
        self.login_frame.destroy()
        self.show_dashboard_frame()

    # -------------------------------------------------------------------------
    # TAMPILAN DASHBOARD UTAMA DEVELOPER
    # -------------------------------------------------------------------------
    def show_dashboard_frame(self):
        self.main_frame = tk.Frame(self, bg=COLOR_BG)
        self.main_frame.pack(fill="both", expand=True)

        # 1. HEADER BAR NAVY
        header = tk.Frame(self.main_frame, bg=COLOR_NAVY_DARK, padx=22, pady=12)
        header.pack(fill="x")

        # Kiri: Logo + Judul
        left_h = tk.Frame(header, bg=COLOR_NAVY_DARK)
        left_h.pack(side="left")

        if self.logo_img_header:
            tk.Label(left_h, image=self.logo_img_header, bg=COLOR_NAVY_DARK).pack(side="left", padx=(0, 14))

        title_box = tk.Frame(left_h, bg=COLOR_NAVY_DARK)
        title_box.pack(side="left")

        tk.Label(
            title_box,
            text="DEVELOPER CENTRAL PORTAL",
            font=("Segoe UI", 12, "bold"),
            fg="#FFFFFF",
            bg=COLOR_NAVY_DARK,
        ).pack(anchor="w")

        tk.Label(
            title_box,
            text="Sistem Pengawas Kredensial & Keamanan Dashboard DNA Advertising",
            font=("Segoe UI", 8),
            fg="#94A3B8",
            bg=COLOR_NAVY_DARK,
        ).pack(anchor="w")

        # Kanan: Jam Realtime + Identitas + Tombol Logout
        right_h = tk.Frame(header, bg=COLOR_NAVY_DARK)
        right_h.pack(side="right")

        # Jam Realtime Live
        self.lbl_clock = tk.Label(
            right_h,
            text="🕒 Memuat waktu...",
            font=("Segoe UI", 9, "bold"),
            fg=COLOR_GOLD,
            bg=COLOR_NAVY_DARK,
            padx=10,
        )
        self.lbl_clock.pack(side="left", padx=(0, 16))
        self.start_realtime_clock()

        dev_badge = tk.Frame(right_h, bg=COLOR_NAVY, padx=10, pady=4)
        dev_badge.pack(side="left", padx=(0, 12))
        tk.Label(
            dev_badge,
            text=f"⚡ {self.dev_email}",
            font=("Segoe UI", 8, "bold"),
            fg="#FFFFFF",
            bg=COLOR_NAVY,
        ).pack()

        btn_logout = tk.Button(
            right_h,
            text="Keluar 🚪",
            font=FONT_SMALL,
            bg="#334155",
            fg="#FFFFFF",
            activebackground=COLOR_DANGER,
            activeforeground="#FFFFFF",
            relief="flat",
            padx=12,
            pady=4,
            cursor="hand2",
            command=self.handle_logout,
        )
        btn_logout.pack(side="right")

        # 2. STATS KPI CARDS
        stats_frame = tk.Frame(self.main_frame, bg=COLOR_BG, padx=22, pady=16)
        stats_frame.pack(fill="x")

        self.stat_admins_lbl  = self.create_stat_card(stats_frame, "👥 Total Admin Terdaftar", "0", COLOR_NAVY)
        self.stat_logs_lbl    = self.create_stat_card(stats_frame, "📋 Total Riwayat Login", "0", "#0284C7")
        self.stat_success_lbl = self.create_stat_card(stats_frame, "🟢 Login Sukses", "0", COLOR_SUCCESS)
        self.stat_failed_lbl  = self.create_stat_card(stats_frame, "🔴 Login Gagal / Mencurigakan", "0", COLOR_DANGER)
        self.stat_today_lbl   = self.create_stat_card(stats_frame, "📅 Aktivitas Hari Ini", "0", COLOR_GOLD)

        # 3. TAB UTAMA
        content_frame = tk.Frame(self.main_frame, bg=COLOR_BG, padx=22, pady=0)
        content_frame.pack(fill="both", expand=True, pady=(0, 16))

        self.notebook = ttk.Notebook(content_frame)
        self.notebook.pack(fill="both", expand=True)

        self.tab_logs = tk.Frame(self.notebook, bg=COLOR_BG, padx=4, pady=8)
        self.tab_admins = tk.Frame(self.notebook, bg=COLOR_BG, padx=4, pady=8)

        self.notebook.add(self.tab_logs, text="  🕒 Riwayat Login Realtime & Sandi  ")
        self.notebook.add(self.tab_admins, text="  🛡️ Manajemen & Keamanan Akun Admin  ")

        self.setup_logs_tab()
        self.setup_admins_tab()

        self.refresh_all_data()

    def start_realtime_clock(self):
        def update_clock():
            now = datetime.datetime.now()
            hari = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"][now.weekday()]
            bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"][now.month - 1]
            time_str = f"🕒 {hari}, {now.day:02d} {bulan} {now.year} | {now.strftime('%H:%M:%S')} WIB"
            if hasattr(self, "lbl_clock") and self.lbl_clock.winfo_exists():
                self.lbl_clock.config(text=time_str)
            self.after(1000, update_clock)
        update_clock()

    def create_stat_card(self, parent, title: str, initial_value: str, color_bar: str):
        card = tk.Frame(parent, bg=COLOR_CARD, highlightbackground=COLOR_BORDER, highlightthickness=1, padx=14, pady=10)
        card.pack(side="left", fill="both", expand=True, padx=4)

        bar = tk.Frame(card, bg=color_bar, height=3)
        bar.pack(fill="x", pady=(0, 6))

        tk.Label(card, text=title, font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w")
        val_lbl = tk.Label(card, text=initial_value, font=("Segoe UI", 16, "bold"), fg=COLOR_TEXT_MAIN, bg=COLOR_CARD)
        val_lbl.pack(anchor="w", pady=(2, 0))
        return val_lbl

    # -------------------------------------------------------------------------
    # TAB 1: RIWAYAT LOGIN REALTIME & SANDI
    # -------------------------------------------------------------------------
    def setup_logs_tab(self):
        toolbar = tk.Frame(self.tab_logs, bg=COLOR_BG, pady=6)
        toolbar.pack(fill="x")

        # Search Box
        tk.Label(toolbar, text="Cari:", font=FONT_BODY_BOLD, fg=COLOR_TEXT_MAIN, bg=COLOR_BG).pack(side="left", padx=(0, 6))
        self.log_search_var = tk.StringVar()
        self.log_search_entry = tk.Entry(
            toolbar,
            textvariable=self.log_search_var,
            font=FONT_BODY,
            bg=COLOR_INPUT_BG,
            fg=COLOR_TEXT_MAIN,
            relief="flat",
            highlightthickness=1,
            highlightbackground=COLOR_INPUT_BORDER,
            highlightcolor=COLOR_NAVY,
            width=22,
        )
        self.log_search_entry.pack(side="left", ipady=4, padx=(0, 10))
        self.log_search_entry.bind("<Return>", lambda e: self.fetch_logs())

        # Filter Status
        tk.Label(toolbar, text="Status:", font=FONT_BODY_BOLD, fg=COLOR_TEXT_MAIN, bg=COLOR_BG).pack(side="left", padx=(6, 6))
        self.log_status_filter_var = tk.StringVar(value="Semua")
        status_dropdown = ttk.Combobox(
            toolbar,
            textvariable=self.log_status_filter_var,
            values=["Semua", "success", "failed"],
            state="readonly",
            width=10,
            font=FONT_BODY,
        )
        status_dropdown.pack(side="left", padx=(0, 10))
        status_dropdown.bind("<<ComboboxSelected>>", lambda e: self.fetch_logs())

        btn_filter = tk.Button(
            toolbar, text="🔍 Cari", font=FONT_SMALL, bg=COLOR_CARD, fg=COLOR_TEXT_MAIN,
            relief="flat", padx=10, pady=4, cursor="hand2", highlightbackground=COLOR_BORDER, highlightthickness=1, command=self.fetch_logs
        )
        btn_filter.pack(side="left", padx=(0, 8))

        btn_refresh = tk.Button(
            toolbar, text="🔄 Refresh", font=FONT_SMALL, bg=COLOR_NAVY, fg="#FFFFFF",
            relief="flat", padx=12, pady=4, cursor="hand2", command=self.fetch_logs
        )
        btn_refresh.pack(side="left", padx=(0, 10))

        # Toggle Tampilkan/Sembunyikan Sandi Masuk
        self.btn_toggle_pass_logs = tk.Button(
            toolbar,
            text="👁️ Sembunyikan Sandi",
            font=FONT_SMALL,
            bg=COLOR_CARD,
            fg=COLOR_NAVY,
            relief="flat",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            padx=10,
            pady=4,
            cursor="hand2",
            command=self.toggle_passwords_logs,
        )
        self.btn_toggle_pass_logs.pack(side="left", padx=(0, 10))

        self.auto_refresh_var = tk.BooleanVar(value=True)
        chk_auto = tk.Checkbutton(
            toolbar,
            text="Auto-refresh Realtime (10 dtk)",
            variable=self.auto_refresh_var,
            command=self.toggle_auto_refresh,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG,
            selectcolor=COLOR_CARD,
            font=FONT_SMALL,
        )
        chk_auto.pack(side="left")

        btn_clear = tk.Button(
            toolbar,
            text="🗑️ Bersihkan Riwayat",
            font=FONT_SMALL,
            bg=COLOR_CARD,
            fg=COLOR_DANGER,
            relief="flat",
            padx=10,
            pady=4,
            cursor="hand2",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            command=self.handle_clear_logs,
        )
        btn_clear.pack(side="right")

        # TABEL LOGS
        table_frame = tk.Frame(self.tab_logs, bg=COLOR_CARD, highlightbackground=COLOR_BORDER, highlightthickness=1)
        table_frame.pack(fill="both", expand=True)

        columns = ("id", "waktu", "email", "admin_name", "sandi", "status", "ip", "pesan")
        self.logs_tree = ttk.Treeview(table_frame, columns=columns, show="headings", selectmode="browse")

        self.logs_tree.heading("id", text="ID", anchor="center")
        self.logs_tree.heading("waktu", text="Waktu & Jam Realtime", anchor="w")
        self.logs_tree.heading("email", text="Email Login", anchor="w")
        self.logs_tree.heading("admin_name", text="Nama Admin / HRD", anchor="w")
        self.logs_tree.heading("sandi", text="Sandi yang Digunakan", anchor="w")
        self.logs_tree.heading("status", text="Status", anchor="center")
        self.logs_tree.heading("ip", text="IP Address", anchor="w")
        self.logs_tree.heading("pesan", text="Keterangan / Pesan", anchor="w")

        self.logs_tree.column("id", width=45, anchor="center")
        self.logs_tree.column("waktu", width=160, anchor="w")
        self.logs_tree.column("email", width=210, anchor="w")
        self.logs_tree.column("admin_name", width=140, anchor="w")
        self.logs_tree.column("sandi", width=140, anchor="w")
        self.logs_tree.column("status", width=95, anchor="center")
        self.logs_tree.column("ip", width=110, anchor="w")
        self.logs_tree.column("pesan", width=250, anchor="w")

        vsb = ttk.Scrollbar(table_frame, orient="vertical", command=self.logs_tree.yview)
        self.logs_tree.configure(yscrollcommand=vsb.set)
        self.logs_tree.pack(side="left", fill="both", expand=True)
        vsb.pack(side="right", fill="y")

        self.logs_tree.bind("<Double-1>", self.on_log_double_click)

        # Tags styling
        self.logs_tree.tag_configure("status_success", background="#F0FDF4", foreground="#166534")
        self.logs_tree.tag_configure("status_failed", background="#FEF2F2", foreground="#991B1B")

    def toggle_passwords_logs(self):
        self.show_passwords_logs = not self.show_passwords_logs
        if self.show_passwords_logs:
            self.btn_toggle_pass_logs.config(text="👁️ Sembunyikan Sandi")
        else:
            self.btn_toggle_pass_logs.config(text="👁️ Tampilkan Sandi")
        self.render_logs_table()

    def on_log_double_click(self, event):
        item = self.logs_tree.selection()
        if not item:
            return
        log_id = self.logs_tree.item(item[0], "values")[0]
        match = next((l for l in self.raw_logs_data if str(l.get("id")) == str(log_id)), None)
        if not match:
            return

        modal = tk.Toplevel(self)
        modal.title(f"Detail Riwayat Login #{log_id}")
        modal.geometry("540x450")
        modal.configure(bg=COLOR_BG)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_CARD, padx=26, pady=20, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=16, pady=16)

        tk.Label(card, text=f"🔍 Detail Riwayat Login #{log_id}", font=FONT_TITLE, fg=COLOR_NAVY, bg=COLOR_CARD).pack(anchor="w", pady=(0, 14))

        info_frame = tk.Frame(card, bg=COLOR_CARD)
        info_frame.pack(fill="both", expand=True)

        rows = [
            ("Tanggal & Jam:", match.get("created_at", "-")),
            ("Nama Admin / HRD:", match.get("admin_name", "-")),
            ("Email Login:", match.get("email", "-")),
            ("Sandi Dimasukkan:", match.get("password_used", "-")),
            ("Status Login:", "🟢 BERHASIL" if match.get("status") == "success" else "🔴 GAGAL / DITOLAK"),
            ("IP Address:", match.get("ip_address", "-")),
            ("Keterangan:", match.get("message", "-")),
            ("Perangkat / Browser:", match.get("user_agent", "-")),
        ]

        for i, (k, v) in enumerate(rows):
            tk.Label(info_frame, text=k, font=("Segoe UI", 9, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).grid(row=i, column=0, sticky="w", pady=4)
            val_lbl = tk.Label(info_frame, text=str(v), font=FONT_BODY, fg=COLOR_TEXT_MAIN, bg=COLOR_CARD, wraplength=340, justify="left")
            val_lbl.grid(row=i, column=1, sticky="w", padx=(10, 0), pady=4)

        btn_row = tk.Frame(card, bg=COLOR_CARD)
        btn_row.pack(fill="x", pady=(14, 0))

        pwd_val = match.get("password_used", "")
        if pwd_val and pwd_val != "-":
            def copy_pwd():
                self.clipboard_clear()
                self.clipboard_append(pwd_val)
                messagebox.showinfo("Tersalin", f"Sandi '{pwd_val}' berhasil disalin ke clipboard!", parent=modal)

            tk.Button(btn_row, text="📋 Salin Sandi", font=FONT_SMALL, bg=COLOR_NAVY_LIGHT, fg=COLOR_NAVY, relief="flat", padx=12, pady=5, cursor="hand2", command=copy_pwd).pack(side="left")

        tk.Button(btn_row, text="Tutup", font=FONT_SMALL, bg=COLOR_NAVY, fg="#FFFFFF", relief="flat", padx=16, pady=5, cursor="hand2", command=modal.destroy).pack(side="right")

    # -------------------------------------------------------------------------
    # TAB 2: MANAJEMEN & KEAMANAN AKUN ADMIN
    # -------------------------------------------------------------------------
    def setup_admins_tab(self):
        toolbar = tk.Frame(self.tab_admins, bg=COLOR_BG, pady=6)
        toolbar.pack(fill="x")

        # 1. Tombol Blokir / Buka Blokir
        btn_block = tk.Button(
            toolbar,
            text="🚫 Blokir / Buka Blokir Akun",
            font=FONT_BODY_BOLD,
            bg=COLOR_CARD,
            fg=COLOR_DANGER,
            relief="flat",
            highlightbackground=COLOR_DANGER,
            highlightthickness=1,
            padx=12,
            pady=5,
            cursor="hand2",
            command=self.handle_toggle_block_admin,
        )
        btn_block.pack(side="left", padx=(0, 8))

        # 2. Tombol Reset / Ganti Password
        btn_reset = tk.Button(
            toolbar,
            text="🔑 Ganti / Reset Sandi",
            font=FONT_BODY_BOLD,
            bg=COLOR_NAVY,
            fg="#FFFFFF",
            relief="flat",
            padx=12,
            pady=5,
            cursor="hand2",
            command=self.open_reset_password_modal,
        )
        btn_reset.pack(side="left", padx=(0, 8))

        # 3. Salin Akun & Sandi
        btn_copy = tk.Button(
            toolbar,
            text="📋 Salin Akun & Sandi",
            font=FONT_BODY,
            bg=COLOR_CARD,
            fg=COLOR_TEXT_MAIN,
            relief="flat",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            padx=10,
            pady=5,
            cursor="hand2",
            command=self.handle_copy_admin_credentials,
        )
        btn_copy.pack(side="left", padx=(0, 8))

        # 4. Tambah Admin
        btn_add = tk.Button(
            toolbar,
            text="➕ Tambah Admin Baru",
            font=FONT_BODY,
            bg=COLOR_CARD,
            fg=COLOR_TEXT_MAIN,
            relief="flat",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            padx=10,
            pady=5,
            cursor="hand2",
            command=self.open_add_admin_modal,
        )
        btn_add.pack(side="left", padx=(0, 8))

        # 5. Tendang / Hapus Admin
        btn_delete = tk.Button(
            toolbar,
            text="🗑️ Hapus Akun (Tendang)",
            font=FONT_BODY,
            bg=COLOR_CARD,
            fg=COLOR_DANGER,
            relief="flat",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            padx=10,
            pady=5,
            cursor="hand2",
            command=self.handle_delete_admin,
        )
        btn_delete.pack(side="left", padx=(0, 8))

        # Toggle Tampilkan/Sembunyikan Sandi Admin
        self.btn_toggle_pass_admins = tk.Button(
            toolbar,
            text="👁️ Sembunyikan Sandi",
            font=FONT_SMALL,
            bg=COLOR_CARD,
            fg=COLOR_NAVY,
            relief="flat",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            padx=8,
            pady=5,
            cursor="hand2",
            command=self.toggle_passwords_admins,
        )
        self.btn_toggle_pass_admins.pack(side="left", padx=(0, 8))

        # Refresh
        btn_refresh = tk.Button(
            toolbar,
            text="🔄 Refresh",
            font=FONT_SMALL,
            bg=COLOR_CARD,
            fg=COLOR_TEXT_MAIN,
            relief="flat",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            padx=12,
            pady=5,
            cursor="hand2",
            command=self.fetch_admins,
        )
        btn_refresh.pack(side="right")

        # TABEL ADMINS
        table_frame = tk.Frame(self.tab_admins, bg=COLOR_CARD, highlightbackground=COLOR_BORDER, highlightthickness=1)
        table_frame.pack(fill="both", expand=True)

        columns = ("id", "name", "email", "sandi", "status", "created_at", "last_login", "last_ip")
        self.admins_tree = ttk.Treeview(table_frame, columns=columns, show="headings", selectmode="browse")

        self.admins_tree.heading("id", text="ID", anchor="center")
        self.admins_tree.heading("name", text="Nama Admin / HRD", anchor="w")
        self.admins_tree.heading("email", text="Email Login", anchor="w")
        self.admins_tree.heading("sandi", text="Sandi Akun (Tersimpan)", anchor="w")
        self.admins_tree.heading("status", text="Status Akun", anchor="center")
        self.admins_tree.heading("created_at", text="Tanggal Dibuat", anchor="w")
        self.admins_tree.heading("last_login", text="Terakhir Login", anchor="w")
        self.admins_tree.heading("last_ip", text="IP Terakhir", anchor="w")

        self.admins_tree.column("id", width=45, anchor="center")
        self.admins_tree.column("name", width=180, anchor="w")
        self.admins_tree.column("email", width=220, anchor="w")
        self.admins_tree.column("sandi", width=150, anchor="w")
        self.admins_tree.column("status", width=100, anchor="center")
        self.admins_tree.column("created_at", width=140, anchor="w")
        self.admins_tree.column("last_login", width=150, anchor="w")
        self.admins_tree.column("last_ip", width=110, anchor="w")

        vsb_adm = ttk.Scrollbar(table_frame, orient="vertical", command=self.admins_tree.yview)
        self.admins_tree.configure(yscrollcommand=vsb_adm.set)
        self.admins_tree.pack(side="left", fill="both", expand=True)
        vsb_adm.pack(side="right", fill="y")

        self.admins_tree.tag_configure("admin_active", background="#FFFFFF", foreground=COLOR_TEXT_MAIN)
        self.admins_tree.tag_configure("admin_blocked", background="#FEF2F2", foreground="#991B1B")

    def toggle_passwords_admins(self):
        self.show_passwords_admins = not self.show_passwords_admins
        if self.show_passwords_admins:
            self.btn_toggle_pass_admins.config(text="👁️ Sembunyikan Sandi")
        else:
            self.btn_toggle_pass_admins.config(text="👁️ Tampilkan Sandi")
        self.render_admins_table()

    def handle_copy_admin_credentials(self):
        item = self.admins_tree.selection()
        if not item:
            messagebox.showwarning("Pilih Akun", "Silakan klik akun admin pada tabel terlebih dahulu.", parent=self)
            return
        vals = self.admins_tree.item(item[0], "values")
        adm_id = vals[0]
        match = next((a for a in self.raw_admins_data if str(a.get("id")) == str(adm_id)), None)
        if not match:
            return

        name = match.get("name", "")
        email = match.get("email", "")
        pwd = match.get("saved_password", "")

        copy_text = f"Kredensial Admin DNA Advertising:\nNama: {name}\nEmail: {email}\nPassword: {pwd}\nLink Login: https://dna-vendor.vercel.app/admin/login"
        self.clipboard_clear()
        self.clipboard_append(copy_text)
        messagebox.showinfo("Kredensial Tersalin", f"Kredensial untuk '{name}' berhasil disalin ke clipboard!\nBisa langsung di-paste ke WhatsApp HRD.", parent=self)

    def handle_toggle_block_admin(self):
        item = self.admins_tree.selection()
        if not item:
            messagebox.showwarning("Pilih Akun", "Pilih akun admin yang ingin diblokir atau diaktifkan.", parent=self)
            return
        vals = self.admins_tree.item(item[0], "values")
        adm_id = vals[0]
        adm_name = vals[1]
        adm_status = vals[4]

        is_currently_blocked = "DIBLOKIR" in adm_status
        action_word = "mengaktifkan kembali" if is_currently_blocked else "MEMBLOKIR"
        msg = f"Apakah Anda yakin ingin {action_word} akun '{adm_name}' (ID #{adm_id})?\n\n"
        if not is_currently_blocked:
            msg += "⚠️ PERINGATAN: Akun yang diblokir TIDAK AKAN BISA LOGIN ke admin dashboard sampai Anda membuka blokirnya."
        else:
            msg += "Akun akan dapat login kembali secara normal."

        if not messagebox.askyesno("Konfirmasi Blokir Akun", msg, parent=self):
            return

        def worker():
            headers = {
                "Accept": "application/json",
                "Authorization": f"Bearer {self.auth_token}",
                "X-Dev-Secret-Key": self.secret_key,
            }
            try:
                resp = self.api_request("POST", f"/admins/{adm_id}/toggle-block", headers=headers, timeout=12)
                if resp.status_code == 200:
                    data = resp.json()
                    self.after(0, lambda: [
                        messagebox.showinfo("Berhasil", data.get("message", "Status akun diperbarui!"), parent=self),
                        self.fetch_admins(),
                        self.fetch_logs(),
                    ])
                else:
                    self.after(0, lambda: messagebox.showerror("Gagal", f"Gagal mengubah status blokir (HTTP {resp.status_code})", parent=self))
            except Exception as e:
                self.after(0, lambda: messagebox.showerror("Error", f"Koneksi gagal: {str(e)}", parent=self))

        threading.Thread(target=worker, daemon=True).start()

    # -------------------------------------------------------------------------
    # MODAL RESET PASSWORD
    # -------------------------------------------------------------------------
    def open_reset_password_modal(self):
        item = self.admins_tree.selection()
        if not item:
            messagebox.showwarning("Pilih Akun", "Silakan klik akun admin yang ingin direset passwordnya pada tabel.", parent=self)
            return

        vals = self.admins_tree.item(item[0], "values")
        adm_id = vals[0]
        adm_name = vals[1]
        adm_email = vals[2]

        modal = tk.Toplevel(self)
        modal.title(f"Reset Password - {adm_name}")
        modal.geometry("480x380")
        modal.configure(bg=COLOR_BG)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_CARD, padx=26, pady=22, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=16, pady=16)

        tk.Label(card, text="🔑 Ganti / Reset Password Admin", font=FONT_TITLE, fg=COLOR_NAVY, bg=COLOR_CARD).pack(anchor="w", pady=(0, 4))
        tk.Label(card, text=f"Akun: {adm_name} ({adm_email})", font=FONT_BODY_BOLD, fg=COLOR_TEXT_MAIN, bg=COLOR_CARD).pack(anchor="w", pady=(0, 16))

        tk.Label(card, text="PASSWORD BARU:", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w", pady=(0, 4))

        pwd_row = tk.Frame(card, bg=COLOR_CARD, highlightbackground=COLOR_INPUT_BORDER, highlightthickness=1)
        pwd_row.pack(fill="x", pady=(0, 12))

        new_pwd_entry = tk.Entry(pwd_row, font=FONT_BODY, bg=COLOR_INPUT_BG, fg=COLOR_TEXT_MAIN, relief="flat", show="")
        new_pwd_entry.pack(side="left", fill="x", expand=True, ipady=6, padx=(8, 0))

        def generate_random():
            alphabet = string.ascii_letters + string.digits + "!@#$%"
            auto_pass = "".join(secrets.choice(alphabet) for _ in range(10))
            new_pwd_entry.delete(0, tk.END)
            new_pwd_entry.insert(0, auto_pass)

        btn_gen = tk.Button(pwd_row, text="🎲 Acak", font=FONT_SMALL, bg=COLOR_CARD, fg=COLOR_NAVY, relief="flat", padx=8, cursor="hand2", command=generate_random)
        btn_gen.pack(side="right", padx=(0, 4))

        status_lbl = tk.Label(card, text="", font=FONT_SMALL, fg=COLOR_DANGER, bg=COLOR_CARD, wraplength=400)
        status_lbl.pack(anchor="w", pady=(0, 12))

        btn_row = tk.Frame(card, bg=COLOR_CARD)
        btn_row.pack(fill="x")

        def submit():
            val = new_pwd_entry.get().strip()
            if len(val) < 6:
                status_lbl.config(text="Password baru minimal 6 karakter!", fg=COLOR_DANGER)
                return

            status_lbl.config(text="Menyimpan password baru...", fg=COLOR_TEXT_MUTED)

            def worker():
                headers = {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": f"Bearer {self.auth_token}",
                    "X-Dev-Secret-Key": self.secret_key,
                }
                try:
                    resp = self.api_request("POST", f"/admins/{adm_id}/reset-password", json={"new_password": val}, headers=headers, timeout=12)
                    if resp.status_code == 200:
                        self.after(0, lambda: [
                            modal.destroy(),
                            messagebox.showinfo("Sukses", f"Password untuk '{adm_name}' berhasil diganti!\n\nPassword Baru: {val}", parent=self),
                            self.fetch_admins(),
                            self.fetch_logs(),
                        ])
                    else:
                        self.after(0, lambda: status_lbl.config(text=f"Gagal (HTTP {resp.status_code})", fg=COLOR_DANGER))
                except Exception as e:
                    self.after(0, lambda: status_lbl.config(text=f"Error: {str(e)}", fg=COLOR_DANGER))

            threading.Thread(target=worker, daemon=True).start()

        tk.Button(btn_row, text="Simpan Password Baru", font=FONT_BODY_BOLD, bg=COLOR_NAVY, fg="#FFFFFF", relief="flat", padx=16, pady=7, cursor="hand2", command=submit).pack(side="left")
        tk.Button(btn_row, text="Batal", font=FONT_BODY, bg=COLOR_BG, fg=COLOR_TEXT_MAIN, relief="flat", padx=14, pady=7, cursor="hand2", command=modal.destroy).pack(side="right")

    # -------------------------------------------------------------------------
    # MODAL TAMBAH ADMIN
    # -------------------------------------------------------------------------
    def open_add_admin_modal(self):
        modal = tk.Toplevel(self)
        modal.title("Tambah Akun Admin Baru")
        modal.geometry("480x420")
        modal.configure(bg=COLOR_BG)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_CARD, padx=26, pady=22, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=16, pady=16)

        tk.Label(card, text="➕ Tambah Admin / HRD Baru", font=FONT_TITLE, fg=COLOR_NAVY, bg=COLOR_CARD).pack(anchor="w", pady=(0, 16))

        tk.Label(card, text="NAMA LENGKAP:", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w", pady=(0, 4))
        name_e = tk.Entry(card, font=FONT_BODY, bg=COLOR_INPUT_BG, fg=COLOR_TEXT_MAIN, relief="flat", highlightthickness=1, highlightbackground=COLOR_INPUT_BORDER, highlightcolor=COLOR_NAVY)
        name_e.pack(fill="x", ipady=6, pady=(0, 12))

        tk.Label(card, text="EMAIL LOGIN:", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w", pady=(0, 4))
        email_e = tk.Entry(card, font=FONT_BODY, bg=COLOR_INPUT_BG, fg=COLOR_TEXT_MAIN, relief="flat", highlightthickness=1, highlightbackground=COLOR_INPUT_BORDER, highlightcolor=COLOR_NAVY)
        email_e.pack(fill="x", ipady=6, pady=(0, 12))

        tk.Label(card, text="PASSWORD AWAL (Minimal 6 Karakter):", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w", pady=(0, 4))
        pwd_e = tk.Entry(card, font=FONT_BODY, bg=COLOR_INPUT_BG, fg=COLOR_TEXT_MAIN, relief="flat", highlightthickness=1, highlightbackground=COLOR_INPUT_BORDER, highlightcolor=COLOR_NAVY)
        pwd_e.pack(fill="x", ipady=6, pady=(0, 14))

        status_lbl = tk.Label(card, text="", font=FONT_SMALL, fg=COLOR_DANGER, bg=COLOR_CARD, wraplength=400)
        status_lbl.pack(anchor="w", pady=(0, 10))

        btn_row = tk.Frame(card, bg=COLOR_CARD)
        btn_row.pack(fill="x")

        def submit():
            n = name_e.get().strip()
            em = email_e.get().strip()
            p = pwd_e.get().strip()
            if not n or not em or not p:
                status_lbl.config(text="Semua kolom wajib diisi!", fg=COLOR_DANGER)
                return

            def worker():
                headers = {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": f"Bearer {self.auth_token}",
                    "X-Dev-Secret-Key": self.secret_key,
                }
                try:
                    resp = self.api_request("POST", "/admins", json={"name": n, "email": em, "password": p}, headers=headers, timeout=12)
                    if resp.status_code in [200, 201]:
                        self.after(0, lambda: [
                            modal.destroy(),
                            messagebox.showinfo("Sukses", f"Admin '{n}' ({em}) berhasil ditambahkan!\nPassword: {p}", parent=self),
                            self.fetch_admins(),
                            self.fetch_logs(),
                        ])
                    else:
                        msg = resp.json().get("message", "Gagal menambah admin.")
                        self.after(0, lambda: status_lbl.config(text=msg, fg=COLOR_DANGER))
                except Exception as e:
                    self.after(0, lambda: status_lbl.config(text=f"Error: {str(e)}", fg=COLOR_DANGER))

            threading.Thread(target=worker, daemon=True).start()

        tk.Button(btn_row, text="Simpan Admin", font=FONT_BODY_BOLD, bg=COLOR_NAVY, fg="#FFFFFF", relief="flat", padx=16, pady=7, cursor="hand2", command=submit).pack(side="left")
        tk.Button(btn_row, text="Batal", font=FONT_BODY, bg=COLOR_BG, fg=COLOR_TEXT_MAIN, relief="flat", padx=14, pady=7, cursor="hand2", command=modal.destroy).pack(side="right")

    def handle_delete_admin(self):
        item = self.admins_tree.selection()
        if not item:
            messagebox.showwarning("Pilih Akun", "Pilih akun admin yang ingin dihapus / ditendang.", parent=self)
            return

        vals = self.admins_tree.item(item[0], "values")
        adm_id = vals[0]
        adm_name = vals[1]
        adm_email = vals[2]

        if not messagebox.askyesno(
            "Konfirmasi Hapus Akun Admin",
            f"PERINGATAN: Apakah Anda yakin ingin MENGHAPUS / MENENDANG akun admin ini?\n\n"
            f"ID: #{adm_id}\n"
            f"Nama: {adm_name}\n"
            f"Email: {adm_email}\n\n"
            f"Akun ini akan dihapus secara permanen dari database dan tidak dapat login lagi.",
            parent=self,
        ):
            return

        def worker():
            headers = {
                "Accept": "application/json",
                "Authorization": f"Bearer {self.auth_token}",
                "X-Dev-Secret-Key": self.secret_key,
            }
            try:
                resp = self.api_request("DELETE", f"/admins/{adm_id}", headers=headers, timeout=12)
                if resp.status_code == 200:
                    self.after(0, lambda: [
                        messagebox.showinfo("Berhasil", f"Akun '{adm_name}' berhasil dihapus dari sistem.", parent=self),
                        self.fetch_admins(),
                        self.fetch_logs(),
                    ])
                else:
                    self.after(0, lambda: messagebox.showerror("Gagal", f"Gagal menghapus admin (HTTP {resp.status_code})", parent=self))
            except Exception as e:
                self.after(0, lambda: messagebox.showerror("Error", f"Koneksi gagal: {str(e)}", parent=self))

        threading.Thread(target=worker, daemon=True).start()

    def handle_clear_logs(self):
        if not messagebox.askyesno("Bersihkan Riwayat", "Apakah Anda yakin ingin menghapus SELURUH riwayat login admin dari database?", parent=self):
            return

        def worker():
            headers = {
                "Accept": "application/json",
                "Authorization": f"Bearer {self.auth_token}",
                "X-Dev-Secret-Key": self.secret_key,
            }
            try:
                resp = self.api_request("DELETE", "/logs", headers=headers, timeout=12)
                if resp.status_code == 200:
                    self.after(0, lambda: [
                        messagebox.showinfo("Berhasil", "Seluruh riwayat login berhasil dibersihkan.", parent=self),
                        self.fetch_logs(),
                        self.fetch_stats(),
                    ])
            except Exception as e:
                self.after(0, lambda: messagebox.showerror("Error", str(e), parent=self))

        threading.Thread(target=worker, daemon=True).start()

    # -------------------------------------------------------------------------
    # PENGAMBILAN DATA (FETCHING)
    # -------------------------------------------------------------------------
    def refresh_all_data(self):
        self.fetch_stats()
        self.fetch_logs()
        self.fetch_admins()

    def fetch_stats(self):
        def worker():
            headers = {"Authorization": f"Bearer {self.auth_token}", "X-Dev-Secret-Key": self.secret_key}
            try:
                resp = self.api_request("GET", "/stats", headers=headers, timeout=10)
                if resp.status_code == 200:
                    data = resp.json().get("data", {})
                    self.after(0, lambda: [
                        self.stat_admins_lbl.config(text=str(data.get("total_admins", 0))),
                        self.stat_logs_lbl.config(text=str(data.get("total_logs", 0))),
                        self.stat_success_lbl.config(text=str(data.get("successful_logs", 0))),
                        self.stat_failed_lbl.config(text=str(data.get("failed_logs", 0))),
                        self.stat_today_lbl.config(text=str(data.get("today_logs", 0))),
                    ])
            except Exception:
                pass

        threading.Thread(target=worker, daemon=True).start()

    def fetch_logs(self):
        def worker():
            headers = {"Authorization": f"Bearer {self.auth_token}", "X-Dev-Secret-Key": self.secret_key}
            params = {}
            st = self.log_status_filter_var.get()
            if st in ["success", "failed"]:
                params["status"] = st
            q = self.log_search_var.get().strip()
            if q:
                params["search"] = q

            try:
                resp = self.api_request("GET", "/logs", params=params, headers=headers, timeout=12)
                if resp.status_code == 200:
                    self.raw_logs_data = resp.json().get("data", [])
                    self.after(0, self.render_logs_table)
            except Exception:
                pass

        threading.Thread(target=worker, daemon=True).start()

    def render_logs_table(self):
        for item in self.logs_tree.get_children():
            self.logs_tree.delete(item)

        for l in self.raw_logs_data:
            lid = l.get("id")
            waktu = l.get("created_at", "-")
            email = l.get("email", "-")
            name = l.get("admin_name", "-")
            raw_pwd = l.get("password_used", "-")

            if self.show_passwords_logs:
                sandi_display = raw_pwd if raw_pwd else "-"
            else:
                sandi_display = "••••••••" if (raw_pwd and raw_pwd != "-") else "-"

            st = l.get("status", "")
            st_text = "🟢 BERHASIL" if st == "success" else "🔴 GAGAL"
            ip = l.get("ip_address", "-")
            pesan = l.get("message", "-")

            tag = "status_success" if st == "success" else "status_failed"

            self.logs_tree.insert(
                "",
                "end",
                values=(lid, waktu, email, name, sandi_display, st_text, ip, pesan),
                tags=(tag,),
            )

    def fetch_admins(self):
        def worker():
            headers = {"Authorization": f"Bearer {self.auth_token}", "X-Dev-Secret-Key": self.secret_key}
            try:
                resp = self.api_request("GET", "/admins", headers=headers, timeout=12)
                if resp.status_code == 200:
                    self.raw_admins_data = resp.json().get("data", [])
                    self.after(0, self.render_admins_table)
            except Exception:
                pass

        threading.Thread(target=worker, daemon=True).start()

    def render_admins_table(self):
        for item in self.admins_tree.get_children():
            self.admins_tree.delete(item)

        for a in self.raw_admins_data:
            aid = a.get("id")
            name = a.get("name", "-")
            email = a.get("email", "-")
            raw_pwd = a.get("saved_password", "-")

            if self.show_passwords_admins:
                sandi_display = raw_pwd if raw_pwd else "-"
            else:
                sandi_display = "••••••••" if (raw_pwd and raw_pwd != "-") else "-"

            is_blocked = a.get("is_blocked", False)
            st_text = "🔴 DIBLOKIR" if is_blocked else "🟢 Aktif"
            created = a.get("created_at", "-")
            last_login = a.get("last_login_at", "-")
            last_ip = a.get("last_login_ip", "-")

            tag = "admin_blocked" if is_blocked else "admin_active"

            self.admins_tree.insert(
                "",
                "end",
                values=(aid, name, email, sandi_display, st_text, created, last_login, last_ip),
                tags=(tag,),
            )

    def toggle_auto_refresh(self):
        self.auto_refresh_enabled = self.auto_refresh_var.get()
        if self.auto_refresh_enabled:
            self.schedule_next_auto_refresh()
        else:
            if self.auto_refresh_timer:
                self.after_cancel(self.auto_refresh_timer)
                self.auto_refresh_timer = None

    def schedule_next_auto_refresh(self):
        if self.auto_refresh_enabled:
            self.auto_refresh_timer = self.after(10000, self.auto_refresh_tick)

    def auto_refresh_tick(self):
        if self.auto_refresh_enabled:
            self.fetch_logs()
            self.fetch_stats()
            self.schedule_next_auto_refresh()

    def handle_logout(self):
        if messagebox.askyesno("Konfirmasi Keluar", "Apakah Anda yakin ingin keluar dari Developer Portal?", parent=self):
            if self.auto_refresh_timer:
                self.after_cancel(self.auto_refresh_timer)
            self.auth_token = None
            self.main_frame.destroy()
            self.show_login_frame()


# =============================================================================
# ENTRY POINT
# =============================================================================
if __name__ == "__main__":
    app = DeveloperPortalApp()
    app.mainloop()

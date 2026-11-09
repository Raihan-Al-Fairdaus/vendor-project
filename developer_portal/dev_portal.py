"""
=============================================================================
DNA ADVERTISING - DEVELOPER PORTAL (DESKTOP APPLICATION)
=============================================================================
Aplikasi Desktop Khusus Developer / Super Administrator
- Tampilan Modern & Bersih (Menyesuaikan Tema Website DNA: Navy Blue & Putih)
- Login Simpel: Cukup Email & Password Developer
- Dilengkapi Fitur Intip/Lihat Password (👁️ Show/Hide)
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
# KONFIGURASI TEMA WARNA WEBSITE DNA ADVERTISING (LIGHT & BRAND NAVY)
# =============================================================================
COLOR_BG            = "#EDF2F7"  # Latar belakang utama (Soft Light Gray)
COLOR_CARD          = "#FFFFFF"  # Latar card & panel (Putih Bersih)
COLOR_NAVY          = "#1B3A60"  # Brand Utama DNA Advertising (Deep Navy)
COLOR_NAVY_HOVER    = "#234B7C"  # Hover Navy
COLOR_NAVY_LIGHT    = "#EBF2FA"  # Navy Muda / Tint
COLOR_BLUE          = "#2563EB"  # Aksen Biru
COLOR_TEXT_MAIN     = "#0F172A"  # Teks Utama (Slate 900)
COLOR_TEXT_MUTED    = "#64748B"  # Teks Sekunder (Slate 500)
COLOR_BORDER        = "#E2E8F0"  # Garis pembatas (Slate 200)
COLOR_INPUT_BG      = "#FFFFFF"  # Field input
COLOR_INPUT_BORDER  = "#CBD5E1"  # Border input (Slate 300)
COLOR_SUCCESS       = "#10B981"  # Hijau (Status Berhasil)
COLOR_DANGER        = "#EF4444"  # Merah (Status Gagal / Hapus)
COLOR_DANGER_HOVER  = "#DC2626"
COLOR_WARNING       = "#F59E0B"  # Amber / Kuning Peringatan

FONT_TITLE = ("Segoe UI", 16, "bold")
FONT_HEADING = ("Segoe UI", 12, "bold")
FONT_BODY = ("Segoe UI", 10)
FONT_BODY_BOLD = ("Segoe UI", 10, "bold")
FONT_SMALL = ("Segoe UI", 9)
FONT_MONO = ("Consolas", 9)

# Kredensial & Konfigurasi Default
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
        self.title("DNA Advertising - Developer Portal")
        self.geometry("1140x740")
        self.minsize(980, 640)
        self.configure(bg=COLOR_BG)

        # State Aplikasi
        self.local_config = load_local_config()
        self.server_url = self.local_config.get("server_url", DEFAULT_SERVER_URL)
        self.secret_key = DEFAULT_SECRET_KEY
        self.auth_token = None
        self.dev_email = None
        self.auto_refresh_timer = None
        self.auto_refresh_enabled = False

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

        # Tabs Styling
        self.style.configure(
            "TNotebook",
            background=COLOR_BG,
            borderwidth=0,
        )
        self.style.configure(
            "TNotebook.Tab",
            background=COLOR_CARD,
            foreground=COLOR_TEXT_MUTED,
            font=("Segoe UI", 10, "bold"),
            padding=[20, 10],
            borderwidth=0,
        )
        self.style.map(
            "TNotebook.Tab",
            background=[("selected", COLOR_NAVY), ("active", COLOR_NAVY_LIGHT)],
            foreground=[("selected", "#FFFFFF"), ("active", COLOR_NAVY)],
        )

        # Treeview (Tabel Data)
        self.style.configure(
            "Treeview",
            background=COLOR_CARD,
            foreground=COLOR_TEXT_MAIN,
            fieldbackground=COLOR_CARD,
            font=("Segoe UI", 9),
            rowheight=32,
            borderwidth=0,
        )
        self.style.configure(
            "Treeview.Heading",
            background=COLOR_NAVY,
            foreground="#FFFFFF",
            font=("Segoe UI", 9, "bold"),
            relief="flat",
            padding=[8, 7],
        )
        self.style.map("Treeview.Heading", background=[("active", COLOR_NAVY_HOVER)])
        self.style.map(
            "Treeview",
            background=[("selected", COLOR_NAVY_LIGHT)],
            foreground=[("selected", COLOR_NAVY)],
        )

        self.style.configure(
            "Vertical.TScrollbar",
            background=COLOR_BG,
            troughcolor=COLOR_CARD,
            borderwidth=0,
            arrowsize=12,
        )

    # -------------------------------------------------------------------------
    # HELPER REQUEST DENGAN DUAL ROUTE FALLBACK
    # -------------------------------------------------------------------------
    def api_request(self, method: str, subpath: str, **kwargs):
        base = self.server_url.rstrip("/")
        timeout = kwargs.pop("timeout", 15)

        # Coba /api/developer/...
        url1 = f"{base}/api/developer{subpath}"
        try:
            resp = requests.request(method, url1, timeout=timeout, **kwargs)
            if resp.status_code != 404:
                return resp
        except Exception:
            pass

        # Fallback ke /developer/...
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

        # Kartu Putih Bersih
        card = tk.Frame(
            center,
            bg=COLOR_CARD,
            padx=44,
            pady=38,
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
        )
        card.pack()

        # Banner Header Brand DNA
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
            text="Masuk untuk memonitor riwayat login admin & kelola password",
            font=FONT_SMALL,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_CARD,
        ).pack(anchor="center", pady=(4, 22))

        # 1. Input Email Developer
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

        # 2. Input Password Developer (Dengan Tombol Lihat Password 👁️)
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
            activebackground=COLOR_NAVY_LIGHT,
            activeforeground=COLOR_NAVY,
            relief="flat",
            bd=0,
            cursor="hand2",
            padx=10,
            command=lambda: self.toggle_input_visibility(self.pass_entry, self.btn_eye_pass),
        )
        self.btn_eye_pass.pack(side="right", padx=(0, 2))

        # Status Error / Loading Label
        self.login_status_lbl = tk.Label(
            card,
            text="",
            font=FONT_SMALL,
            fg=COLOR_DANGER,
            bg=COLOR_CARD,
            wraplength=360,
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

        # Ikon Pengaturan Server Tersembunyi (Discreet Gear di Kanan Bawah)
        gear_frame = tk.Frame(card, bg=COLOR_CARD)
        gear_frame.pack(fill="x", pady=(14, 0))

        btn_settings = tk.Button(
            gear_frame,
            text="⚙️ Pengaturan Server",
            font=("Segoe UI", 8),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_CARD,
            activebackground=COLOR_CARD,
            activeforeground=COLOR_NAVY,
            relief="flat",
            bd=0,
            cursor="hand2",
            command=self.open_server_settings_modal,
        )
        btn_settings.pack(side="right")

        # Shortcut Enter
        self.email_entry.bind("<Return>", lambda e: self.handle_login_clicked())
        self.pass_entry.bind("<Return>", lambda e: self.handle_login_clicked())

    def toggle_input_visibility(self, entry_widget, button_widget):
        if entry_widget.cget("show") == "":
            entry_widget.config(show="•")
            button_widget.config(text="👁️", fg=COLOR_TEXT_MUTED)
        else:
            entry_widget.config(show="")
            button_widget.config(text="🔒", fg=COLOR_NAVY)

    def open_server_settings_modal(self):
        """Modal tersembunyi jika developer suatu saat ingin ganti URL server backend"""
        modal = tk.Toplevel(self)
        modal.title("Pengaturan Alamat Server")
        modal.geometry("460x220")
        modal.configure(bg=COLOR_BG)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_CARD, padx=24, pady=20, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=16, pady=16)

        tk.Label(card, text="⚙️ Alamat Server Backend", font=FONT_HEADING, fg=COLOR_NAVY, bg=COLOR_CARD).pack(anchor="w")
        tk.Label(card, text="Ganti alamat URL jika website telah dipindah ke hosting/domain baru:", font=FONT_SMALL, fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w", pady=(2, 10))

        url_entry = tk.Entry(card, font=FONT_BODY, bg=COLOR_INPUT_BG, fg=COLOR_TEXT_MAIN, relief="flat", highlightthickness=1, highlightbackground=COLOR_INPUT_BORDER, highlightcolor=COLOR_NAVY)
        url_entry.insert(0, self.server_url)
        url_entry.pack(fill="x", ipady=6, pady=(0, 14))

        actions = tk.Frame(card, bg=COLOR_CARD)
        actions.pack(fill="x")

        def save_url():
            new_url = url_entry.get().strip().rstrip("/")
            if new_url:
                self.server_url = new_url
                save_local_config({"server_url": self.server_url, "remember_email": self.email_entry.get().strip()})
                modal.destroy()
                messagebox.showinfo("Tersimpan", f"Server berhasil diatur ke:\n{self.server_url}", parent=self)

        tk.Button(actions, text="Simpan Alamat", font=FONT_BODY_BOLD, bg=COLOR_NAVY, fg="#FFFFFF", relief="flat", padx=12, pady=5, cursor="hand2", command=save_url).pack(side="left")
        tk.Button(actions, text="Batal", font=FONT_BODY, bg=COLOR_BG, fg=COLOR_TEXT_MAIN, relief="flat", padx=10, pady=5, cursor="hand2", command=modal.destroy).pack(side="right")

    def set_login_loading(self, loading: bool, message: str = "", is_error: bool = False):
        if loading:
            self.btn_login.config(state="disabled", text="Memverifikasi...", bg=COLOR_TEXT_MUTED)
            self.login_status_lbl.config(text="Menghubungi sistem...", fg=COLOR_TEXT_MUTED)
        else:
            self.btn_login.config(state="normal", text="MASUK KE DEVELOPER PORTAL", bg=COLOR_NAVY)
            self.login_status_lbl.config(text=message, fg=COLOR_DANGER if is_error else COLOR_SUCCESS)

    def handle_login_clicked(self):
        email = self.email_entry.get().strip()
        password = self.pass_entry.get().strip()

        if not email or not password:
            self.set_login_loading(False, "Harap isi Email dan Password Developer!", is_error=True)
            return

        self.set_login_loading(True)

        def worker():
            headers = {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Dev-Secret-Key": self.secret_key,
            }
            payload = {
                "email": email,
                "password": password,
                "secret_key": self.secret_key,
            }

            try:
                resp = self.api_request("POST", "/login", json=payload, headers=headers, timeout=14)
                data = resp.json() if resp.headers.get("content-type", "").startswith("application/json") else {}

                if resp.status_code == 200 and data.get("status") == "success":
                    token = data.get("data", {}).get("token")
                    dev_email = data.get("data", {}).get("email")

                    save_local_config({
                        "server_url": self.server_url,
                        "remember_email": email,
                    })

                    self.after(0, lambda: self.on_login_success(token, dev_email))
                else:
                    err_msg = data.get("message")
                    if not err_msg:
                        if resp.status_code == 401:
                            err_msg = "Email atau Password Developer salah."
                        elif resp.status_code == 404:
                            err_msg = "Layanan Developer belum siap di server ini."
                        else:
                            err_msg = f"Gagal masuk (Kode: {resp.status_code})"
                    self.after(0, lambda: self.set_login_loading(False, err_msg, is_error=True))
            except requests.exceptions.ConnectionError:
                self.after(0, lambda: self.set_login_loading(
                    False, "Tidak dapat terhubung ke server. Periksa koneksi internet Anda.", is_error=True
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
    # TAMPILAN DASHBOARD UTAMA
    # -------------------------------------------------------------------------
    def show_dashboard_frame(self):
        if hasattr(self, "login_frame") and self.login_frame:
            self.login_frame.destroy()

        self.main_frame = tk.Frame(self, bg=COLOR_BG)
        self.main_frame.pack(fill="both", expand=True)

        # 1. HEADER BAR NAVY
        header = tk.Frame(self.main_frame, bg=COLOR_NAVY, padx=24, pady=14)
        header.pack(fill="x")

        # Judul & Badge Kiri
        title_box = tk.Frame(header, bg=COLOR_NAVY)
        title_box.pack(side="left")

        tk.Label(
            title_box,
            text="DNA ADVERTISING",
            font=("Segoe UI", 13, "bold"),
            fg="#FFFFFF",
            bg=COLOR_NAVY,
        ).pack(side="left")

        dev_badge = tk.Frame(title_box, bg="#2A4D77", padx=8, pady=2)
        dev_badge.pack(side="left", padx=12)
        tk.Label(
            dev_badge,
            text="DEVELOPER PORTAL",
            font=("Segoe UI", 8, "bold"),
            fg="#93C5FD",
            bg="#2A4D77",
        ).pack()

        # Identitas & Logout Kanan
        right_box = tk.Frame(header, bg=COLOR_NAVY)
        right_box.pack(side="right")

        tk.Label(
            right_box,
            text=f"Developer: {self.dev_email}",
            font=FONT_SMALL,
            fg="#CBD5E1",
            bg=COLOR_NAVY,
        ).pack(side="left", padx=14)

        btn_logout = tk.Button(
            right_box,
            text="Keluar",
            font=FONT_SMALL,
            bg="#2A4D77",
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

        # 2. STATS CARDS BAR (PUTIH BERSIH)
        self.stats_bar = tk.Frame(self.main_frame, bg=COLOR_BG, padx=24, pady=16)
        self.stats_bar.pack(fill="x")

        self.card_admins_val = self.create_stat_card(self.stats_bar, "TOTAL ADMIN", "0", COLOR_NAVY, 0)
        self.card_logs_val = self.create_stat_card(self.stats_bar, "TOTAL RIWAYAT LOGIN", "0", COLOR_TEXT_MAIN, 1)
        self.card_success_val = self.create_stat_card(self.stats_bar, "LOGIN BERHASIL", "0", COLOR_SUCCESS, 2)
        self.card_failed_val = self.create_stat_card(self.stats_bar, "PERCOBAAN GAGAL", "0", COLOR_DANGER, 3)

        # 3. TABS CONTAINER
        self.notebook = ttk.Notebook(self.main_frame)
        self.notebook.pack(fill="both", expand=True, padx=24, pady=(0, 18))

        # TAB 1: RIWAYAT LOGIN ADMIN
        self.tab_logs = tk.Frame(self.notebook, bg=COLOR_BG)
        self.notebook.add(self.tab_logs, text="  📋 Riwayat Login Admin  ")
        self.setup_logs_tab()

        # TAB 2: MANAJEMEN AKUN ADMIN
        self.tab_admins = tk.Frame(self.notebook, bg=COLOR_BG)
        self.notebook.add(self.tab_admins, text="  👥 Manajemen Akun Admin  ")
        self.setup_admins_tab()

        self.refresh_all_data()

    def create_stat_card(self, parent, title, initial_val, val_color, col):
        card = tk.Frame(
            parent,
            bg=COLOR_CARD,
            padx=18,
            pady=14,
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
        )
        card.grid(row=0, column=col, sticky="ew", padx=(0 if col == 0 else 12, 0))
        parent.grid_columnconfigure(col, weight=1)

        tk.Label(
            card,
            text=title,
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_CARD,
        ).pack(anchor="w")

        lbl_val = tk.Label(
            card,
            text=initial_val,
            font=("Segoe UI", 20, "bold"),
            fg=val_color,
            bg=COLOR_CARD,
        )
        lbl_val.pack(anchor="w", pady=(3, 0))
        return lbl_val

    # -------------------------------------------------------------------------
    # TAB 1: RIWAYAT LOGIN ADMIN
    # -------------------------------------------------------------------------
    def setup_logs_tab(self):
        toolbar = tk.Frame(self.tab_logs, bg=COLOR_BG, pady=10)
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
            width=24,
        )
        self.log_search_entry.pack(side="left", ipady=5, padx=(0, 10))
        self.log_search_entry.bind("<Return>", lambda e: self.fetch_logs())

        # Filter Status
        tk.Label(toolbar, text="Status:", font=FONT_BODY_BOLD, fg=COLOR_TEXT_MAIN, bg=COLOR_BG).pack(side="left", padx=(6, 6))

        self.log_status_filter_var = tk.StringVar(value="Semua")
        status_dropdown = ttk.Combobox(
            toolbar,
            textvariable=self.log_status_filter_var,
            values=["Semua", "success", "failed"],
            state="readonly",
            width=11,
            font=FONT_BODY,
        )
        status_dropdown.pack(side="left", padx=(0, 12))
        status_dropdown.bind("<<ComboboxSelected>>", lambda e: self.fetch_logs())

        btn_filter = tk.Button(
            toolbar,
            text="🔍 Cari",
            font=FONT_SMALL,
            bg=COLOR_CARD,
            fg=COLOR_TEXT_MAIN,
            relief="flat",
            padx=12,
            pady=5,
            cursor="hand2",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            command=self.fetch_logs,
        )
        btn_filter.pack(side="left", padx=(0, 8))

        btn_refresh = tk.Button(
            toolbar,
            text="🔄 Refresh",
            font=FONT_SMALL,
            bg=COLOR_NAVY,
            fg="#FFFFFF",
            relief="flat",
            padx=14,
            pady=5,
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
            bg=COLOR_BG,
            selectcolor=COLOR_CARD,
            activebackground=COLOR_BG,
            font=FONT_SMALL,
        )
        chk_auto.pack(side="left", padx=(10, 0))

        btn_clear = tk.Button(
            toolbar,
            text="🗑️ Bersihkan Riwayat",
            font=FONT_SMALL,
            bg=COLOR_CARD,
            fg=COLOR_DANGER,
            relief="flat",
            padx=12,
            pady=5,
            cursor="hand2",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            command=self.handle_clear_logs,
        )
        btn_clear.pack(side="right")

        table_frame = tk.Frame(self.tab_logs, bg=COLOR_CARD, highlightbackground=COLOR_BORDER, highlightthickness=1)
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
        self.logs_tree.column("admin_name", width=140, anchor="w")
        self.logs_tree.column("status", width=95, anchor="center")
        self.logs_tree.column("ip", width=125, anchor="w")
        self.logs_tree.column("pesan", width=310, anchor="w")

        self.logs_tree.tag_configure("success_tag", foreground=COLOR_SUCCESS)
        self.logs_tree.tag_configure("failed_tag", foreground=COLOR_DANGER)

        scrollbar = ttk.Scrollbar(table_frame, orient="vertical", command=self.logs_tree.yview, style="Vertical.TScrollbar")
        self.logs_tree.configure(yscrollcommand=scrollbar.set)

        self.logs_tree.pack(side="left", fill="both", expand=True)
        scrollbar.pack(side="right", fill="y")

        self.logs_tree.bind("<Double-1>", self.on_log_double_click)

        footer_lbl = tk.Label(
            self.tab_logs,
            text="💡 Tip: Klik ganda pada salah satu baris log untuk melihat rincian lengkap perangkat & browser admin.",
            font=FONT_SMALL,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG,
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
        dialog.configure(bg=COLOR_BG)
        dialog.transient(self)
        dialog.grab_set()

        card = tk.Frame(dialog, bg=COLOR_CARD, padx=26, pady=22, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=18, pady=18)

        tk.Label(card, text=f"Detail Riwayat Login #{raw.get('id')}", font=FONT_HEADING, fg=COLOR_NAVY, bg=COLOR_CARD).pack(anchor="w", pady=(0, 14))

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
            row = tk.Frame(card, bg=COLOR_CARD, pady=3)
            row.pack(fill="x")
            tk.Label(row, text=f"{label}:", font=FONT_BODY_BOLD, fg=COLOR_TEXT_MUTED, bg=COLOR_CARD, width=18, anchor="w").pack(side="left")
            val_color = COLOR_SUCCESS if val == "BERHASIL (SUCCESS)" else (COLOR_DANGER if val == "GAGAL (FAILED)" else COLOR_TEXT_MAIN)
            tk.Label(row, text=str(val or "-"), font=FONT_BODY, fg=val_color, bg=COLOR_CARD, wraplength=320, justify="left").pack(side="left", fill="x", expand=True)

        tk.Button(card, text="Tutup", font=FONT_BODY, bg=COLOR_NAVY, fg="#FFFFFF", relief="flat", padx=16, pady=5, cursor="hand2", command=dialog.destroy).pack(anchor="e", pady=(20, 0))

    # -------------------------------------------------------------------------
    # TAB 2: MANAJEMEN AKUN ADMIN
    # -------------------------------------------------------------------------
    def setup_admins_tab(self):
        toolbar = tk.Frame(self.tab_admins, bg=COLOR_BG, pady=10)
        toolbar.pack(fill="x")

        # Tombol Aksi Utama: Ganti Password Admin
        btn_reset = tk.Button(
            toolbar,
            text="🔑 Ganti / Reset Password Admin",
            font=FONT_BODY_BOLD,
            bg=COLOR_NAVY,
            fg="#FFFFFF",
            activebackground=COLOR_NAVY_HOVER,
            activeforeground="#FFFFFF",
            relief="flat",
            padx=14,
            pady=6,
            cursor="hand2",
            command=self.open_reset_password_modal,
        )
        btn_reset.pack(side="left", padx=(0, 10))

        btn_add = tk.Button(
            toolbar,
            text="➕ Tambah Admin Baru",
            font=FONT_BODY,
            bg=COLOR_CARD,
            fg=COLOR_TEXT_MAIN,
            relief="flat",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            padx=12,
            pady=6,
            cursor="hand2",
            command=self.open_add_admin_modal,
        )
        btn_add.pack(side="left", padx=(0, 10))

        btn_delete = tk.Button(
            toolbar,
            text="🗑️ Hapus Admin Terpilih",
            font=FONT_BODY,
            bg=COLOR_CARD,
            fg=COLOR_DANGER,
            relief="flat",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            padx=12,
            pady=6,
            cursor="hand2",
            command=self.handle_delete_admin,
        )
        btn_delete.pack(side="left", padx=(0, 10))

        btn_refresh = tk.Button(
            toolbar,
            text="🔄 Refresh Data",
            font=FONT_SMALL,
            bg=COLOR_CARD,
            fg=COLOR_TEXT_MAIN,
            relief="flat",
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
            padx=12,
            pady=6,
            cursor="hand2",
            command=self.fetch_admins,
        )
        btn_refresh.pack(side="right")

        table_frame = tk.Frame(self.tab_admins, bg=COLOR_CARD, highlightbackground=COLOR_BORDER, highlightthickness=1)
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

        info_box = tk.Frame(self.tab_admins, bg=COLOR_CARD, padx=18, pady=12, highlightbackground=COLOR_BORDER, highlightthickness=1)
        info_box.pack(fill="x", pady=(12, 0))

        tk.Label(info_box, text="💡 Fitur Reset Password Admin:", font=FONT_BODY_BOLD, fg=COLOR_NAVY, bg=COLOR_CARD).pack(anchor="w")
        tk.Label(
            info_box,
            text="Jika ada admin yang lupa kata sandi, klik nama admin di tabel lalu klik tombol [🔑 Ganti / Reset Password Admin]. Anda bisa memasukkan sandi baru atau menggunakan tombol acak sandi.",
            font=FONT_SMALL,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_CARD,
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
        modal.geometry("520x390")
        modal.configure(bg=COLOR_BG)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_CARD, padx=28, pady=24, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=18, pady=18)

        tk.Label(card, text="🔑 Reset / Ganti Password Admin", font=FONT_TITLE, fg=COLOR_NAVY, bg=COLOR_CARD).pack(anchor="w")
        tk.Label(card, text=f"Admin Target: {admin_name} ({admin_email})", font=FONT_BODY_BOLD, fg=COLOR_BLUE, bg=COLOR_CARD).pack(anchor="w", pady=(4, 16))

        tk.Label(card, text="PASSWORD BARU (Minimal 6 Karakter):", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w", pady=(0, 4))

        input_wrapper = tk.Frame(card, bg=COLOR_CARD)
        input_wrapper.pack(fill="x", pady=(0, 6))

        pass_row = tk.Frame(input_wrapper, bg=COLOR_CARD, highlightbackground=COLOR_INPUT_BORDER, highlightthickness=1)
        pass_row.pack(side="left", fill="x", expand=True)

        new_pass_entry = tk.Entry(
            pass_row,
            font=FONT_BODY,
            bg=COLOR_INPUT_BG,
            fg=COLOR_TEXT_MAIN,
            relief="flat",
        )
        new_pass_entry.pack(side="left", fill="x", expand=True, ipady=6, padx=(8, 0))

        btn_eye_modal = tk.Button(
            pass_row,
            text="👁️",
            font=("Segoe UI Emoji", 10),
            bg=COLOR_CARD,
            fg=COLOR_TEXT_MUTED,
            relief="flat",
            bd=0,
            cursor="hand2",
            padx=8,
            command=lambda: self.toggle_input_visibility(new_pass_entry, btn_eye_modal),
        )
        btn_eye_modal.pack(side="right", padx=(0, 2))

        def generate_random_pwd():
            chars = string.ascii_letters + string.digits + "@#$!"
            pwd = "".join(secrets.choice(chars) for _ in range(12))
            new_pass_entry.delete(0, tk.END)
            new_pass_entry.insert(0, pwd)
            new_pass_entry.config(show="")
            btn_eye_modal.config(text="🔒", fg=COLOR_NAVY)

        btn_random = tk.Button(
            input_wrapper,
            text="🎲 Acak Sandi",
            font=FONT_SMALL,
            bg=COLOR_BG,
            fg=COLOR_TEXT_MAIN,
            relief="flat",
            padx=10,
            pady=5,
            cursor="hand2",
            command=generate_random_pwd,
        )
        btn_random.pack(side="left", padx=(8, 0))

        status_lbl = tk.Label(card, text="", font=FONT_SMALL, fg=COLOR_DANGER, bg=COLOR_CARD, wraplength=420)
        status_lbl.pack(anchor="w", pady=(8, 12))

        actions = tk.Frame(card, bg=COLOR_CARD)
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
                        timeout=14,
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

        btn_save = tk.Button(actions, text="Simpan Password Baru", font=FONT_BODY_BOLD, bg=COLOR_NAVY, fg="#FFFFFF", relief="flat", padx=16, pady=8, cursor="hand2", command=submit_reset)
        btn_save.pack(side="left")

        btn_cancel = tk.Button(actions, text="Batal", font=FONT_BODY, bg=COLOR_BG, fg=COLOR_TEXT_MAIN, relief="flat", padx=14, pady=8, cursor="hand2", command=modal.destroy)
        btn_cancel.pack(side="right")

    # -------------------------------------------------------------------------
    # MODAL: TAMBAH ADMIN BARU (DENGAN TOMBOL LIHAT PASSWORD 👁️)
    # -------------------------------------------------------------------------
    def open_add_admin_modal(self):
        modal = tk.Toplevel(self)
        modal.title("Tambah Akun Admin Baru")
        modal.geometry("500x430")
        modal.configure(bg=COLOR_BG)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_CARD, padx=28, pady=24, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=18, pady=18)

        tk.Label(card, text="➕ Tambah Admin Baru", font=FONT_TITLE, fg=COLOR_NAVY, bg=COLOR_CARD).pack(anchor="w", pady=(0, 16))

        tk.Label(card, text="NAMA LENGKAP ADMIN:", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w", pady=(0, 4))
        name_entry = tk.Entry(card, font=FONT_BODY, bg=COLOR_INPUT_BG, fg=COLOR_TEXT_MAIN, relief="flat", highlightthickness=1, highlightbackground=COLOR_INPUT_BORDER, highlightcolor=COLOR_NAVY)
        name_entry.pack(fill="x", ipady=6, pady=(0, 12))

        tk.Label(card, text="EMAIL ADMIN:", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w", pady=(0, 4))
        email_entry = tk.Entry(card, font=FONT_BODY, bg=COLOR_INPUT_BG, fg=COLOR_TEXT_MAIN, relief="flat", highlightthickness=1, highlightbackground=COLOR_INPUT_BORDER, highlightcolor=COLOR_NAVY)
        email_entry.pack(fill="x", ipady=6, pady=(0, 12))

        tk.Label(card, text="PASSWORD AWAL (Minimal 6 Karakter):", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_CARD).pack(anchor="w", pady=(0, 4))

        pwd_row = tk.Frame(card, bg=COLOR_CARD, highlightbackground=COLOR_INPUT_BORDER, highlightthickness=1)
        pwd_row.pack(fill="x", pady=(0, 14))

        pwd_entry = tk.Entry(pwd_row, font=FONT_BODY, bg=COLOR_INPUT_BG, fg=COLOR_TEXT_MAIN, relief="flat", show="•")
        pwd_entry.pack(side="left", fill="x", expand=True, ipady=6, padx=(8, 0))

        btn_eye_add = tk.Button(
            pwd_row,
            text="👁️",
            font=("Segoe UI Emoji", 10),
            bg=COLOR_CARD,
            fg=COLOR_TEXT_MUTED,
            relief="flat",
            bd=0,
            cursor="hand2",
            padx=8,
            command=lambda: self.toggle_input_visibility(pwd_entry, btn_eye_add),
        )
        btn_eye_add.pack(side="right", padx=(0, 2))

        status_lbl = tk.Label(card, text="", font=FONT_SMALL, fg=COLOR_DANGER, bg=COLOR_CARD, wraplength=420)
        status_lbl.pack(anchor="w", pady=(0, 10))

        actions = tk.Frame(card, bg=COLOR_CARD)
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
                        timeout=14,
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

        btn_can = tk.Button(actions, text="Batal", font=FONT_BODY, bg=COLOR_BG, fg=COLOR_TEXT_MAIN, relief="flat", padx=14, pady=7, cursor="hand2", command=modal.destroy)
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
                resp = self.api_request("DELETE", f"/admins/{admin_id}", headers=headers, timeout=14)
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
                resp = self.api_request("DELETE", "/logs", headers=headers, timeout=14)
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
                resp = self.api_request("GET", "/stats", headers=headers, timeout=12)
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
                resp = self.api_request("GET", "/logs", params=params, headers=headers, timeout=14)
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
                resp = self.api_request("GET", "/admins", headers=headers, timeout=14)
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

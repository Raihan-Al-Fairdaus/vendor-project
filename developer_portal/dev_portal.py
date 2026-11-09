"""
=============================================================================
DNA ADVERTISING - DEVELOPER PORTAL (DESKTOP APPLICATION)
=============================================================================
Aplikasi Desktop Khusus Developer untuk Pengawasan & Manajemen Admin
- Login Eksklusif Developer (Email, Password, Secret Key)
- Live Monitoring Riwayat Login Admin (Sukses, Gagal, IP, Device)
- Manajemen Akun Admin:
  * Lihat Semua Akun Admin
  * Reset/Ganti Password Admin jika Lupa Password
  * Tambah Admin Baru
  * Hapus Akun Admin
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
COLOR_BG_DARK       = "#0F172A"  # Slate 900 (Background Utama)
COLOR_BG_CARD       = "#1E293B"  # Slate 800 (Card & Panel)
COLOR_BG_CARD_HOVER = "#334155"  # Slate 700 (Border & Hover)
COLOR_BG_INPUT      = "#0B1120"  # Dark Slate (Input Field)
COLOR_TEXT_PRIMARY  = "#F8FAFC"  # White (Teks Utama)
COLOR_TEXT_MUTED    = "#94A3B8"  # Slate 400 (Teks Sekunder)
COLOR_PRIMARY       = "#2563EB"  # Royal Blue (Tombol Utama)
COLOR_PRIMARY_HOVER = "#1D4ED8"  # Darker Blue
COLOR_SUCCESS       = "#10B981"  # Emerald Green (Status Sukses)
COLOR_DANGER        = "#EF4444"  # Red (Hapus / Status Gagal)
COLOR_DANGER_HOVER  = "#DC2626"
COLOR_WARNING       = "#F59E0B"  # Amber (Peringatan)
COLOR_BORDER        = "#334155"  # Border Slate

FONT_TITLE = ("Segoe UI", 16, "bold")
FONT_SUBTITLE = ("Segoe UI", 11)
FONT_HEADING = ("Segoe UI", 12, "bold")
FONT_BODY = ("Segoe UI", 10)
FONT_BODY_BOLD = ("Segoe UI", 10, "bold")
FONT_SMALL = ("Segoe UI", 9)
FONT_MONO = ("Consolas", 9)

# Lokasi File Konfigurasi Lokal untuk menyimpan URL Server & Remember Me
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
# KELAS UTAMA APLIKASI
# =============================================================================
class DeveloperPortalApp(tk.Tk):
    def __init__(self):
        super().__init__()
        self.title("DNA Advertising - Developer Portal v2.0")
        self.geometry("1100x720")
        self.minsize(980, 620)
        self.configure(bg=COLOR_BG_DARK)

        # State Aplikasi
        self.server_url = "https://dna-vendor.vercel.app"
        self.auth_token = None
        self.dev_email = None
        self.auto_refresh_timer = None
        self.auto_refresh_enabled = False

        # Load konfigurasi sebelumnya jika ada
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

        # Global Notebook
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
            padding=[16, 8],
            borderwidth=0,
        )
        self.style.map(
            "TNotebook.Tab",
            background=[("selected", COLOR_PRIMARY), ("active", COLOR_BG_CARD_HOVER)],
            foreground=[("selected", COLOR_TEXT_PRIMARY), ("active", COLOR_TEXT_PRIMARY)],
        )

        # Treeview (Tabel Data)
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
        self.style.map(
            "Treeview.Heading",
            background=[("active", COLOR_PRIMARY)],
        )
        self.style.map(
            "Treeview",
            background=[("selected", COLOR_PRIMARY)],
            foreground=[("selected", COLOR_TEXT_PRIMARY)],
        )

        # Scrollbars
        self.style.configure(
            "Vertical.TScrollbar",
            background=COLOR_BG_CARD,
            troughcolor=COLOR_BG_DARK,
            borderwidth=0,
            arrowsize=12,
        )

    # -------------------------------------------------------------------------
    # TAMPILAN LOGIN
    # -------------------------------------------------------------------------
    def show_login_frame(self):
        if hasattr(self, "main_frame") and self.main_frame:
            self.main_frame.destroy()

        self.login_frame = tk.Frame(self, bg=COLOR_BG_DARK)
        self.login_frame.pack(fill="both", expand=True)

        # Kartu Login Tengah
        center_container = tk.Frame(self.login_frame, bg=COLOR_BG_DARK)
        center_container.place(relx=0.5, rely=0.5, anchor="center")

        card = tk.Frame(
            center_container,
            bg=COLOR_BG_CARD,
            padx=40,
            pady=36,
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
        )
        card.pack()

        # Logo / Badge Developer
        badge_frame = tk.Frame(card, bg="#312E81", padx=14, pady=4)
        badge_frame.pack(anchor="center", pady=(0, 10))
        tk.Label(
            badge_frame,
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
            text="Aplikasi Khusus Developer untuk Monitoring & Reset Password Admin",
            font=FONT_SMALL,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="center", pady=(4, 24))

        # 1. Pilihan Server Target
        tk.Label(
            card,
            text="SERVER TARGET",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="w", pady=(0, 4))

        self.server_choice_var = tk.StringVar(
            value="production" if "vercel" in self.server_url else "localhost"
        )
        server_btn_frame = tk.Frame(card, bg=COLOR_BG_CARD)
        server_btn_frame.pack(fill="x", pady=(0, 6))

        def on_server_change():
            choice = self.server_choice_var.get()
            if choice == "production":
                self.server_entry.delete(0, tk.END)
                self.server_entry.insert(0, "https://dna-vendor.vercel.app")
            elif choice == "localhost":
                self.server_entry.delete(0, tk.END)
                self.server_entry.insert(0, "http://127.0.0.1:8000")

        rb1 = tk.Radiobutton(
            server_btn_frame,
            text="Production (Vercel)",
            variable=self.server_choice_var,
            value="production",
            command=on_server_change,
            fg=COLOR_TEXT_PRIMARY,
            bg=COLOR_BG_CARD,
            selectcolor=COLOR_BG_DARK,
            activebackground=COLOR_BG_CARD,
            activeforeground=COLOR_TEXT_PRIMARY,
            font=FONT_SMALL,
        )
        rb1.pack(side="left", padx=(0, 10))

        rb2 = tk.Radiobutton(
            server_btn_frame,
            text="Localhost (Laragon)",
            variable=self.server_choice_var,
            value="localhost",
            command=on_server_change,
            fg=COLOR_TEXT_PRIMARY,
            bg=COLOR_BG_CARD,
            selectcolor=COLOR_BG_DARK,
            activebackground=COLOR_BG_CARD,
            activeforeground=COLOR_TEXT_PRIMARY,
            font=FONT_SMALL,
        )
        rb2.pack(side="left")

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
            width=42,
        )
        self.server_entry.insert(0, self.server_url)
        self.server_entry.pack(fill="x", ipady=6, pady=(0, 14))

        # 2. Input Email Developer
        tk.Label(
            card,
            text="EMAIL DEVELOPER",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="w", pady=(0, 4))

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
            width=42,
        )
        saved_email = self.local_config.get("remember_email", "developer@dna-advertising.com")
        self.email_entry.insert(0, saved_email)
        self.email_entry.pack(fill="x", ipady=6, pady=(0, 14))

        # 3. Input Password Developer
        tk.Label(
            card,
            text="PASSWORD DEVELOPER",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="w", pady=(0, 4))

        self.pass_entry = tk.Entry(
            card,
            font=FONT_BODY,
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_PRIMARY,
            insertbackground=COLOR_TEXT_PRIMARY,
            relief="flat",
            show="•",
            highlightthickness=1,
            highlightbackground=COLOR_BORDER,
            highlightcolor=COLOR_PRIMARY,
            width=42,
        )
        self.pass_entry.insert(0, "DevDNA@2026!")
        self.pass_entry.pack(fill="x", ipady=6, pady=(0, 14))

        # 4. Input Secret Key Developer
        tk.Label(
            card,
            text="DEVELOPER SECRET KEY",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="w", pady=(0, 4))

        self.secret_entry = tk.Entry(
            card,
            font=FONT_BODY,
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_PRIMARY,
            insertbackground=COLOR_TEXT_PRIMARY,
            relief="flat",
            show="•",
            highlightthickness=1,
            highlightbackground=COLOR_BORDER,
            highlightcolor=COLOR_PRIMARY,
            width=42,
        )
        self.secret_entry.insert(0, "dna_dev_sec_2026_secret_key_dna_portal")
        self.secret_entry.pack(fill="x", ipady=6, pady=(0, 18))

        # Status / Feedback Label
        self.login_status_lbl = tk.Label(
            card,
            text="",
            font=FONT_SMALL,
            fg=COLOR_DANGER,
            bg=COLOR_BG_CARD,
            wraplength=340,
        )
        self.login_status_lbl.pack(pady=(0, 10))

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
        self.btn_login.pack(fill="x", ipady=8)

        # Bind Enter key to submit
        self.pass_entry.bind("<Return>", lambda e: self.handle_login_clicked())
        self.secret_entry.bind("<Return>", lambda e: self.handle_login_clicked())

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
            self.set_login_loading(False, "Harap isi Server, Email, dan Password!", is_error=True)
            return

        self.server_url = server
        self.set_login_loading(True)

        def worker():
            endpoint = f"{self.server_url}/api/developer/login"
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
                resp = requests.post(endpoint, json=payload, headers=headers, timeout=12)
                data = resp.json() if resp.headers.get("content-type", "").startswith("application/json") else {}

                if resp.status_code == 200 and data.get("status") == "success":
                    token = data.get("data", {}).get("token")
                    dev_email = data.get("data", {}).get("email")

                    # Simpan konfigurasi
                    save_local_config({
                        "server_url": self.server_url,
                        "remember_email": email,
                    })

                    self.after(0, lambda: self.on_login_success(token, dev_email))
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
    # TAMPILAN DASHBOARD UTAMA
    # -------------------------------------------------------------------------
    def show_dashboard_frame(self):
        if hasattr(self, "login_frame") and self.login_frame:
            self.login_frame.destroy()

        self.main_frame = tk.Frame(self, bg=COLOR_BG_DARK)
        self.main_frame.pack(fill="both", expand=True)

        # 1. HEADER BAR
        header = tk.Frame(self.main_frame, bg=COLOR_BG_CARD, padx=20, pady=12)
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

        # Server Status Tengah
        server_short = "Production (Vercel)" if "vercel" in self.server_url else "Localhost"
        server_label = tk.Label(
            header,
            text=f"🟢 {server_short} ({self.server_url})",
            font=FONT_SMALL,
            fg=COLOR_SUCCESS,
            bg=COLOR_BG_CARD,
        )
        server_label.pack(side="left", padx=20)

        # Profil Developer & Logout Kanan
        right_box = tk.Frame(header, bg=COLOR_BG_CARD)
        right_box.pack(side="right")

        tk.Label(
            right_box,
            text=f"Logged in as: {self.dev_email}",
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
            padx=10,
            pady=3,
            cursor="hand2",
            command=self.handle_logout,
        )
        btn_logout.pack(side="right")

        # 2. STATS CARDS BAR (RINGKASAN STATUS)
        self.stats_bar = tk.Frame(self.main_frame, bg=COLOR_BG_DARK, padx=20, pady=14)
        self.stats_bar.pack(fill="x")

        self.card_admins_val = self.create_stat_card(self.stats_bar, "TOTAL ADMIN", "0", COLOR_PRIMARY, 0)
        self.card_logs_val = self.create_stat_card(self.stats_bar, "TOTAL RIWAYAT LOGIN", "0", COLOR_TEXT_PRIMARY, 1)
        self.card_success_val = self.create_stat_card(self.stats_bar, "LOGIN BERHASIL", "0", COLOR_SUCCESS, 2)
        self.card_failed_val = self.create_stat_card(self.stats_bar, "PERCOBAAN GAGAL", "0", COLOR_DANGER, 3)

        # 3. TABS CONTAINER
        self.notebook = ttk.Notebook(self.main_frame)
        self.notebook.pack(fill="both", expand=True, padx=20, pady=(0, 16))

        # TAB 1: RIWAYAT LOGIN ADMIN
        self.tab_logs = tk.Frame(self.notebook, bg=COLOR_BG_DARK)
        self.notebook.add(self.tab_logs, text="  📋 Riwayat Login Admin  ")
        self.setup_logs_tab()

        # TAB 2: MANAJEMEN AKUN ADMIN
        self.tab_admins = tk.Frame(self.notebook, bg=COLOR_BG_DARK)
        self.notebook.add(self.tab_admins, text="  👥 Manajemen Akun Admin  ")
        self.setup_admins_tab()

        # Load initial data
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
        # Toolbar Atas
        toolbar = tk.Frame(self.tab_logs, bg=COLOR_BG_DARK, pady=10)
        toolbar.pack(fill="x")

        # Search Box
        tk.Label(
            toolbar,
            text="Cari:",
            font=FONT_BODY_BOLD,
            fg=COLOR_TEXT_PRIMARY,
            bg=COLOR_BG_DARK,
        ).pack(side="left", padx=(0, 6))

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
            width=24,
        )
        self.log_search_entry.pack(side="left", ipady=4, padx=(0, 10))
        self.log_search_entry.bind("<Return>", lambda e: self.fetch_logs())

        # Filter Status
        tk.Label(
            toolbar,
            text="Status:",
            font=FONT_BODY_BOLD,
            fg=COLOR_TEXT_PRIMARY,
            bg=COLOR_BG_DARK,
        ).pack(side="left", padx=(6, 6))

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

        # Tombol Filter/Cari
        btn_filter = tk.Button(
            toolbar,
            text="🔍 Filter",
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

        # Tombol Refresh
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

        # Checkbox Auto Refresh
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

        # Tombol Hapus Riwayat di Kanan
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

        # Container Tabel
        table_frame = tk.Frame(
            self.tab_logs,
            bg=COLOR_BG_CARD,
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
        )
        table_frame.pack(fill="both", expand=True)

        columns = ("id", "waktu", "email", "admin_name", "status", "ip", "pesan")
        self.logs_tree = ttk.Treeview(
            table_frame,
            columns=columns,
            show="headings",
            selectmode="browse",
        )

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

        # Tag styling untuk Success (Hijau) dan Failed (Merah)
        self.logs_tree.tag_configure("success_tag", foreground=COLOR_SUCCESS)
        self.logs_tree.tag_configure("failed_tag", foreground=COLOR_DANGER)

        # Scrollbar
        scrollbar = ttk.Scrollbar(
            table_frame, orient="vertical", command=self.logs_tree.yview, style="Vertical.TScrollbar"
        )
        self.logs_tree.configure(yscrollcommand=scrollbar.set)

        self.logs_tree.pack(side="left", fill="both", expand=True)
        scrollbar.pack(side="right", fill="y")

        # Double click untuk lihat detail
        self.logs_tree.bind("<Double-1>", self.on_log_double_click)

        # Petunjuk Bawah
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

        # Modal Detail Log
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
        # Toolbar Atas
        toolbar = tk.Frame(self.tab_admins, bg=COLOR_BG_DARK, pady=10)
        toolbar.pack(fill="x")

        # Tombol Aksi Utama: Ganti Password Admin
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

        # Tombol Tambah Admin Baru
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

        # Tombol Refresh Data Admin
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

        # Container Tabel Admin
        table_frame = tk.Frame(
            self.tab_admins,
            bg=COLOR_BG_CARD,
            highlightbackground=COLOR_BORDER,
            highlightthickness=1,
        )
        table_frame.pack(fill="both", expand=True)

        columns = ("id", "name", "email", "created_at", "last_login", "last_ip")
        self.admins_tree = ttk.Treeview(
            table_frame,
            columns=columns,
            show="headings",
            selectmode="browse",
        )

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

        # Scrollbar
        scrollbar = ttk.Scrollbar(
            table_frame, orient="vertical", command=self.admins_tree.yview, style="Vertical.TScrollbar"
        )
        self.admins_tree.configure(yscrollcommand=scrollbar.set)

        self.admins_tree.pack(side="left", fill="both", expand=True)
        scrollbar.pack(side="right", fill="y")

        # Info Box Bawah
        info_box = tk.Frame(self.tab_admins, bg=COLOR_BG_CARD, padx=16, pady=12, highlightbackground=COLOR_BORDER, highlightthickness=1)
        info_box.pack(fill="x", pady=(12, 0))

        tk.Label(
            info_box,
            text="💡 Fitur Reset Password Admin:",
            font=FONT_BODY_BOLD,
            fg="#60A5FA",
            bg=COLOR_BG_CARD,
        ).pack(anchor="w")

        tk.Label(
            info_box,
            text="Jika ada admin yang lupa kata sandinya, pilih admin tersebut di tabel lalu klik tombol [🔑 Ganti / Reset Password Admin]. Anda bisa memasukkan password baru atau mengacak password yang kuat.",
            font=FONT_SMALL,
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
            wraplength=850,
            justify="left",
        ).pack(anchor="w", pady=(2, 0))

    # -------------------------------------------------------------------------
    # MODAL: GANTI / RESET PASSWORD ADMIN
    # -------------------------------------------------------------------------
    def open_reset_password_modal(self):
        selected = self.admins_tree.selection()
        if not selected:
            messagebox.showwarning(
                "Pilih Admin",
                "Silakan klik salah satu akun admin di tabel terlebih dahulu!",
                parent=self,
            )
            return

        item = self.admins_tree.item(selected[0])
        values = item.get("values", [])
        admin_id = values[0]
        admin_name = values[1]
        admin_email = values[2]

        # Modal Window
        modal = tk.Toplevel(self)
        modal.title(f"Ganti Password Admin: {admin_name}")
        modal.geometry("500x380")
        modal.minsize(460, 360)
        modal.configure(bg=COLOR_BG_DARK)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_BG_CARD, padx=28, pady=24, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=20, pady=20)

        tk.Label(
            card,
            text="🔑 Reset / Ganti Password Admin",
            font=FONT_TITLE,
            fg=COLOR_TEXT_PRIMARY,
            bg=COLOR_BG_CARD,
        ).pack(anchor="w")

        tk.Label(
            card,
            text=f"Admin Target: {admin_name} ({admin_email})",
            font=FONT_BODY_BOLD,
            fg="#93C5FD",
            bg=COLOR_BG_CARD,
        ).pack(anchor="w", pady=(4, 16))

        # Input Password Baru
        tk.Label(
            card,
            text="PASSWORD BARU (Minimal 6 Karakter):",
            font=("Segoe UI", 8, "bold"),
            fg=COLOR_TEXT_MUTED,
            bg=COLOR_BG_CARD,
        ).pack(anchor="w", pady=(0, 4))

        pass_input_frame = tk.Frame(card, bg=COLOR_BG_CARD)
        pass_input_frame.pack(fill="x", pady=(0, 6))

        new_pass_entry = tk.Entry(
            pass_input_frame,
            font=FONT_BODY,
            bg=COLOR_BG_INPUT,
            fg=COLOR_TEXT_PRIMARY,
            insertbackground=COLOR_TEXT_PRIMARY,
            relief="flat",
            highlightthickness=1,
            highlightbackground=COLOR_BORDER,
            highlightcolor=COLOR_PRIMARY,
        )
        new_pass_entry.pack(side="left", fill="x", expand=True, ipady=6)

        def generate_random_pwd():
            chars = string.ascii_letters + string.digits + "@#$!"
            pwd = "".join(secrets.choice(chars) for _ in range(12))
            new_pass_entry.delete(0, tk.END)
            new_pass_entry.insert(0, pwd)

        btn_random = tk.Button(
            pass_input_frame,
            text="🎲 Acak Sandi Kuat",
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

        # Status Label di dalam Modal
        status_lbl = tk.Label(
            card,
            text="",
            font=FONT_SMALL,
            fg=COLOR_DANGER,
            bg=COLOR_BG_CARD,
            wraplength=420,
        )
        status_lbl.pack(anchor="w", pady=(8, 12))

        # Tombol Aksi Bawah
        actions_frame = tk.Frame(card, bg=COLOR_BG_CARD)
        actions_frame.pack(fill="x", pady=(10, 0))

        def submit_reset():
            new_pass = new_pass_entry.get().strip()
            if not new_pass or len(new_pass) < 6:
                status_lbl.config(text="Password baru harus diisi minimal 6 karakter!", fg=COLOR_DANGER)
                return

            status_lbl.config(text="Memperbarui password di database...", fg=COLOR_TEXT_MUTED)
            btn_save.config(state="disabled")

            def worker():
                endpoint = f"{self.server_url}/api/developer/admins/{admin_id}/reset-password"
                headers = {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": f"Bearer {self.auth_token}",
                }
                try:
                    resp = requests.post(endpoint, json={"new_password": new_pass}, headers=headers, timeout=12)
                    data = resp.json() if resp.headers.get("content-type", "").startswith("application/json") else {}

                    if resp.status_code == 200 and data.get("status") == "success":
                        def on_done():
                            modal.destroy()
                            messagebox.showinfo(
                                "Password Berhasil Diganti!",
                                f"Password untuk admin '{admin_name}' ({admin_email}) berhasil diganti!\n\n"
                                f"Password Baru: {new_pass}\n\n"
                                f"Admin sekarang dapat langsung login dengan password ini.",
                                parent=self,
                            )
                            self.fetch_admins()
                            self.fetch_logs()
                        self.after(0, on_done)
                    else:
                        err = data.get("message") or f"Gagal mengubah password (HTTP {resp.status_code})"
                        self.after(0, lambda: [
                            status_lbl.config(text=err, fg=COLOR_DANGER),
                            btn_save.config(state="normal")
                        ])
                except Exception as e:
                    self.after(0, lambda: [
                        status_lbl.config(text=f"Koneksi gagal: {str(e)}", fg=COLOR_DANGER),
                        btn_save.config(state="normal")
                    ])

            threading.Thread(target=worker, daemon=True).start()

        btn_save = tk.Button(
            actions_frame,
            text="Simpan Password Baru",
            font=FONT_BODY_BOLD,
            bg=COLOR_PRIMARY,
            fg="#FFFFFF",
            relief="flat",
            padx=16,
            pady=8,
            cursor="hand2",
            command=submit_reset,
        )
        btn_save.pack(side="left")

        btn_cancel = tk.Button(
            actions_frame,
            text="Batal",
            font=FONT_BODY,
            bg=COLOR_BG_CARD_HOVER,
            fg=COLOR_TEXT_PRIMARY,
            relief="flat",
            padx=14,
            pady=8,
            cursor="hand2",
            command=modal.destroy,
        )
        btn_cancel.pack(side="right")

    # -------------------------------------------------------------------------
    # MODAL: TAMBAH ADMIN BARU
    # -------------------------------------------------------------------------
    def open_add_admin_modal(self):
        modal = tk.Toplevel(self)
        modal.title("Tambah Akun Admin Baru")
        modal.geometry("480x420")
        modal.minsize(440, 390)
        modal.configure(bg=COLOR_BG_DARK)
        modal.transient(self)
        modal.grab_set()

        card = tk.Frame(modal, bg=COLOR_BG_CARD, padx=28, pady=24, highlightbackground=COLOR_BORDER, highlightthickness=1)
        card.pack(fill="both", expand=True, padx=20, pady=20)

        tk.Label(card, text="➕ Tambah Admin Baru", font=FONT_TITLE, fg=COLOR_TEXT_PRIMARY, bg=COLOR_BG_CARD).pack(anchor="w", pady=(0, 16))

        # Nama
        tk.Label(card, text="NAMA LENGKAP ADMIN:", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_BG_CARD).pack(anchor="w", pady=(0, 4))
        name_entry = tk.Entry(card, font=FONT_BODY, bg=COLOR_BG_INPUT, fg=COLOR_TEXT_PRIMARY, insertbackground=COLOR_TEXT_PRIMARY, relief="flat", highlightthickness=1, highlightbackground=COLOR_BORDER, highlightcolor=COLOR_PRIMARY)
        name_entry.pack(fill="x", ipady=5, pady=(0, 12))

        # Email
        tk.Label(card, text="EMAIL ADMIN:", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_BG_CARD).pack(anchor="w", pady=(0, 4))
        email_entry = tk.Entry(card, font=FONT_BODY, bg=COLOR_BG_INPUT, fg=COLOR_TEXT_PRIMARY, insertbackground=COLOR_TEXT_PRIMARY, relief="flat", highlightthickness=1, highlightbackground=COLOR_BORDER, highlightcolor=COLOR_PRIMARY)
        email_entry.pack(fill="x", ipady=5, pady=(0, 12))

        # Password
        tk.Label(card, text="PASSWORD AWAL (Minimal 6 Karakter):", font=("Segoe UI", 8, "bold"), fg=COLOR_TEXT_MUTED, bg=COLOR_BG_CARD).pack(anchor="w", pady=(0, 4))
        pwd_entry = tk.Entry(card, font=FONT_BODY, bg=COLOR_BG_INPUT, fg=COLOR_TEXT_PRIMARY, insertbackground=COLOR_TEXT_PRIMARY, relief="flat", highlightthickness=1, highlightbackground=COLOR_BORDER, highlightcolor=COLOR_PRIMARY)
        pwd_entry.pack(fill="x", ipady=5, pady=(0, 14))

        status_lbl = tk.Label(card, text="", font=FONT_SMALL, fg=COLOR_DANGER, bg=COLOR_BG_CARD, wraplength=400)
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
                endpoint = f"{self.server_url}/api/developer/admins"
                headers = {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": f"Bearer {self.auth_token}",
                }
                try:
                    resp = requests.post(endpoint, json={"name": name, "email": email, "password": pwd}, headers=headers, timeout=12)
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
            endpoint = f"{self.server_url}/api/developer/admins/{admin_id}"
            headers = {
                "Accept": "application/json",
                "Authorization": f"Bearer {self.auth_token}",
            }
            try:
                resp = requests.delete(endpoint, headers=headers, timeout=12)
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
            endpoint = f"{self.server_url}/api/developer/logs"
            headers = {
                "Accept": "application/json",
                "Authorization": f"Bearer {self.auth_token}",
            }
            try:
                resp = requests.delete(endpoint, headers=headers, timeout=12)
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
    # FETCH DATA DARI API
    # -------------------------------------------------------------------------
    def refresh_all_data(self):
        self.fetch_stats()
        self.fetch_logs()
        self.fetch_admins()

    def fetch_stats(self):
        def worker():
            endpoint = f"{self.server_url}/api/developer/stats"
            headers = {"Accept": "application/json", "Authorization": f"Bearer {self.auth_token}"}
            try:
                resp = requests.get(endpoint, headers=headers, timeout=10)
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

            endpoint = f"{self.server_url}/api/developer/logs"
            headers = {"Accept": "application/json", "Authorization": f"Bearer {self.auth_token}"}
            try:
                resp = requests.get(endpoint, params=params, headers=headers, timeout=12)
                if resp.status_code == 200:
                    logs = resp.json().get("data", [])
                    raw_map = {item["id"]: item for item in logs}

                    def update_tree():
                        self._logs_raw_map = raw_map
                        # Bersihkan tabel lama
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
            endpoint = f"{self.server_url}/api/developer/admins"
            headers = {"Accept": "application/json", "Authorization": f"Bearer {self.auth_token}"}
            try:
                resp = requests.get(endpoint, headers=headers, timeout=12)
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

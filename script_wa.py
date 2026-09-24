import re

with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'r', encoding='utf-8') as f:
    html = f.read()

wa_css = '''
    /* ============================================================
       FLOATING WHATSAPP BUTTON
    ============================================================ */
    .bb-wa-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: #25D366;
        color: white;
        border-radius: 50px;
        padding: 10px 20px 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
        z-index: 1000;
        transition: all 0.3s ease;
        animation: waPulse 2s infinite;
    }
    .bb-wa-float:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.6);
        color: white;
    }
    .bb-wa-icon {
        background-color: white;
        color: #25D366;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.3rem;
    }
    .bb-wa-text {
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.5px;
    }
    @keyframes waPulse {
        0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(37, 211, 102, 0); }
        100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
    }
    @media (max-width: 768px) {
        .bb-wa-float {
            bottom: 20px;
            right: 20px;
            padding: 8px 16px 8px 10px;
        }
        .bb-wa-text {
            font-size: 0.9rem;
        }
    }
</style>'''

wa_html = '''
    {{-- FLOATING WHATSAPP BUTTON --}}
    {{-- Ganti nomor 628... dengan nomor WA tujuan --}}
    <a href="https://wa.me/6281234567890?text=Halo%20DNA%20Advertising,%20saya%20tertarik%20untuk%20order%20Billboard." target="_blank" class="bb-wa-float">
        <div class="bb-wa-icon">
            <i class="fa-brands fa-whatsapp"></i>
        </div>
        <span class="bb-wa-text">Order Here</span>
    </a>

</div>
@endsection
'''

# First, replace the FIRST </style> tag.
html = html.replace('</style>', wa_css, 1)

# Second, replace the very end of the file
html = html.replace('</div>\n@endsection', wa_html)

with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'w', encoding='utf-8') as f:
    f.write(html)

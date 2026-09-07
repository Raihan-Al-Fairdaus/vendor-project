with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'r', encoding='utf-8') as f:
    html = f.read()

anim_css = '''
    /* OPTIMASI: Matikan animasi background yang sangat berat di mobile agar backdrop-filter tidak menyebabkan frame drop */
    @media (max-width: 991px) {
        .bb-bg-gradient, .bb-glow-blob-1, .bb-glow-blob-2, .bb-wrapper {
            animation: none !important;
        }
        .bb-item-card, .bb-search-card {
            /* Fallback rendering filter jika GPU kesulitan */
            -webkit-backdrop-filter: none !important;
            backdrop-filter: none !important;
            background: rgba(30, 58, 138, 0.7) !important; /* warna fallback yang solid/gelap */
        }
    }
</style>'''

# Replace only the first occurrence of </style>
html = html.replace('</style>', anim_css, 1)

with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'w', encoding='utf-8') as f:
    f.write(html)

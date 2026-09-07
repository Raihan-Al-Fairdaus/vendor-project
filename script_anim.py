with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'r', encoding='utf-8') as f:
    html = f.read()

anim_css = '''
    /* OPTIMASI: Matikan animasi background yang sangat berat di mobile agar backdrop-filter tidak menyebabkan lag */
    @media (max-width: 768px) {
        .bb-bg-gradient, .bb-glow-blob-1, .bb-glow-blob-2, .bb-wrapper {
            animation: none !important;
        }
        .bb-item-card {
            /* Fallback rendering filter jika GPU kesulitan */
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }
    }
</style>'''

html = html.replace('</style>', anim_css)

with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'w', encoding='utf-8') as f:
    f.write(html)

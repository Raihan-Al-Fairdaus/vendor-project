import re

with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'r', encoding='utf-8') as f:
    html = f.read()

# Replace .bb-bg-container CSS
old_bg_css = '''    .bb-bg-container {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        background: #1b3a60; /* Fallback */
    }'''

new_bg_css = '''    html, body {
        background-color: #1b3a60 !important;
        /* overscroll-behavior: none; */ /* Opsi jika ingin mematikan efek bounce, tapi biarkan saja default dan fix backgroundnya */
    }
    .bb-bg-container {
        position: fixed;
        top: -150px;
        bottom: -150px;
        left: -50px;
        right: -50px;
        z-index: 0;
        overflow: hidden;
        background: #1b3a60; /* Fallback */
    }'''

if old_bg_css in html:
    html = html.replace(old_bg_css, new_bg_css)
    print('Replaced .bb-bg-container')
else:
    print('Could not find .bb-bg-container')

with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'w', encoding='utf-8') as f:
    f.write(html)

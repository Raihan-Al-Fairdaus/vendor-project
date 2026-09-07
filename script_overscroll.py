import re

with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'r', encoding='utf-8') as f:
    html = f.read()

# I will find the html, body block and add overscroll-behavior-y: none;
html = re.sub(r'(html,\s*body\s*\{[^}]*)(\})', r'\1    overscroll-behavior-y: none;\n\2', html)

with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'w', encoding='utf-8') as f:
    f.write(html)

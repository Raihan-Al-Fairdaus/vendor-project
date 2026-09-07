import json
import re

with open('svg_icons.json') as f:
    svgs = json.load(f)

def replace_icon(html, old_str, svg_name, color='', extra_style='', extra_classes=''):
    svg = svgs[svg_name]
    if color:
        svg = svg.replace('<svg', f'<svg fill="{color}" {extra_style} class="{extra_classes}"')
    else:
        svg = svg.replace('<svg', f'<svg fill="currentColor" {extra_style} class="{extra_classes}"')
    
    if 'width' not in extra_style:
        svg = svg.replace('<svg', '<svg width="1em" height="1em" ')

    svg = re.sub(r'<!--.*?-->', '', svg, flags=re.DOTALL)
    svg = svg.replace('\n', '')
    return html.replace(old_str, svg)

# 1. Update Billboard Index
with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'r', encoding='utf-8') as f:
    html = f.read()

html = replace_icon(html, '<i class="fa-solid fa-desktop"></i>', 'desktop', extra_style='style="font-size:inherit;"')
html = replace_icon(html, '<i class="fa-solid fa-tv"></i>', 'tv', extra_style='style="font-size:inherit;"')
html = replace_icon(html, '<i class="fa-solid fa-location-dot"></i>', 'location-dot', extra_style='style="font-size:inherit;"')
html = replace_icon(html, '<i class="fa-solid fa-circle-check"></i>', 'circle-check', extra_style='style="font-size:inherit;"')

with open(r'C:\laragon\www\vendor-project\resources\views\bilboard\index.blade.php', 'w', encoding='utf-8') as f:
    f.write(html)


# 2. Update Footer
with open(r'C:\laragon\www\vendor-project\resources\views\layouts\partials\footer.blade.php', 'r', encoding='utf-8') as f:
    html = f.read()

html = replace_icon(html, '<i class="fa-brands fa-linkedin-in"></i>', 'linkedin-in', extra_style='style="font-size:inherit;"')
html = replace_icon(html, '<i class="fa-brands fa-whatsapp"></i>', 'whatsapp', extra_style='style="font-size:inherit;"')
html = replace_icon(html, '<i class="fa-brands fa-instagram"></i>', 'instagram', extra_style='style="font-size:inherit;"')
html = replace_icon(html, '<i class="fa-solid fa-location-dot"></i>', 'location-dot', extra_style='style="font-size:inherit;margin-top:0.2rem;"')
html = replace_icon(html, '<i class="fa-regular fa-clock"></i>', 'clock', extra_style='style="font-size:inherit;margin-top:0.2rem;"')
html = replace_icon(html, '<i class="fa-solid fa-envelope"></i>', 'envelope', extra_style='style="font-size:inherit;margin-top:0.2rem;"')
html = replace_icon(html, '<i class="fa-solid fa-phone"></i>', 'phone', extra_style='style="font-size:inherit;margin-top:0.2rem;"')
html = replace_icon(html, '<i class="fa-solid fa-arrow-right"></i>', 'arrow-right', extra_style='style="font-size:inherit;"')

with open(r'C:\laragon\www\vendor-project\resources\views\layouts\partials\footer.blade.php', 'w', encoding='utf-8') as f:
    f.write(html)

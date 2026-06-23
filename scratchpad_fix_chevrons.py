import os
import re

count_total = 0
for root, dirs, files in os.walk('resources/views/admin'):
    for file in files:
        if file.endswith('.blade.php'):
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # The pattern looks for <span class="absolute inset-y-0 right-0 ..."> ... <svg ...> ... </svg> ... </span>
            new_content, count = re.subn(r'<span class="absolute inset-y-0 right-0[^>]*>\s*<svg[^>]*>[\s\S]*?</svg>\s*</span>', '', content)
            
            if count > 0:
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f'Fixed {count} double chevrons in {path}')
                count_total += count

print(f'Total fixed: {count_total}')

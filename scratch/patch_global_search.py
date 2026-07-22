import os

updates = {
    "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/ProductResource.php": "name",
    "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/OrderResource.php": "order_number",
    "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/CustomerResource.php": "name",
    "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/BlogResource.php": "title",
}

for file_path, attr in updates.items():
    if not os.path.exists(file_path):
        continue
    with open(file_path, 'r') as f:
        content = f.read()
    
    if "$recordTitleAttribute" not in content:
        target = "protected static ?string $model = "
        insertion = f"    protected static ?string $recordTitleAttribute = '{attr}';\n"
        
        # find the line with protected static ?string $model
        lines = content.split('\n')
        for i, line in enumerate(lines):
            if target in line:
                lines.insert(i + 1, insertion)
                break
        
        with open(file_path, 'w') as f:
            f.write('\n'.join(lines))

print("Global Search attributes added!")

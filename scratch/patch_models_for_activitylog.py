import os

models_to_patch = [
    "/home/sany/Desktop/mmm/e-commerce/app/Models/Product.php",
    "/home/sany/Desktop/mmm/e-commerce/app/Models/Order.php",
    "/home/sany/Desktop/mmm/e-commerce/app/Models/Blog.php"
]

for model_file in models_to_patch:
    if not os.path.exists(model_file):
        continue
    with open(model_file, 'r') as f:
        content = f.read()

    if "Spatie\\Activitylog\\Traits\\LogsActivity" not in content:
        content = content.replace("use Illuminate\\Database\\Eloquent\\Model;", "use Illuminate\\Database\\Eloquent\\Model;\nuse Spatie\\Activitylog\\Traits\\LogsActivity;\nuse Spatie\\Activitylog\\LogOptions;")
        
        # Insert into use statement inside class
        content = content.replace("use HasFactory", "use HasFactory, LogsActivity")
        
        # Add getActivitylogOptions method
        log_options = """    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
"""
        # Find first protected or public function to insert before
        import re
        content = re.sub(r'(protected|public) function', log_options + r'\1 function', content, count=1)
        
        with open(model_file, 'w') as f:
            f.write(content)

print("Models patched for ActivityLog!")

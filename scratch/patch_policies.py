import os
import glob
import re

policies_dir = "/home/sany/Desktop/mmm/e-commerce/app/Policies"
files = glob.glob(os.path.join(policies_dir, "*.php"))

for file in files:
    with open(file, 'r') as f:
        content = f.read()
    
    # Replace method signatures
    # public function viewAny(User $user) -> public function viewAny(\Illuminate\Foundation\Auth\User $user)
    # Using regex to match `(User $user` taking care of optional spaces
    new_content = re.sub(r'\(User \$user', r'(\\Illuminate\\Foundation\\Auth\\User $user', content)
    
    with open(file, 'w') as f:
        f.write(new_content)

print(f"Patched {len(files)} policies!")

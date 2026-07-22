import os

file_path = "/home/sany/Desktop/mmm/e-commerce/app/Providers/Filament/AdminPanelProvider.php"
with open(file_path, 'r') as f:
    content = f.read()

# Add use statement if not exists
if "use pxlrbt\\FilamentSpotlight\\SpotlightPlugin;" not in content:
    content = content.replace("use BezhanSalleh\\FilamentShield\\FilamentShieldPlugin;", "use BezhanSalleh\\FilamentShield\\FilamentShieldPlugin;\nuse pxlrbt\\FilamentSpotlight\\SpotlightPlugin;")

# Add plugin registration
if "SpotlightPlugin::make()" not in content:
    content = content.replace("FilamentShieldPlugin::make(),", "FilamentShieldPlugin::make(),\n                SpotlightPlugin::make(),")

with open(file_path, 'w') as f:
    f.write(content)

print("SpotlightPlugin registered in AdminPanelProvider!")

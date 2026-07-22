import os

resource_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/BlogResource.php"
with open(resource_file, 'r') as f:
    content = f.read()

seo_schema = """            Forms\\Components\\Section::make('SEO & Tags')->schema([
                Forms\\Components\\TagsInput::make('tags')
                    ->label('Tags')
                    ->separator(',')
                    ->columnSpanFull(),
                Forms\\Components\\TextInput::make('seo_title')
                    ->label('SEO Title')
                    ->maxLength(255),
                Forms\\Components\\Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->columnSpanFull(),
            ]),
"""

if "SEO & Tags" not in content:
    content = content.replace("Forms\\Components\\Section::make(\"Publishing Details\")->schema([", seo_schema + "            Forms\\Components\\Section::make(\"Publishing Details\")->schema([")
    with open(resource_file, 'w') as f:
        f.write(content)

print("BlogResource patched!")

<?php
$content = file_get_contents('/home/sany/Desktop/mmm/e-commerce/resources/views/shop/show.blade.php');

// 1. Add sku to mapped variants
$oldMap = <<<CODE
                            'has_own_price'  => \$v->price > 0,
CODE;
$newMap = <<<CODE
                            'sku'            => \$v->sku,
                            'has_own_price'  => \$v->price > 0,
CODE;
$content = str_replace($oldMap, $newMap, $content);

// 2. Add activeSku function
$oldFunc = <<<CODE
                    activePrice() {
CODE;
$newFunc = <<<CODE
                    activeSku() {
                        if (this.selectedVariant) {
                            const variant = this.variants.find(v => v.id === this.selectedVariant);
                            if (variant && variant.sku) {
                                return variant.sku;
                            }
                        }
                        return '{{ addslashes(\$product->sku) }}';
                    },
                    activePrice() {
CODE;
$content = str_replace($oldFunc, $newFunc, $content);

// 3. Update top SKU display
$oldTopSku = <<<CODE
                        <span>SKU: {{ \$product->sku }}</span>
CODE;
$newTopSku = <<<CODE
                        <span>SKU: <span x-text="activeSku()"></span></span>
CODE;
$content = str_replace($oldTopSku, $newTopSku, $content);

// 4. Update table SKU display
$oldTableSku = <<<CODE
                                <tr><td>SKU</td><td>{{ \$product->sku }}</td></tr>
CODE;
$newTableSku = <<<CODE
                                <tr><td>SKU</td><td x-text="activeSku()"></td></tr>
CODE;
$content = str_replace($oldTableSku, $newTableSku, $content);

file_put_contents('/home/sany/Desktop/mmm/e-commerce/resources/views/shop/show.blade.php', $content);
echo "Fixed SKU updating on variant selection.\n";

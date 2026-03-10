<?php

namespace App\Services;

use App\Contracts\CsvToXmlTransformer;
use DOMDocument;

class DemandwareTransformer implements CsvToXmlTransformer
{
    private const CATALOG_NS = 'http://www.demandware.com/xml/impex/catalog/2006-10-31';

    public function transform(string $csv): string
    {
        $rows = $this->parseCsv($csv);
        $masters = $this->groupByMaster($rows);

        $doc = new DOMDocument('1.0', 'UTF-8');
        $catalog = $doc->createElementNS(self::CATALOG_NS, 'catalog');
        $catalog->setAttribute('catalog-id', 'TestCatalog');
        $doc->appendChild($catalog);

        foreach (array_keys($masters) as $productId) {
            $variants = $masters[$productId];
            $first = $variants[0];
            $product = $doc->createElementNS(self::CATALOG_NS, 'product');
            $product->setAttribute('product-id', $productId);
            $displayName = $doc->createElementNS(self::CATALOG_NS, 'display-name');
            $displayName->setAttribute('xml:lang', 'x-default');
            $displayName->appendChild($doc->createTextNode($first['display_name']));
            $product->appendChild($displayName);
            $brand = $doc->createElementNS(self::CATALOG_NS, 'brand');
            $brand->appendChild($doc->createTextNode($first['brand']));
            $product->appendChild($brand);
            $variations = $doc->createElementNS(self::CATALOG_NS, 'variations');
            $variantsEl = $doc->createElementNS(self::CATALOG_NS, 'variants');
            foreach ($variants as $v) {
                $variant = $doc->createElementNS(self::CATALOG_NS, 'variant');
                $variant->setAttribute('product-id', $v['variant_id']);
                if ($v['default'] === 'Y') {
                    $variant->setAttribute('default', 'true');
                }
                $variantsEl->appendChild($variant);
            }
            $variations->appendChild($variantsEl);
            $product->appendChild($variations);
            $catalog->appendChild($product);

            foreach ($variants as $row) {
                $variantProduct = $doc->createElementNS(self::CATALOG_NS, 'product');
                $variantProduct->setAttribute('product-id', $row['variant_id']);
                $customAttrs = $doc->createElementNS(self::CATALOG_NS, 'custom-attributes');
                $colour = $doc->createElementNS(self::CATALOG_NS, 'custom-attribute');
                $colour->setAttribute('attribute-id', 'colour');
                $colour->appendChild($doc->createTextNode($row['colour']));
                $customAttrs->appendChild($colour);
                $size = $doc->createElementNS(self::CATALOG_NS, 'custom-attribute');
                $size->setAttribute('attribute-id', 'size');
                $size->appendChild($doc->createTextNode($row['size']));
                $customAttrs->appendChild($size);
                $variantProduct->appendChild($customAttrs);
                $catalog->appendChild($variantProduct);
            }
        }

        $doc->formatOutput = false;
        $doc->preserveWhiteSpace = false;

        return $doc->saveXML();
    }

    /** @return list<array{product_id: string, brand: string, display_name: string, variant_id: string, colour: string, size: string, default: string}> */
    private function parseCsv(string $csv): array
    {
        $out = [];
        $lines = array_filter(explode("\n", trim($csv)));
        foreach ($lines as $line) {
            $row = str_getcsv($line);
            if (count($row) < 7) {
                continue;
            }
            $out[] = [
                'product_id' => trim($row[0]),
                'brand' => trim($row[1]),
                'display_name' => trim($row[2]),
                'variant_id' => trim($row[3]),
                'colour' => trim($row[4]),
                'size' => trim($row[5]),
                'default' => strtoupper(trim($row[6])),
            ];
        }

        return $out;
    }

    /**
     * @param list<array{product_id: string, ...}> $rows
     * @return array<string, list<array>>
     */
    private function groupByMaster(array $rows): array
    {
        $masters = [];
        foreach ($rows as $row) {
            $masters[$row['product_id']][] = $row;
        }

        return $masters;
    }
}

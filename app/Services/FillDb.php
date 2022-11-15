<?php

namespace App\Services;

use App\{Category, CategoryProduct, PriceProduct, PriceType, Product, Warehouse};

class FillDb
{
    private $data = [];

    public function __construct(array $data, string $type)
    {
        $this->data = $data;

        switch ($type) {
            case 'classifier': $this->fillClassifier();
                break;
            case 'products': $this->fillProducts();
                break;
            case 'offers': $this->fillOffers();
                break;
            case 'prices': $this->fillPrices();
                break;
            case 'rests': $this->fillRests();
                break;
        }
    }

    private function fillClassifier()
    {
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'groups':
                    $this->fillGroups();
                    break;
                case 'warehouses':
                    $this->fillWarehouses();
                    break;
                case 'price_type':
                    $this->fillTypePrices();
                    break;
            }
        }
    }

    private function fillGroups()
    {
        $this->saveGroups($this->data['groups']['Группа']);
    }

    private function saveGroups(array $items, $parent = null)
    {
        if (isset($items['Ид'])) {
            $this->updateGroup($items, $parent);
        } else {
            foreach ($items as $item) {
                $this->updateGroup($item, $parent);
            }
        }
    }

    private function updateGroup($items, $parent)
    {
        $parent = Category::updateOrCreate(['1c_id' => $items['Ид']], [
            '1c_id' => $items['Ид'],
            'title' => $items['Наименование'],
            'slug' => $items['Ид'],
            'parent_id' => is_null($parent) ? $parent : $parent->id,
        ]);

        if (array_key_exists('Группы', $items)) {
            $this->saveGroups($items['Группы']['Группа'], $parent);
        }
    }

    private function fillWarehouses()
    {
        foreach ($this->data['warehouses'] as $item) {
            Warehouse::updateOrCreate(['1c_id' => $item['id']], [
                '1c_id' => $item['id'],
                'name' => $item['name'],
                'is_active' => $item['inactive'] == "true" ? 0 : 1,
            ]);
        }
    }

    private function fillTypePrices()
    {
        foreach ($this->data['price_type'] as $item) {
            PriceType::updateOrCreate(['id' => $item['id']], [
                'id' => $item['id'],
                'name' => $item['name'],
                'currency' => $item['currency'],
            ]);
        }
    }

    private function fillProducts()
    {
        foreach ($this->data as $item) {
            foreach ($item['Группы'] as $group) {
                CategoryProduct::updateOrCreate(['category_1c_id' => $group, 'product_1c_id' => $item['Ид']], [
                    'product_1c_id' => $item['Ид'],
                    'category_1c_id' => $group,
                ]);
            }
        }
    }

    private function fillOffers()
    {
        foreach ($this->data['offers'] as $item) {
            $inactive = $item['inactive'] == "true" ? 0 : 1;
            $slug = \Slug::build($item['name']);

            Product::updateOrCreate(['1c_id' => $item['id']], [
                '1c_id' => $item['id'],
                'title' => $item['name'],
                'slug' => $slug,
                'is_active' => $inactive,
            ]);
        }
    }

    private function fillPrices()
    {
        foreach ($this->data['prices'] as $item) {
            foreach ($item['prices'] as $price) {
                PriceProduct::updateOrCreate(['product_1c_id' => $item['id'], 'price_type_1c_id' => $price['price_type_id']], [
                    'product_1c_id' => $item['id'],
                    'price_type_1c_id' => $price['price_type_id'],
                    'price' => $price['price'],
                    'currency' => $price['currency'],
                ]);
            }
        }
    }

    private function fillRests()
    {
        $values = [];

        foreach ($this->data['rests'] as $item) {
            foreach ($item['warehouses'] as $warehouse) {
                $values[] = "('{$item['id']}', '{$warehouse['id']}', '{$warehouse['count']}')";
            }
        }

        $this->multipleInsertOrUpdate('warehouse_products', ['product_1c_id', 'warehouse_1c_id', 'availability'], $values, ['availability']);
    }

    private function multipleInsertOrUpdate(string $table, array $keys, array $values, array $fields): bool
    {
        $keysOnString = implode(', ', $keys);
        $valuesOnString = implode(', ', $values);
        $fieldsOnString = implode(', ', array_map(function ($item) {
            return "`{$item}` = VALUES({$item})";
        }, $fields));

        $sql = "INSERT INTO `{$table}`($keysOnString) VALUES $valuesOnString ON DUPLICATE KEY UPDATE {$fieldsOnString}";

        return \DB::statement($sql);
    }
}

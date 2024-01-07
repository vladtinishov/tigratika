<?php

namespace App\Actions;

use SimpleXMLElement;

class ConvertXmlToArray
{
    public function handle(SimpleXMLElement $productsXml, SimpleXMLElement $categoriesXml): array
    {
        $products = [];

        foreach ($productsXml as $product) {
            $isAvailable = (string) $product->attributes()->available === 'true' ? 1 : 0;
            $data = [
                'id' => (string) $product->attributes()->id,
                'name' =>(string) $product->name,
                'url' => (string) $product->url,
                'picture' => (string) $product->picture,
                'price' => (int) $product->price,
                'oldprice' => (int) $product->oldprice,
                'currency_id' => (string) $product->currencyId,
                'vendor' => (string) $product->vendor,
                'available' => $isAvailable,
            ];

            $categoryId = (int) $product->categoryId;

            $categoriesData = $this->getCategories($categoriesXml, $categoryId);
            $data += $categoriesData;
            $products[] = $data;
        }

        return $products;
    }

    public function getCategories($xml, $catId)
    {
        $categories = [];
        $cat = $this->getCategoryNameById($xml, $catId);
        $categories['sub_sub_category'] = $cat['name'];

        $cat = $this->getCategoryNameById($xml, $cat['parentId']);
        $categories['sub_category'] = $cat['name'];

        $cat = $this->getCategoryNameById($xml, $cat['parentId']);
        $categories['category'] = $cat['name'];

        if (!$categories['category']) {
            $categories['category'] = $categories['sub_category'];
            $categories['sub_category'] = $categories['sub_sub_category'];
            $categories['sub_sub_category'] = '';
        }


        return $categories;
    }

    public function getCategoryNameById($categoriesXml, $id): array
    {
        $result = ['name' => '', 'parentId' => 0];
        foreach ($categoriesXml as $category) {
            if (!$id) return $result;
            if ((int)$category->attributes()->id === $id) {
                return [
                    'name' => (string) $category,
                    'parentId' => (int) $category->attributes()->parentId,
                ];
            }
        }

        return $result;
    }
}

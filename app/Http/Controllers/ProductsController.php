<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use SimpleXMLElement;

class ProductsController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $title = 'Products';
        return view('pages.products.index')->with([
            'title' => $title,
        ]);
    }

    public function indexJson()
    {
        $result = $this->productRepository
            ->getColl()
            ->get();

        return response()->json($result);
    }

    /**
     * @throws Exception
     */
    public function processAndConvert()
    {
        $xmlString = file_get_contents('https://quarta-hunt.ru/bitrix/catalog_export/export_Ngq.xml');
        $xml = new SimpleXMLElement($xmlString);
        $productsXml = $xml->shop->offers->offer;
        $categoriesXml = $xml->shop->categories->category;

        $productsData = $this->convertProductsXmlToArray($productsXml, $categoriesXml);

        $this->productRepository->createMany($productsData);

        return Excel::download(new ProductsExport($productsData), 'output.xlsx');
    }

    public function convertProductsXmlToArray(SimpleXMLElement $productsXml, SimpleXMLElement $categoriesXml): array
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

            $subSubCategory = $this->getCategoryNameById($categoriesXml, $categoryId);
            $subCategory = $this->getCategoryNameById($categoriesXml, $subSubCategory ? $subSubCategory['parentId'] : false);
            $category = $this->getCategoryNameById($categoriesXml, $subCategory ? $subCategory['parentId'] : false);

            $data['category'] = $category['name'];
            $data['sub_category'] = $subCategory['name'];
            $data['sub_sub_category'] = $subSubCategory['name'];

            $products[] = $data;
        }

        return $products;
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

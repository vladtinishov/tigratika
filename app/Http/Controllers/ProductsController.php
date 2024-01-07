<?php

namespace App\Http\Controllers;

use App\Actions\ConvertXmlToArray;
use App\Exports\ProductsExport;
use App\Repositories\ProductRepository;
use Exception;
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
        $title = 'Продукты';
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
    public function processAndConvert(ConvertXmlToArray $convert)
    {
        $xmlString = file_get_contents(env('PRODUCT_DATA_XML_URL'));
        $xml = new SimpleXMLElement($xmlString);
        $productsXml = $xml->shop->offers->offer;
        $categoriesXml = $xml->shop->categories->category;

        $productsData = $convert->handle($productsXml, $categoriesXml);

        $this->productRepository->createMany($productsData);

        return Excel::download(new ProductsExport($productsData), 'output.xlsx');
    }

}

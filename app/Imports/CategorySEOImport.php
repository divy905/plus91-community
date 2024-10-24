<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Maatwebsite\Excel\Concerns\WithBatchInserts;
use App\Product;
use App\Category;
use App\Helpers\CustomHelper;

use DB;



class CategorySEOImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading{

   public function  __construct(){


   }

   public function model(array $row){    
    $category_slug = $row['category_slug'] ?? '';
    $city_id = $row['city_id'] ?? '';
    $locality_slug = $row['locality_slug'] ?? '';
    $meta_title = $row['meta_title'] ?? '';
    $meta_description = $row['meta_description'] ?? '';
   
        ///////////////////////////////////////////////////////////

   

    $dbArray = [];
    $dbArray['category_id'] = $category_slug??'';
    $dbArray['city_id'] = $city_id??'';
    $dbArray['locality_id'] = $locality_slug??'';
    $dbArray['meta_title'] = $meta_title??'';
    $dbArray['meta_description'] = $meta_description??'';
    DB::table('categories_seo') ->insert($dbArray); 
        ///////////////////////////////////////////////////////
    


        ////////////////////////////////////////////////////////////////////////////////////


    return ;
}



public function batchSize(): int
{
    return 1000;
}

public function chunkSize(): int
{
    return 1000;
}




/* end of class */
}
<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Providers\TaxonomyServiceprovider;


/**
 * Description of WPCategoriesController
 *
 * @author thiago
 */
class WPCategoriesController extends Controller {
  
  
  public static function show() {

    $taxonomy_provider = new TaxonomyServiceprovider();
    $categories = $taxonomy_provider->loadCategoriesFromWP();
    
    return view(
        'categories/list', 
        [
          'categories' => $categories
        ]

    );
  }

  public function import($categoryId) {
    $delete = false;
    
    $taxonomy_provider = new TaxonomyServiceprovider();
    $salved = $taxonomy_provider->importCategory($categoryId, $delete);
    var_dump($salved);
    
  }
  
  
  public function syncWithFirestore() {
    $taxonomy_provider = new TaxonomyServiceprovider();
    $taxonomy_provider->syncWithFirestore();
    echo json_encode(array("response" => "synced"));
    
  }
  
}

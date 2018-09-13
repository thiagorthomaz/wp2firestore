<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;

/**
 * Description of TaxonomyServiceprovider
 *
 * @author thiago
 */
class TaxonomyServiceprovider {
  
  
  public function loadCategoriesFromWP() {

    return DB::select('SELECT wtt.term_id, wtt.term_taxonomy_id, wt.name
    FROM wp_term_taxonomy wtt
    join wp_terms wt
        on wt.term_id = wtt.term_id
    where wtt.taxonomy = "category"');
        
  }
  
  
  
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use App\Models\WFCategory;
use App\Models\WPTerms;
use Google\Cloud\Firestore\FirestoreClient;

/**
 * Description of TaxonomyServiceprovider
 *
 * @author thiago
 */
class TaxonomyServiceprovider {
  
  
  public function loadCategoriesFromWP() {

    return DB::select('SELECT wtt.term_id, wtt.term_taxonomy_id, wt.name, wfc.id as checked
    FROM wp_term_taxonomy wtt
    join wp_terms wt
        on wt.term_id = wtt.term_id
    left join wf_categories wfc
      on wfc.id = wtt.term_id and wfc.deleted_at is null
    where wtt.taxonomy = "category" order by wt.name');
        
  }
  
  public function importCategory($category_id, $delete = false ) {
    
    $category_model = \App\Models\WFCategory::find($category_id);
    if (is_null($category_model)) {
      $category_model = new WFCategory();
      $category_model->created_at = date("Y-m-d H:i:s");
    } else {
      if (is_null($category_model->deleted_at)) {
        $category_model->deleted_at = date("Y-m-d H:i:s");  
      } else {
        $category_model->deleted_at = null;
      }
    }
    
    $category_model->id = $category_id;
    $category_model->updated_at = date("Y-m-d H:i:s");
    
    return $category_model->save();
    
  }
  
  public function syncWithFirestore() {
    
    $firestore = new FirestoreClient();
    $categories = DB::select('SELECT * from wf_categories');
    
    foreach ($categories as $cat) {
      $wf_category = \App\Models\WFCategory::find($cat->id);
      $category = \App\Models\WPTerms::find($cat->id);
      
      if (is_null($cat->deleted_at)) {
        $collectionReference = $firestore->collection("categories");
        $documentReference = $collectionReference->document( $wf_category->id);
        $documentReference->set(array(
            'term_id' => $category->term_id, 
            'name' => $category->name, 
            'slug' => $category->slug 
        ));
        $wf_category->imported_at = date("Y-m-d H:i:s");
      } else {
        $collectionReference = $firestore->collection("categories")->document($wf_category->id)->delete();        
        $wf_category->imported_at = null;
      }
      
      $wf_category->save();
            
    }
    
  }
  
  public function loadCategoriesToImport() {
    return DB::select('SELECT * from wf_categories where deleted_at is null');
  }
  
  public function categoriesByPost($post_id) {
    return DB::select('SELECT wt.* FROM wp_term_relationships wtr
      join wf_categories wfc
          on wfc.id = wtr.term_taxonomy_id
      join wp_terms wt
          on wt.term_id = wtr.term_taxonomy_id
      where wtr.object_id = ?', [$post_id]);

  }
  
  
  
}

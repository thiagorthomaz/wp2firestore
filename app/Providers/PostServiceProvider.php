<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Google\Cloud\Firestore\FirestoreClient;
use App\Models\WFPost;
use App\Providers\TaxonomyServiceprovider;


/**
 * Description of PostServiceProvider
 *
 * @author thiago
 */
class PostServiceProvider {
 
  public function loadImportedPosts() {
    
    $posts = \App\Models\WFPost::all();
    
    
  }


  public function loadFromFireStore($collection) {
    
    $post_list = array();
    
    $firestore = new FirestoreClient();
   
    $collectionReference = $firestore->collection($collection);

    $documents = $collectionReference->documents();

    foreach($documents as $document) {

      if ($document->exists()) {        
        $post['ID'] = $document['ID'];
        $post['post_title'] = $document['post_title'];
        $post['post_status'] = $document['post_status'];
        $post['post_date'] = $document['post_date'];
        $post['post_modified'] = $document['post_modified'];
        $post['created_at'] = $document['created_at'];
        
        $post_list[] = (object)$post;
      }

    }    
    
    return $post_list;

  }
  
  public function loadFromWordPress() {
       
    $posts = DB::select('select * from wp_posts wp
    left join wf_posts wf
        on wf.post_id = wp.ID
    where wp.post_type="post"');
    
    return $posts;

  }
  
  public function loadFromWordPressByCategory($categoryID) {
       
    $posts = DB::select('SELECT wp.*, wf.* FROM wp_term_relationships wtr
    join wp_terms wt
      on wtr.term_taxonomy_id = wt.term_id
    join wp_posts wp
      on wp.ID = wtr.object_id
    left join wf_posts wf
        on wf.post_id = wp.ID
    where wt.term_id = ?', [$categoryID]);
    
    
    return $posts;

  }
  
  public function loadNotImportedFromWordPress($categoryID = null) {
    
    if (is_null($categoryID)) {
      $posts = DB::select('SELECT distinct wp.* FROM wp_term_relationships wtr
      join wf_categories wfc
        on wfc.id = wtr.term_taxonomy_id
      join wp_terms wt
          on wtr.term_taxonomy_id = wt.term_id
      join wp_posts wp
          on wp.ID = wtr.object_id
      where wp.ID not in (SELECT post_id FROM wf_posts) and wp.post_type="post"');  
    } else {
      $posts = DB::select('SELECT distinct wp.* FROM wp_term_relationships wtr
      join wf_categories wfc
        on wfc.id = wtr.term_taxonomy_id
      join wp_terms wt
          on wtr.term_taxonomy_id = wt.term_id
      join wp_posts wp
          on wp.ID = wtr.object_id
      where wp.ID not in (SELECT post_id FROM wf_posts) and wp.post_type="post"
      and wt.term_id = ?', [$categoryID]);  
    }
    
    
    return $posts;

  }

  public function importFromWordPress() {
    
    $taxonomy = new TaxonomyServiceprovider();
    $categories = $taxonomy->loadCategoriesToImport();
    $posts = array();
    
    foreach ($categories as $cat) {
      $posts_to_import = $this->loadNotImportedFromWordPress($cat->id);
      $posts = array_merge($posts, $posts_to_import);
    }
    
    $firestore = new FirestoreClient();
    $collectionReference = $firestore->collection("posts");
    
    foreach ($posts as $post) {
      $wf_post = new WFPost();
      $wf_post->post_id = $post->ID;
      $wf_post->created_at = date("Y-m-d H:i:s");
      $wf_post->updated_at = date("Y-m-d H:i:s");
      $wf_post->save();

      $post->created_at = date("Y-m-d H:i:s");
      $post->featured_image = $this->loadFeaturedImage($post->ID);
      $documentReference = $collectionReference->newDocument();
      $documentReference->set((array)$post);

    }

  }
  
  public function loadFeaturedImage($post_id) {
    
    $result = DB::select("SELECT concat((select option_value from wp_options where option_name ='siteurl'  limit 1),'/wp-content/uploads/', wpm2.meta_value) as image_path
    FROM wp_posts wp
        INNER JOIN wp_postmeta wpm
            ON (wp.ID = wpm.post_id AND wpm.meta_key = '_thumbnail_id')
        INNER JOIN wp_postmeta wpm2
            ON (wpm.meta_value = wpm2.post_id AND wpm2.meta_key = '_wp_attached_file')
    where wp.ID = ?",
    [$post_id]);

    $image_path = "";
    if (isset($result[0])) {
      $image_path = $result[0]->image_path;
      
    }
    
    return $image_path;
    
  }
  
  
  
}

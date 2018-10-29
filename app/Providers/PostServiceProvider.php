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
       
    $posts = DB::select('SELECT wp.*, wf.* FROM wp_term_relationships wtr
    join wp_terms wt
      on wtr.term_taxonomy_id = wt.term_id
    join wp_posts wp
      on wp.ID = wtr.object_id
    left join wf_posts wf
        on wf.id = wp.ID
    where post_status="publish" and wt.term_id in (select wc.id from wf_categories wc)
    order by wp.post_date desc');
    
    return $posts;

  }
  
  public function loadFromWordPressByCategory($categoryID) {
       
    $posts = DB::select('SELECT wp.*, wf.* FROM wp_term_relationships wtr
    join wp_terms wt
      on wtr.term_taxonomy_id = wt.term_id
    join wp_posts wp
      on wp.ID = wtr.object_id
    left join wf_posts wf
        on wf.id = wp.ID
    where wt.term_id = ?
    order by wp.post_date desc', [$categoryID]);
    
    
    return $posts;

  }
  
  public function loadNotImportedFromWordPress($categoryID = null) {
    
    if (is_null($categoryID)) {
      
      $posts = DB::select('select distinct wp.* from wp_terms wt
      join wp_term_taxonomy wtt
          on wtt.term_id = wt.term_id
      join wp_term_relationships wtr
              on wtr.term_taxonomy_id = wtt.term_taxonomy_id
      join wp_posts wp
              on wp.ID = wtr.object_id
      where wp.post_type="post"
      order by wp.post_date desc');  
      
    } else {
      $posts = DB::select('select distinct wp.* from wp_terms wt
      join wp_term_taxonomy wtt
          on wtt.term_id = wt.term_id
      join wp_term_relationships wtr
              on wtr.term_taxonomy_id = wtt.term_taxonomy_id
      join wp_posts wp
              on wp.ID = wtr.object_id
      where wt.term_id = ?
      order by wp.post_date desc', [$categoryID]);  
      

    }
    
    return $posts;

  }

  public function syncWithFirestoreByCategory($categoryId) {

    $posts_to_import = $this->loadNotImportedFromWordPress($categoryId);
    foreach ($posts_to_import as $post) {
      $this->sendFromWP2FS($post);
    }

  }
  
  public function syncWithFirestore() {
    
    $taxonomy = new TaxonomyServiceprovider();
    $categories = $taxonomy->loadCategoriesToImport();
    
    $posts = array();
    
    $post_to_delete = $this->unsyncPosts();
    
    foreach ($categories as $cat) {
      $posts_to_import = $this->loadNotImportedFromWordPress($cat->id);
      $posts = array_merge($posts, $posts_to_import);
    }
    
    foreach ($posts as $post) {
      $this->sendFromWP2FS($post);
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
  
  private function sendFromWP2FS($post){
    
    $wp_categories = new TaxonomyServiceprovider();
    $categories = $wp_categories->categoriesByPost($post->ID);
    $categories_list = array();
    
    foreach ($categories as $cat) {
      $categories_list[] = $cat->term_id;
    }

    $post->post_categories = $categories_list;
    
    $firestore = new FirestoreClient();
    $collectionReference = $firestore->collection("posts");
    
    $wf_post = \App\Models\WFPost::find($post->ID);
    
    if (!$wf_post) {
      $wf_post = new WFPost();
      $wf_post->id = $post->ID;  
    }
    
    $wf_post->created_at = $post->post_date;
    $wf_post->updated_at = $post->post_modified;
    $wf_post->deleted_at = null;
    $wf_post->save();

    $post->created_at = date("Y-m-d H:i:s");
    $post->featured_image = $this->loadFeaturedImage($post->ID);
    $documentReference = $collectionReference->document( $post->ID);
    $documentReference->set((array)$post);
        
  }
  
  public function delete($post_id){
   
    $post = \App\Models\WFPost::find($post_id);
    if ($post) {
      $post->deleted_at = date("Y-m-d H:i:s");
    
      $firestore = new FirestoreClient();
      $collectionReference = $firestore->collection("posts")->document($post_id)->delete();
      $post->save();
    }
    
    
  }
  
  public function unsyncPosts() {
    $posts = DB::select('SELECT distinct wp.* FROM 
      wf_categories wfc
      join wp_term_relationships wtr
          on wtr.term_taxonomy_id = wfc.id
      join wp_posts wp
              on wp.ID = wtr.object_id
      where wfc.deleted_at is not null');
    
    foreach ($posts as $post) {
      $this->delete($post->ID);
    }
    
  }
  
  
}

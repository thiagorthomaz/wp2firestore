<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;

use Google\Cloud\Firestore\FirestoreClient;

/**
 * Description of PostServiceProvider
 *
 * @author thiago
 */
class PostServiceProvider {
 
  
  
  public function loadFromFireStore($collection) {
    
    $post_list = array();
    
    $firestore = new FirestoreClient();
   
    $collectionReference = $firestore->collection($collection);

    $documents = $collectionReference->documents();

    foreach($documents as $document) {

      if ($document->exists()) {        
        $post_list[] = new Post($document['post'], $document['imagem'],$document['title'],$document['sub_title'], $document['post_link']);
      }

    }    
    print_r($post_list);exit;
    return $post_list;

  }
  
  public function loadFromWordPress() {
    
    $posts = DB::select('select * from wp_posts where post_type="post"');
    return $posts;

  }
  

  public function importFromWordPress() {
    
    $posts = $this->loadFromWordPress();

    
    
    
    
  }  
  
  
  
}

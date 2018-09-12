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
        $post['ID'] = $document['ID'];
        $post['post_title'] = $document['post_title'];
        $post['post_status'] = $document['post_status'];
        $post['post_date'] = $document['post_date'];
        $post['post_modified'] = $document['post_modified'];
        
        $post_list[] = (object)$post;
      }

    }    
    
    return $post_list;

  }
  
  public function loadFromWordPress() {
    
    $posts = DB::select('select * from wp_posts where post_type="post"');
    return $posts;

  }
  

  public function importFromWordPress() {
    
    $posts = $this->loadFromWordPress();
    $firestore = new FirestoreClient();
    $collectionReference = $firestore->collection("posts");
    
    foreach ($posts as $post) {
      $documentReference = $collectionReference->newDocument();
      $documentReference->set((array)$post);
    }

  }  
  
  
  
}

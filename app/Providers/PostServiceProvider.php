<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Google\Cloud\Firestore\FirestoreClient;
use App\Models\WFPost;


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
        
        $post_list[] = (object)$post;
      }

    }    
    
    return $post_list;

  }
  
  public function loadFromWordPress() {
       
    $posts = DB::select('select * from wp_posts where post_type="post"');
    return $posts;

  }
  
  public function loadNotImportedFromWordPress() {
       
    $posts = DB::select('select * from wp_posts where post_type="post" and ID not in (SELECT post_id FROM wf_posts)');
    return $posts;

  }

  public function importFromWordPress() {
    
    $posts = $this->loadNotImportedFromWordPress();

    $firestore = new FirestoreClient();
    $collectionReference = $firestore->collection("posts");
    
    foreach ($posts as $post) {
      $wf_post = new WFPost();
      $wf_post->post_id = $post->ID;
      $wf_post->created_at = date("Y-m-d H:i:s");
      $wf_post->updated_at = date("Y-m-d H:i:s");
      $wf_post->save();

      $documentReference = $collectionReference->newDocument();
      $documentReference->set((array)$post);

    }

  }  
  
  
  
}

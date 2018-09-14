<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Providers\PostServiceProvider;

class FSPostController extends Controller
{

  public static function show()
  {

    $post_provider = new PostServiceProvider();
    $posts = $post_provider->loadFromFireStore("posts");

    return view(
      'post/list', 
      [
          'posts' => $posts,
          'post_type' => 'firestore',
          'title' => 'Posts Firestore'
      ]
    );
  }
    
  public function delete($post_id) {
    $post_provider = new PostServiceProvider();
    $post_provider->delete($post_id);
    echo json_encode(array("response" => "deleted"));
    
  }
}
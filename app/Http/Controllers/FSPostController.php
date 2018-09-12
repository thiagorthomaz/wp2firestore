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
}
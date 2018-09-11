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
      $post_provider->loadFromFireStore("posts");
      
      
      
      exit;
        

        //print_r($documents);

        exit;
        $posts = DB::select('select * from wp_posts where post_type="post"');
        return view('posts', ['posts' => $posts]);
    }
}
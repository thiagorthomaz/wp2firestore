<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\PostServiceProvider;

class WPPostController extends Controller
{

    public static function show()
    {
        
      $post_provider = new PostServiceProvider();
      $posts = $post_provider->loadFromWordPress();

      return view(
          'post/list', 
          [
              'posts' => $posts,
              'post_type' => 'wordpress',
              'title' => 'Posts Wordpress'
          ]

      );
    }
    
    public static function import()
    {
     
      $post_provider = new PostServiceProvider();
      $post_provider->importFromWordPress();
      
      
      
      echo json_encode(array("response" => "imported"));
    }
    
}
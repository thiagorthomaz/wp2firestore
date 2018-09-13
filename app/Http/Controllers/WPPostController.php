<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\PostServiceProvider;
use App\Providers\TaxonomyServiceprovider;

class WPPostController extends Controller
{

    public static function show()
    {
        
      $post_provider = new PostServiceProvider();
      $taxonomy_provider = new TaxonomyServiceprovider();
      
      $posts = $post_provider->loadFromWordPress();
      $categories = $taxonomy_provider->loadCategoriesFromWP();
      
      return view(
          'post/list', 
          [
              'posts' => $posts,
              'categories' => $categories,
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
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\PostServiceProvider;
use App\Providers\TaxonomyServiceprovider;

class WPPostController extends Controller
{

    public static function show($categoryId = null)
    {
      
      $post_provider = new PostServiceProvider();
      $taxonomy_provider = new TaxonomyServiceprovider();
      
      if (is_null($categoryId)) {
        $posts = $post_provider->loadFromWordPress();  
      } else {
        $posts = $post_provider->loadFromWordPressByCategory($categoryId);        
      }
      
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
    
    public static function sync()
    {
      $post_provider = new PostServiceProvider();
      $post_provider->syncWithFirestore();
      echo json_encode(array("response" => "Synced"));
    }
    
    public static function update()
    {
      $post_provider = new PostServiceProvider();
      $post_provider->updateFromWordPress();
      echo json_encode(array("response" => "updated"));

    }
    
    
}
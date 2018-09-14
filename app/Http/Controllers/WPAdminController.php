<?php

namespace App\Http\Controllers;
use App\Providers\AdminServiceProvider;

/**
 * Description of WPAdmin
 *
 * @author thiago
 */
class WPAdminController extends Controller {
  
  public static function show() {
    
    $admin_provider = new AdminServiceProvider();
    $options = $admin_provider->loadWPOptions();
    
    return view(
            'admin',
            [
              'wp_options' => $options
            ]
           );
  }
  
  
}

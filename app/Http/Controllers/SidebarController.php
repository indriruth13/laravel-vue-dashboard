<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
  public function show()
    {
      $path = storage_path('app/sidebar.json');
      $content = json_decode(file_get_contents($path), true);
      return response()->json($content);
    }
}

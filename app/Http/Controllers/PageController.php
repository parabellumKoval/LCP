<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models\Page;

class PageController extends Controller
{
  public function index(Request $request) {
    $page = Page::first();

    return view('pages.index', [
      'page' => $page
    ]);
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * PinkieItについてのコントローラー
 */
class AboutController extends BaseController
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        return view('about');
    }
}

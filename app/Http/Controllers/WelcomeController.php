<?php

namespace App\Http\Controllers;

use App\Models\PopularTopic;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function index(): View
    {
        $popularTopics = PopularTopic::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('welcome', compact('popularTopics'));
    }
}

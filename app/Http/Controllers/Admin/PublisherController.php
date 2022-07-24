<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index(Publisher $publisher, Request $request)
    {
        $publishers = $publisher->paginate(25);

        if ($request->ajax()) {
            return ApiAnswerService::successfulAnswerWithData($publishers);
        }

        return view('admin.publishers.index', compact('publishers'));
    }
}

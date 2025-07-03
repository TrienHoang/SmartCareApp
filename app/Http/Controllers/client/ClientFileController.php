<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientFileController extends Controller
{
    public function index(){
        $files = FileUpload::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        return view('client.uploads.index', compact('files'));
    }
}

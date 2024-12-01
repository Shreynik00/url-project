<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UrlController extends Controller
{
    /**
     * Show the form to input the URL.
     */
    public function index()
    {
        $urls = Url::orderBy('created_at', 'desc')->get();
        return view('Url.index', compact('urls'));
    }

    /**
     * Store the original URL and generate a short URL.
     */
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
        ]);

        $shortUrl = Str::random(6); // Generate a random 6-character string

        Url::create([
            'original_url' => $request->original_url,
            'short_url' => $shortUrl,
            'copy_count' => 0,
            'user_id' => Auth::id(), // Add the authenticated user's ID
        ]);

        return redirect()->route('Url.index')->with('success', 'Short URL created successfully!');
    }

    /**
     * Increment the copy count for a short URL.
     */
    public function incrementCopyCount($id)
    {
        $url = Url::findOrFail($id);
        $url->increment('copy_count');
    
        return response()->json([
            'success' => true,
            'copy_count' => $url->copy_count,
        ]);
    }
    

    public function copyShortUrl($id)
    {
        $url = Url::findOrFail($id);
        $url->incrementCopyCount();

        return response()->json([
            'message' => 'Copy count updated successfully!',
            'copy_count' => $url->copy_count,
        ]);
    }
}

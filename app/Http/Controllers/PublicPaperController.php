<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Http\Request;

class PublicPaperController extends Controller
{
    /**
     * Display the public SEO landing page for a specific paper.
     */
    public function show(Paper $paper, $slug)
    {
        // Redirect to the correct slug if it's wrong (good for SEO)
        if ($slug !== $paper->slug) {
            return redirect()->route('paper.show', ['paper' => $paper->id, 'slug' => $paper->slug], 301);
        }

        // If the paper is not published, abort
        if (!$paper->is_published) {
            abort(404);
        }

        // Load relations for display
        $paper->load(['subject', 'questions' => function ($query) {
            $query->limit(3); // Only load first 3 questions for a sneak peek
        }]);

        return view('paper.show', compact('paper'));
    }
}

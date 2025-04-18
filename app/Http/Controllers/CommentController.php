<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\WasteReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class CommentController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function store(Request $request, WasteReport $wasteReport)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment = new Comment([
            'text' => $validated['text'],
            'user_id' => Auth::id(),
            'waste_report_id' => $wasteReport->id,
        ]);

        $comment->save();

        return redirect()->back()
            ->with('success', 'Comment added successfully.');
    }

    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        return redirect()->back()
            ->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->back()
            ->with('success', 'Comment deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Store a newly created message
     */
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        
        Message::create([
            'content' => $validated['content'],
            'from_user_id' => Auth::id(),
            'to_user_id' => $user->id,
        ]);
        
        return redirect()->route('users.show', $user)->with('success', 'Message sent successfully');
    }

    /**
     * Show the form for editing a message
     */
    public function edit(Message $message)
    {
        // Only allow editing own messages
        if (Auth::id() !== $message->from_user_id) {
            return redirect()->back()->with('error', 'You can only edit your own messages');
        }
        
        return view('messages.edit', compact('message'));
    }

    /**
     * Update the specified message
     */
    public function update(Request $request, Message $message)
    {
        // Only allow editing own messages
        if (Auth::id() !== $message->from_user_id) {
            return redirect()->back()->with('error', 'You can only edit your own messages');
        }
        
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        
        $message->update($validated);
        
        return redirect()->route('users.show', $message->to_user_id)->with('success', 'Message updated successfully');
    }

    /**
     * Remove the specified message
     */
    public function destroy(Message $message)
    {
        // Only allow deleting own messages
        if (Auth::id() !== $message->from_user_id) {
            return redirect()->back()->with('error', 'You can only delete your own messages');
        }
        
        $message->delete();
        
        return redirect()->back()->with('success', 'Message deleted successfully');
    }
}

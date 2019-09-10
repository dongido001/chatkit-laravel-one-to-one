<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Room;
use Illuminate\Support\Facades\Auth;
class ChatController extends Controller
{
    protected $chatkit;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        # Initialize Chatkit PHP SDK
        $this->chatkit = new \Chatkit\Chatkit([
            'instance_locator' => getenv("CHATKIT_INSTANCE_LOCATOR"),
            'key' => getenv("CHATKIT_SECRET_KEY")
        ]);
    }

    /**
     * Get all users
     */
    public function getUsers(Request $request)
    {
        return response()->json([
            'users' => User::all()
        ], 200);
    }

    public function createOrGetRoom(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $from_username = $request->input('from_username');
        $to_username = $request->input('to_username');

        $room = Room::whereIn('from_id', [$from, $to])
                           ->whereIn('to_id', [$from, $to])
                           ->first();

        // If room is already created,return it else create it.
        if ($room) { 
            $room_name = $room->name;
        } else {
            $room_name = "private-{$from_username}-{$to_username}";
            
            $room = new Room();
            $room->name = $room_name;
            $room->from_id = $from;
            $room->to_id = $to;
            $room->save();

            // Create room to Chatkit
            $this->chatkit->createRoom([
                'id' => $room_name,
                'creator_id' => $from_username,
                'name' => $room_name,
                'user_ids' => [$from_username, $to_username],
                'private' => true,
            ]);
        }
        
        return response()->json([
            'room' => $room_name
        ], 200);
    }

    /**
     * Generate Token from Chakit
     */
    public function generateToken(Request $request)
    {
        $auth_data = $this->chatkit->authenticate([
            'user_id' => $request->input('user_id')
        ]);
    
        return response()
                    ->json(
                        $auth_data['body'],
                        $auth_data['status']
                    );
    }
}
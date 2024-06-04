<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AddClientRequest;
use App\Http\Requests\EditClientRequest;
use App\Notifications\NewClientNotification;
use Illuminate\Support\Facades\Notification;

class MemberController extends Controller
{
    public function client_listing()
    {
        return Inertia::render('ClientListing/ClientListing');
    }

    public function client_data(Request $request)
    {
        // Get query parameters from the request
        $search = $request->input('search');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection');
        $upline = $request->input('upline');
        $purchasedEA = $request->input('purchasedEA');
        $fundedPAMM = $request->input('fundedPAMM');
    
        // Start building the query
        $query = User::where('role', 'user');
    
        // Apply search filter if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('id', 'like', '%'.$search.'%');
            });
        }
    
        // Apply other filters if provided
        if ($upline) {
            $query->where('upline_id', $upline);
        }
    
        if ($purchasedEA) {
            // Assuming 'purchasedEA' is a boolean column
            $query->where('purchasedEA', $purchasedEA);
        }
    
        if ($fundedPAMM) {
            // Assuming 'fundedPAMM' is a boolean column
            $query->where('fundedPAMM', $fundedPAMM);
        }
    
        // Apply sorting if provided
        if ($sortField && $sortDirection) {
            $query->orderBy($sortField, $sortDirection);
        } elseif ($sortDirection) {
            if ($sortDirection === 'desc') {
                $query->latest();
            } elseif ($sortDirection === 'asc') {
                $query->oldest();
            }
        }
    
        // Paginate the results
        $clients = $query->paginate(10);
    
        // Add profile_photo to each user
        $clients->each(function ($user) {
            $user->profile_photo = $user->getFirstMediaUrl('profile_photo');
        });
    
        // Return paginated results as JSON
        return response()->json($clients);
    }
        public function addClient(AddClientRequest $request)
    {
        $upline_id = $request->upline['value'];
        $upline = User::find($upline_id);

        if(empty($upline->hierarchyList)) {
            $hierarchyList = "-" . $upline_id . "-";
        } else {
            $hierarchyList = $upline->hierarchyList . $upline_id . "-";
        }

        // Generate a random password with 8 characters
        $password = Str::random(8);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'dial_code' => $request->dial_code['value'],
            'phone' => $request->dial_code['value'] . $request->phone,
            'upline_id' => $upline_id,
            'hierarchyList' => $hierarchyList,
            'role' => 'user',
        ]);

        $user->setReferralId();

        // Send a notification to the user with their password
        $user->notify(new NewClientNotification($password));

        return redirect()->back()->with('toast', [
            'title' => 'New Client Added Successfully!',
            'type' => 'success'
        ]);
        
    }

    public function update_client(EditClientRequest $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        // Find the client by id
        $client = User::findOrFail($request->id);

        // Validate and update client information
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'dial_code' => $request->dial_code['value'],
            'phone' => $request->dial_code['value'] . $request->phone,
        ]);

        return redirect()->back()->with('toast', [
            'title' => 'Client Details Updated!',
            'type' => 'success'
        ]);

    }


    public function delete_client(Request $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        // Find the client by id
        $client = User::findOrFail($request->id);

        // Delete the client
        $client->delete();

        return redirect()->back()->with('toast', [
            'title' => 'Client Has Been Deleted!',
            'type' => 'success'
        ]);

    }

    public function getAllUplines(Request $request)
    {
        $users = User::query()
            ->where('role', 'user')
            ->when($request->filled('query'), function ($query) use ($request) {
                $search = $request->input('query');
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%");
                });
            });

        $users = $users
            ->select('id', 'name')
            ->get();

        $users->each(function ($users) {
            $users->profile_photo = $users->getFirstMediaUrl('profile_photo');
        });

        return response()->json($users);
    
    }
}
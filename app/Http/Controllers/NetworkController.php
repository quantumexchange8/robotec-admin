<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function client_network(Request $request)
    {
        // Retrieve top-level users
        $topLevelUsers = User::where('role', 'user')->whereNull('upline_id')->get();
    
        // Initialize an empty array to store the tree structure
        $tree = [];
    
        // Generate tree structure for each top-level user
        foreach ($topLevelUsers as $user) {
            $tree[] = $this->generateTreeNode($user);
        }
    
        return Inertia::render('Network/ClientNetwork', [
            'clients' => $tree,
        ]);
    }
    
    protected function generateTreeNode($user) {
        $node = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'profile_photo' => $user->getFirstMediaUrl('profile_photo'),
            'email' => $user->email,
            'children' => [], // Initialize children array
        ];
    
        // Recursively load descendants for each child
        foreach ($user->children as $child) {
            $node['children'][] = $this->generateTreeNode($child);
        }
    
        return $node;
    }
}

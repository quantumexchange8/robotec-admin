<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\RunningNumberService;
use App\Http\Requests\AddClientRequest;
use App\Http\Requests\EditClientRequest;
use App\Notifications\NewClientNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

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
        $query = $query->selectRaw('*, 
                    (SELECT SUM(transaction_amount) 
                    FROM transactions 
                    WHERE user_id = users.id 
                    AND transaction_type = "commission" 
                    AND status = "Approved") AS totalCommission');
    
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
            $query->where(function ($q) use ($purchasedEA) {
                if ($purchasedEA === 'yes') {
                    $q->whereHas('transactions', function ($q) {
                        $q->where('transaction_type', 'robotec_purchase')
                            ->where('status', 'Success');
                    });
                } elseif ($purchasedEA === 'no') {
                    $q->whereDoesntHave('transactions', function ($q) {
                        $q->where('transaction_type', 'robotec_purchase')
                            ->where('status', 'Success');
                    });
                }
            });
        }
        
        if ($fundedPAMM) {
            $query->where(function ($q) use ($fundedPAMM) {
                if ($fundedPAMM === 'yes') {
                    $q->whereHas('transactions', function ($q) {
                        $q->where('transaction_type', 'pamm_funding')
                            ->where('status', 'Success');
                    });
                } elseif ($fundedPAMM === 'no') {
                    $q->whereDoesntHave('transactions', function ($q) {
                        $q->where('transaction_type', 'pamm_funding')
                            ->where('status', 'Success');
                    });
                }
            });
        }
            
        // Apply sorting if provided
        if ($sortField && $sortDirection) {
            if ($sortField === 'commission') {
                // Order by totalCommission
                $query->orderBy('totalCommission', $sortDirection);
            } else {
                // Order by the specified field
                $query->orderBy($sortField, $sortDirection);
            }
        } elseif ($sortDirection) {
            if ($sortDirection === 'desc') {
                $query->latest();
            } elseif ($sortDirection === 'asc') {
                $query->oldest();
            }
        }
    
        // Paginate the initial query
        $clients = $query->paginate(10);

        // Transform each paginated user
        $clients->getCollection()->transform(function ($user) {
            $user->profile_photo = $user->getFirstMediaUrl('profile_photo');
            $user->upline = $user->upline()->first();
            if ($user->upline) {
                $user->upline->profile_photo = $user->upline->getFirstMediaUrl('profile_photo');
            }
            $user->cash_wallet = $user->wallets()->where('type', 'cash_wallet')->first();
            $user->referee = $this->countDescendants($user);
            unset($user->children); // Unset the 'children' attribute

            // Calculate total deposit
            $user->totalDeposit = Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'deposit')
                ->where('status', 'Success')
                ->sum('transaction_amount');

            // Calculate total withdrawal
            $user->totalWithdrawal = Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'withdrawal')
                ->where('status', 'Approved')
                ->sum('transaction_amount');

            // // Calculate total commission
            // $user->totalCommission = Transaction::where('to_wallet_id', $user->wallets()->where('type', 'commission_wallet')->value('id'))
            //     ->where('transaction_type', 'commission')
            //     ->where('status', 'Approved')
            //     ->sum('transaction_amount');

            // Calculate total funded PAMM
            $user->totalFundedPAMM = Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'pamm_funding')
                ->where('status', 'Success')
                ->sum('transaction_amount');

            return $user;
        });

        // Return paginated results as JSON
        return response()->json([
            'clients' => $clients,
            'totalClient' => $clients->total(),
        ]);
    }

    protected function countDescendants($user)
    {
        // Initialize the count with the direct children count
        $count = $user->children()->count();
    
        // Recursively count descendants of children
        foreach ($user->children as $child) {
            $count += $this->countDescendants($child);
        }
    
        return $count;
    }
    
    public function addClient(AddClientRequest $request)
    {
        $upline_id = null;
        $hierarchyList = null;
    
        if ($request->upline !== null) 
        {
            $upline_id = $request->upline['value'];
            $upline = User::find($upline_id);
        
            if(empty($upline->hierarchyList || $upline == null)) {
                $hierarchyList = "-" . $upline_id . "-";
            } else {
                $hierarchyList = $upline->hierarchyList . $upline_id . "-";
            }    
        }
        
        $user = Auth::user();
        $dial_code = $request->dial_code['value'];
        $phone = $request->phone;

        // Remove leading '+' from dial code if present
        $dial_code = ltrim($dial_code, '+');

        // Remove leading '+' from phone number if present
        $phone = ltrim($phone, '+');

        // Check if phone number already starts with dial code
        if (!str_starts_with($phone, $dial_code)) {
            // Concatenate dial code and phone number
            $phone_number = '+' . $dial_code . $phone;
        } else {
            // If phone number already starts with dial code, use the phone number directly
            $phone_number = '+' . $phone;
        }

        $users = User::where('dial_code', $request->dial_code['value'])->get();
        
        foreach ($users as $user) {
            if ($user->phone == $phone_number) {
                throw ValidationException::withMessages(['phone' => trans('public.invalid_mobile_phone')]);
            }
        }

        // Generate a random password with 8 characters
        $password = Str::random(8);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'dial_code' => $request->dial_code['value'],
            'phone' => $phone_number,
            'upline_id' => $upline_id,
            'hierarchyList' => $hierarchyList,
            'role' => 'user',
        ]);
    
        $user->setReferralId();
    
        // Create cash wallet
        Wallet::create([
            'user_id' => $user->id,
            'name' => 'Cash Wallet',
            'type' => 'cash_wallet',
            'wallet_address' => RunningNumberService::getID('cash_wallet'),
        ]);

        // Create commission wallet
        Wallet::create([
            'user_id' => $user->id,
            'name' => 'Commission Wallet',
            'type' => 'commission_wallet',
            'wallet_address' => RunningNumberService::getID('commission_wallet'),
        ]);

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
        $dial_code = $request->dial_code['value'];
        $phone = $request->phone;

        // Remove leading '+' from dial code if present
        $dial_code = ltrim($dial_code, '+');

        // Remove leading '+' from phone number if present
        $phone = ltrim($phone, '+');

        // Check if phone number already starts with dial code
        if (!str_starts_with($phone, $dial_code)) {
            // Concatenate dial code and phone number
            $phone_number = '+' . $dial_code . $phone;
        } else {
            // If phone number already starts with dial code, use the phone number directly
            $phone_number = '+' . $phone;
        }

        $users = User::where('dial_code', $request->dial_code['value'])
            ->whereNot('id', $client->id)
            ->get();

        foreach ($users as $user) {
            if ($user->phone == $phone_number) {
                throw ValidationException::withMessages(['phone' => trans('public.invalid_mobile_phone')]);
            }
        }

        // Validate and update client information
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'dial_code' => $request->dial_code['value'],
            'phone' => $phone_number,
        ]);

        // Update cash wallet address
        $cashWallet = Wallet::where('user_id', $client->id)->where('type', 'cash_wallet')->first();

        if ($cashWallet) {
            $cashWallet->update([
                'wallet_address' => $request->wallet_address,
            ]);
        }

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
    
        // Delete the client's wallets
        $client->wallets()->delete();
    
        // Delete the client
        $client->delete();
    
        return redirect()->back()->with('toast', [
            'title' => 'Client Has Been Deleted!',
            'type' => 'success'
        ]);
    }
    
    public function getAllClients(Request $request)
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
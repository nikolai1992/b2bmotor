<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Category;
use App\Manager;
use App\User;

class ClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $queryBuilder = User::whereHas('roles', function($query) {
            $query->where('name', '=', 'Client');
        });

        if ($user->inRole('manager')) {
            $clientsList = Manager::getClients($user->id);

            $queryBuilder = $queryBuilder->whereIn('id', $clientsList);
        }

        $clients = $queryBuilder->paginate(10);

        return view('clients.index', compact('clients'));
    }

    public function edit(User $client)
    {
        $categories = Category::get()->toTree();

        return view('clients.edit', compact('client', 'categories'));
    }

    public function update(Request $request, User $client)
    {
        $client->update($request->all());

        return back();
    }

    public function updateCategories(Request $request)
    {
        $excludeCategories = $request->excludeCategories ?? [];
        Client::addCategories($excludeCategories, $request->clientId);

        return 'Categories has been updated!';
    }
}

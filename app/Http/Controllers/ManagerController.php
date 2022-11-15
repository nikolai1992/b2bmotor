<?php

namespace App\Http\Controllers;

use App\User;
use App\Manager;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    const ROLE_MANAGER = 2;

    public function index()
    {
        $managers = User::whereHas('roles', function($query) {
            $query->where('name', '=', 'Manager');
        })->paginate(50);

        return view('managers.index', compact('managers'));
    }

    public function create()
    {
        return view('managers.create');
    }

    public function store(Request $request)
    {

        try {
            $manager = User::create(array_merge($request->all(), ['password' => bcrypt('testpass')]));

            if ($manager) {
                $manager->roles()->attach(self::ROLE_MANAGER);
                return redirect()->route('list_managers');
            }

            return back()->with('error', 'Неудалось добавить менеджера');

        } catch (ValidationException $e) {

            return back()->with('error',$e->getMessage());

        }

    }

    public function edit(User $manager)
    {
        return view('managers.edit', compact('manager'));
    }

    public function update(User $manager, Request $request)
    {
        $manager->update($request->all());
        return back();
    }

    public function clientsList(User $manager)
    {
        $queryBuilder = Manager::with('client');
        $queryBuilder = $queryBuilder->where('manager_id', $manager->id);
        $clientsList = $queryBuilder->paginate(10);

        $clients = Manager::getClientsWithoutManager();

        return view('managers.clients', compact('manager', 'clientsList', 'clients'));
    }

    public function addClients(Request $request)
    {
        $clients = array_map(function ($item) use ($request) {
            return ['manager_id' => $request->manager_id, 'client_id' => $item];
        }, $request->clients ?? []);

        Manager::addClients($clients);

        return back();
    }
}

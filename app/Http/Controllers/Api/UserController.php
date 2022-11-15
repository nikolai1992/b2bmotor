<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ext = [
            'password' =>  bcrypt('password'),
        ];
        DB::transaction(function () use ($request, $ext)  {
            $data = $request->all();
            $data["all_price"] = $request->all_price == "true" ? 1 : 0;
            $user = User::create(array_merge($data,$ext));

            $clients = User::whereIn('uuid', $request->get('clients'))->get();
            foreach ($clients->all() as $client){
                $client->manager()->associate($user)->save();
            }
        });
        $user = User::where("uuid", $request->uuid)->first();

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $user = User::where('uuid', '=', $uuid)->firstOrFail();
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::where('uuid', '=', $id)->firstOrFail();
        DB::transaction(function () use ($request, $user) {
            $data = $request->all();
            $data["all_price"] = $request->all_price == "true" ? 1 : 0;
            $user->update($data);

            $old_clients = User::where('manager', $user->id)->get();
            foreach ($old_clients->all() as $old_client){
                $old_client->manager()->dissociate()->save();
            }

            $clients = User::whereIn('uuid', $request->get('clients'))->get();

            foreach ($clients->all() as $client){
                $client->manager()->associate($user)->save();
            }
        });


        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('uuid', '=', $id)->firstOrFail();
        $user->delete();

        return response()->json(null, 204);
    }
}

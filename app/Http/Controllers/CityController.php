<?php

namespace App\Http\Controllers;

use App\City;
use App\Events\CityShown;
use Illuminate\Database\QueryException;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * OAuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::query()
            ->paginate();

        return view('cities.list', [
            'cities' => $cities,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cities.form', [
            'city' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:cities,name',
        ]);

        try {
            $city = City::create($data);
        } catch (QueryException $e) {
            return redirect()->back()->withErrors([
                'system' => $e->getMessage(),
            ]);
        }

        return redirect()->route('cities.index')->with('created', $city->getKey());
    }

    /**
     * Display the specified resource.
     *
     * @param  Dispatcher  $events
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(Dispatcher $events, City $city)
    {
        $events->dispatch(new CityShown($city));

        return view('cities.item', [
            'city' => $city,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        return view('cities.form', [
            'city' => $city,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $data = $request->validate([
            'name' => 'required|unique:cities,name,'.$city->id,
        ]);

        try {
            $city->update($data);
        } catch (QueryException $e) {
            return redirect()->back()->withErrors([
                'system' => $e->getMessage(),
            ]);
        }

        return redirect()->route('cities.index')->with('updated', $city->getKey());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $key = $city->getKey();

        try {
            $city->delete();
        } catch (QueryException $e) {
            return redirect()->back()->withErrors([
                'system' => $e->getMessage(),
            ]);
        }

        return redirect()->route('cities.index')->with('deleted', $key);
    }
}

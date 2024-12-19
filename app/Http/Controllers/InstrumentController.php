<?php

namespace App\Http\Controllers;

use App\Data\InstrumentData;
use App\Models\Instrument;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\LaravelData\PaginatedDataCollection;

class InstrumentController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Set the authorization for the resource in the constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Instrument::class, 'instrument');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instrumentList = InstrumentData::collect(Instrument::query()->paginate(10), PaginatedDataCollection::class);
        return Inertia::render('Instrument/Index', [
            'instruments' => $instrumentList->toArray()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Instrument/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param InstrumentData $instrumentData
     * @return RedirectResponse
     */
    public function store(InstrumentData $instrumentData, Request $req): RedirectResponse
    {
        try {
            $instrument = Instrument::create($instrumentData->except('id')->toArray());
            return Redirect::back()->with('message', 'Successfully added to the database.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Instrument $instrument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instrument $instrument)
    {
        $instrumentData = InstrumentData::from($instrument->toArray());

        $prev = Instrument::where('id', '<', $instrument->getAttribute('id'))->orderBy('id', 'desc')->first('id');
        $next = Instrument::where('id', '>', $instrument->getAttribute('id'))->orderBy('id', 'asc')->first('id');

        return Inertia::render('Instrument/Edit', [
            'instrument' => $instrumentData,
            'previous'   => $prev,
            'next'       => $next,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param InstrumentData $instrumentData
     * @param Instrument $instrument
     * @return RedirectResponse
     */
    public function update(InstrumentData $instrumentData, Instrument $instrument): RedirectResponse
    {
        try {
            $instrument->update($instrumentData->except('id')->toArray());
            return Redirect::back()->with('message', 'Successfully updated the data.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Instrument $instrument
     * @return RedirectResponse
     */
    public function destroy(Instrument $instrument): RedirectResponse
    {
        try {
            $instrument->delete();
            return Redirect::route('instruments.index', ['page' => \request('page')])->with('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}

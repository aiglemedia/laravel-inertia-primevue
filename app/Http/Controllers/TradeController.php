<?php

namespace App\Http\Controllers;

use App\Data\TradeData;
use App\Models\Instrument;
use App\Models\Trade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\LaravelData\PaginatedDataCollection;

class TradeController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Set the authorization for the resource in the constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Trade::class, 'trade');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instrumentId = 0;
        $totalTrades = 0;

        if (\request('instrument_id')) {
            $instrument = Instrument::query()->withoutGlobalScopes()->findOrFail(\request('instrument_id'));
            $instrumentId = $instrument->id;
            $totalTrades = Trade::query()
                ->where('instrument_id', $instrumentId)->count();
        }
        $instrumentListQuery = Instrument::query()->select(['id', 'name'])->orderBy('name');
        $tradesQuery = Trade::query()->select(['id', 'instrument_id', 'purchase_date', 'quantity', 'price'])->where('instrument_id', $instrumentId);
        $tradeList = TradeData::collect($tradesQuery->get());
        return Inertia::render('Trade/Index', [
            'instrumentList' => $instrumentListQuery->get()->toArray(),
            'trades'         => $tradeList->toArray(),
            'totalTrades'    => $totalTrades,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Trade/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TradeData $tradeData
     * @return RedirectResponse
     */
    public function store(TradeData $tradeData, Request $req): RedirectResponse
    {
        try {
            $trade = Trade::create($tradeData->except('id')->toArray());
            return Redirect::back()->with('message', 'Successfully added to the database.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Trade $trade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trade $trade)
    {
        $tradeData = TradeData::from($trade->toArray());

        $prev = Trade::where('id', '<', $trade->getAttribute('id'))->where('instrument_id', $trade->getAttribute('instrument_id'))->orderBy('id', 'desc')->first('id');
        $next = Trade::where('id', '>', $trade->getAttribute('id'))->where('instrument_id', $trade->getAttribute('instrument_id'))->orderBy('id', 'asc')->first('id');

        return Inertia::render('Trade/Edit', [
            'trade'    => $tradeData,
            'previous' => $prev,
            'next'     => $next,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TradeData $tradeData
     * @param Trade $trade
     * @return RedirectResponse
     */
    public function update(TradeData $tradeData, Trade $trade): RedirectResponse
    {
        try {
            $trade->update($tradeData->except('id')->toArray());
            return Redirect::back()->with('message', 'Successfully updated the data.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Trade $trade
     * @return RedirectResponse
     */
    public function destroy(Trade $trade): RedirectResponse
    {
        try {
            $trade->delete();
            return Redirect::route('trades.index', ['instrument_id' => \request('instrument_id'), 'page' => \request('page')])->with('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}

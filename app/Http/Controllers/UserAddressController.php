<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Http\Requests\UserAddressRequest;

class UserAddressController extends Controller
{
    private $field = [
        'city',
        'district',
        'address',
        'zip_code',
        'contact_name',
        'contact_phone',
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user_addresses.create_and_edit', ['address' => new UserAddress()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserAddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserAddressRequest $request)
    {
        $request->user()->addresses()->create($request->only($this->field));

        flash(__('crud.success.create'))->success()->important();

        return redirect()->route('user_addresses.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserAddress $user_address
     * @return \Illuminate\Http\Response
     */
    public function edit(UserAddress $user_address)
    {
        $this->authorize('own', $user_address);

        return view('user_addresses.create_and_edit', ['address' => $user_address]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserAddressRequest  $request
     * @param UserAddress $user_address
     * @return \Illuminate\Http\Response
     */
    public function update(UserAddressRequest $request, UserAddress $user_address)
    {
        $this->authorize('own', $user_address);

        $user_address->update($request->only($this->field));

        flash(__('crud.success.update'))->success()->important();

        return redirect()->route('user_addresses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserAddress $user_address
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAddress $user_address)
    {
        $this->authorize('own', $user_address);

        $user_address->delete();

        flash(__('crud.success.delete'))->success()->important();

        return redirect()->route('user_addresses.index');
    }
}

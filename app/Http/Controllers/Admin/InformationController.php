<?php

namespace App\Http\Controllers\Admin;

use App\Models\Information;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Information\StoreInformationRequest;
use App\Http\Requests\Admin\Information\UpdateInformationRequest;

class InformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ['information' => Information::first()];
    }

    public function update(UpdateInformationRequest $request, Information $information)
    {
        $form_data = $request->validated();
        $information->update($form_data);
        return response(['message' => 'information updated successfully', 'information' => $information], 200);
    } // end of update

}

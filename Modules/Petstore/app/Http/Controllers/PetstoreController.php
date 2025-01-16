<?php

declare(strict_types=1);

namespace Modules\Petstore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Petstore\Enums\PetStatus;
use Modules\Petstore\Exceptions\ConnectionErrorException;
use Modules\Petstore\Exceptions\PetNotFoundException;
use Modules\Petstore\Http\Requests\IndexRequest;
use Modules\Petstore\Http\Requests\StorePetRequest;
use Modules\Petstore\Services\PetstoreService;

final class PetstoreController extends Controller
{
    public function __construct(private PetstoreService $petstoreService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $status = $request->input('status');

        try {
            return view('petstore::index', [
                'allowed_statuses' => PetStatus::cases(),
                'pets' => $this->petstoreService->getByStatus($status),
                'current_status' => $status,
            ]);
        } catch (ConnectionErrorException $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request)
    {
        $pet = $request->getDto();
        try {
            $this->petstoreService->create($pet);
            return redirect()->route('petstore.index', ['status' => $pet->status])->with(
                'success',
                "$pet->name created."
            );
        } catch (ConnectionErrorException $e) {
            return redirect()->route('petstore.index', ['status' => $pet->status])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('petstore::create', [
            'allowed_statuses' => PetStatus::cases(),
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show(string $id)
    {
        try {
            $pet = $this->petstoreService->get($id);
            return view('petstore::show', [
                'pet' => $pet
            ]);
        } catch (PetNotFoundException $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $pet = $this->petstoreService->get($id);
            return view('petstore::edit', [
                'pet' => $pet,
                'allowed_statuses' => PetStatus::cases(),
            ]);
        } catch (PetNotFoundException $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

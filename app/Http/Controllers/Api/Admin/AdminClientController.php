<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminClientController extends Controller
{
    public function index(): JsonResponse
    {
        $clients = Client::withCount('contents')->orderBy('name')->get();
        return response()->json(['data' => $clients]);
    }

    public function store(Request $request): JsonResponse
    {
        $client = Client::create($this->validated($request));
        return response()->json(['data' => $client->loadCount('contents')], 201);
    }

    public function update(Request $request, Client $client): JsonResponse
    {
        $client->update($this->validated($request, $client));
        return response()->json(['data' => $client->fresh()->loadCount('contents')]);
    }

    public function destroy(Client $client): JsonResponse
    {
        if ($client->contents()->exists()) {
            return response()->json(['message' => 'Este cliente possui conteúdos e não pode ser excluído.'], 422);
        }
        $client->delete();
        return response()->json(status: 204);
    }

    private function validated(Request $request, ?Client $client = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'contact_name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:160', Rule::unique('clients', 'email')->ignore($client)],
            'segment' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}

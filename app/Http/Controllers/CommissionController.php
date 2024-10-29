<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CommissionController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'cpf' => 'required|string',
            'value' => 'required|numeric',
            'date' => 'required|date'
        ]);

        $cpf = $request->input('cpf');
        $value = $request->input('value');
        $date = $request->input('date');

        $transactionData = [
            'id' => uniqid(),
            'cpf' => $cpf,
            'value' => $value,
            'date' => $date,
            'created_at' => now()
        ];


        $filename = 'commission.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $transactionContent = Storage::get($filename);
            $transactions = json_decode($transactionContent, true);
        } else {
            $transactions = [];
        }

        $transactions[] = $transactionData;

        $jsonData = json_encode($transactions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($jsonData === false) {
            return response()->json(['error' => 'Erro ao codificar os dados para JSON'], 500);
        }

        Storage::put($filename, $jsonData);

        return response()->json(['message' => 'Comissão criada com sucesso.'], 201);
    }

    public function findAll(Request $request)
    {
        $filename = 'commission.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $data = json_decode($jsonContent, true);
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function delete(Request $request, $id)
    {
        $filename = 'commission.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $commissions = json_decode($jsonContent, true);

            $updatedCommissions = array_filter($commissions, function($commission) use ($id) {
                return $commission['id'] !== $id;
            });

            if (count($updatedCommissions) === count($commissions)) {
                return response()->json(['error' => 'Comissão não encontrada com o ID fornecido.'], 404);
            }

            $jsonData = json_encode(array_values($updatedCommissions), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            if ($jsonData === false) {
                return response()->json(['error' => 'Erro ao codificar os dados para JSON'], 500);
            }

            Storage::put($filename, $jsonData);

            return response()->json(['message' => 'Comissão excluída com sucesso.'], 200);
        } else {
            return response()->json(['error' => 'Arquivo de comissões não encontrado.'], 404);
        }
    }
}

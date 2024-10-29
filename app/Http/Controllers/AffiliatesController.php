<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AffiliatesController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->only(['name', 'cpf', 'birthDate', 'email', 'phone', 'address', 'city', 'state']);

        $data['id'] = uniqid();
        $data['created_at'] = now();
        $data['active'] = 1;

        $filename = 'affiliates.json';
        $path = storage_path('storage/app' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $user = json_decode($jsonContent, true);

            if ($user === null && json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erro ao decodificar o JSON existente'], 500);
            }
        } else {
            $user = [];
        }

        $user[] = $data;

        $jsonData = json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($jsonData === false) {
            return response()->json(['error' => 'Erro ao codificar os dados para JSON'], 500);
        }

        Storage::put($filename, $jsonData);

        return response()->json(['message' => 'Usuario criada com sucesso'], 201);
    }

    public function findAll(Request $request) {
        $filename = 'affiliates.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $data = json_decode($jsonContent, true);
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function findById($id) {
        $filename = 'affiliates.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $data = json_decode($jsonContent, true);

            $affiliate = collect($data)->firstWhere('id', $id);

            if ($affiliate) {
                return response()->json($affiliate);
            } else {
                return response()->json(['error' => 'Afiliado não encontrado'], 404);
            }
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function update(Request $request, $id) {
        $filename = 'affiliates.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $data = json_decode($jsonContent, true);

            $affiliateIndex = collect($data)->search(function($affiliate) use ($id) {
                return $affiliate['id'] === $id;
            });

            if ($affiliateIndex !== false) {
                $data[$affiliateIndex] = array_merge($data[$affiliateIndex], $request->only(['name', 'cpf', 'birthDate', 'email', 'phone', 'address', 'city', 'state']));

                $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                if ($jsonData === false) {
                    return response()->json(['error' => 'Erro ao codificar os dados para JSON'], 500);
                }

                Storage::put($filename, $jsonData);
                return response()->json(['message' => 'Afiliado atualizado com sucesso'], 200);
            } else {
                return response()->json(['error' => 'Afiliado não encontrado'], 404);
            }
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function deactivate($id)
    {
        $filename = 'affiliates.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $data = json_decode($jsonContent, true);

            $affiliateIndex = collect($data)->search(function($affiliate) use ($id) {
                return $affiliate['id'] === $id;
        });

            if ($affiliateIndex !== false) {
                $data[$affiliateIndex]['active'] = 0;

                $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                if ($jsonData === false) {
                    return response()->json(['error' => 'Erro ao codificar os dados para JSON'], 500);
                }

                Storage::put($filename, $jsonData);
                return response()->json(['message' => 'Afiliado desativado com sucesso'], 200);
            } else {
                return response()->json(['error' => 'Afiliado não encontrado'], 404);
            }
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

}

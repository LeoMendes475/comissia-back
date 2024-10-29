<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->only(['name', 'email', 'password']);
        $data['id'] = uniqid();
        $data['created_at'] = now();
        $data['active'] = 1;

        $filename = 'user.json';
        $path = storage_path('app/' . $filename);

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

    public function findAll(Request $request)
    {
        $filename = 'user.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $data = json_decode($jsonContent, true);
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function findById($id)
    {
        $filename = 'user.json';
        $path = storage_path('app/' . $filename);

        \Log::info('Caminho do arquivo:', ['path' => $path]);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $data = json_decode($jsonContent, true);

            $user = collect($data)->firstWhere('id', $id);

            if ($user) {
                return response()->json($user);
            } else {
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $filename = 'user.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $data = json_decode($jsonContent, true);

            $userIndex = collect($data)->search(function($user) use ($id) {
                return $user['id'] === $id;
            });

            if ($userIndex !== false) {
                $data[$userIndex] = array_merge($data[$userIndex], $request->only(['name', 'email', 'password']));

                $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                if ($jsonData === false) {
                    return response()->json(['error' => 'Erro ao codificar os dados para JSON'], 500);
                }

                Storage::put($filename, $jsonData);
                return response()->json(['message' => 'Usuário atualizado com sucesso'], 200);
            } else {
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function deactivate($id)
    {
        $filename = 'user.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $data = json_decode($jsonContent, true);

            $userIndex = collect($data)->search(function($user) use ($id) {
                return $user['id'] === $id;
        });

            if ($userIndex !== false) {
                $data[$userIndex]['active'] = 0;

                $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                if ($jsonData === false) {
                    return response()->json(['error' => 'Erro ao codificar os dados para JSON'], 500);
                }

                Storage::put($filename, $jsonData);
                return response()->json(['message' => 'Usuário desativado com sucesso'], 200);
            } else {
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

}





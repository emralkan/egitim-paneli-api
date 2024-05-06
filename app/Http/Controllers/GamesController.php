<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Education;
use App\Models\Games;
use App\Models\Language;
use App\Models\Modules;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GamesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function games()
    {
        try {
            $games = Games::all();

            if (!empty($games)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'games' => $games]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function gamesCreate()
    {
        try {
            $education = Education::all();
            $module = Modules::all();

            if (!empty($education)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'education' => $education , 'module' => $module]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function gamesCreated(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'positionX' => 'required',
                'positionY' => 'required',
                'start_frame' => 'required',
                'flagX' => 'required',
                'flagY' => 'required',
                'sentence' => 'required',
                'box_color' => 'required',
                'toolbox' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->errors()], 400);
            }

            $games = Games::create([
                'name' => $request->get('name'),
                'positionX' => $request->get('positionX'),
                'positionY' => $request->get('positionY'),
                'start_frame' => $request->get('start_frame'),
                'flagX' => $request->get('flagX'),
                'flagY' => $request->get('flagY'),
                'sentence' => $request->get('sentence'),
                'box_color' => $request->get('box_color'),
                'toolbox' => $request->get('toolbox'),
            ]);
            $selectedModules = $request->input('modules', []);
            $games->modules()->attach($selectedModules);

            if ($games) {
                return response()->json(['success' => true, 'message' => 'Kayıt işlemi başarıyla tamamlandı.', 'data' => $games], 201);
            } else {
                return response()->json(['success' => false, 'error' => 'Kayıt işlemi sırasında bir hata oluştu. Veritabanına kayıt yapılamadı.'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    public function gamesUpdate($id)
    {
        try {
            if (Auth::guard('api')->check()) {
                $games = Games::find($id);
                $educations = Education::all();

                return response()->json(['success' => true, 'games' => $games, 'educations' => $educations]);
            } else {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 404);
        }
    }


    public function gamesUpdated(Request $request)
    {
        try {
            $updatedEducationData = $request->all();

            if (!isset($updatedEducationData['id'])) {
                return response()->json(['error' => 'Güncellenen Oyunun ID bilgisi eksik'], 400);
            }

            $module = Games::find($updatedEducationData['id']);

            if (!$module) {
                return response()->json(['error' => 'Oyun bulunamadı'], 404);
            }

            $updateData = [
                'name' => $updatedEducationData['name'],
            ];

            $module->update($updateData);

            return response()->json(['success' => true, 'message' => 'Oyun başarıyla güncellendi']);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function gamesDelete($id)
    {
        try {
            $loggedInUser = Auth::user();
            $games = Games::find($id);

            if (!$games) {
                return response()->json(['error' => 'Oyun bulunamadı'], 404);
            }

            if (!$loggedInUser || ($loggedInUser->user_type !== 'admin' && $loggedInUser->id != $games->user_id)) {
                return response()->json(['error' => 'Yetkisiz işlem!'], 403);
            }

            $games->delete();

            return response()->json(['message' => 'Oyun başarıyla silindi'], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


}

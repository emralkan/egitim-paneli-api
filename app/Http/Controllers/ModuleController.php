<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Games;
use Illuminate\Http\Request;
use App\Models\Modules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ModuleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function modules()
    {
        try {
            $modules = Modules::all();

            if (!empty($modules)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'modules' => $modules]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
    public function moduleCreate()
    {
        try {
            $modules = Modules::all();

            if (!empty($modules)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'modules' => $modules]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function moduleCreated(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->errors()], 400);
            }

            $modules = Modules::create([
                'name' => $request->get('name'),
            ]);

            if ($modules) {
                return response()->json(['success' => true, 'message' => 'Kayıt işlemi başarıyla tamamlandı.', 'data' => $modules], 201);
            } else {
                return response()->json(['success' => false, 'error' => 'Kayıt işlemi sırasında bir hata oluştu. Veritabanına kayıt yapılamadı.'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    public function moduleUpdate($id)
    {
        try {
            if (Auth::guard('api')->check()) {
                $modules = Modules::find($id);

                return response()->json(['success' => true, 'modules' => $modules]);
            } else {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 404);
        }
    }


    public function moduleUpdated(Request $request)
    {
        try {
            $updatedEducationData = $request->all();

            if (!isset($updatedEducationData['id'])) {
                return response()->json(['error' => 'Güncellenen modülün ID bilgisi eksik'], 400);
            }

            $module = Modules::find($updatedEducationData['id']);

            if (!$module) {
                return response()->json(['error' => 'Modül bulunamadı'], 404);
            }

            $updateData = [
                'name' => $updatedEducationData['name'],
            ];

            $module->update($updateData);

            return response()->json(['success' => true, 'message' => 'Modül başarıyla güncellendi']);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    public function moduleDelete($id)
    {
        try {
            $loggedInUser = Auth::user();
            $education = Modules::find($id);

            if (!$education) {
                return response()->json(['error' => 'Modül bulunamadı'], 404);
            }

            if (!$loggedInUser || ($loggedInUser->user_type !== 'admin' && $loggedInUser->id != $education->user_id)) {
                return response()->json(['error' => 'Yetkisiz işlem!'], 403);
            }

            $education->delete();

            return response()->json(['message' => 'Eğitim başarıyla silindi'], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

}

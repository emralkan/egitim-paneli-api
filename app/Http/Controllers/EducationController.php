<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Games;
use App\Models\Package;
use App\Models\User;
use App\Models\EducationGame;
use Illuminate\Http\Request;
use App\Models\Education;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class EducationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function education()
    {
        try {
            $education = Education::with('packages', 'games')->get();

            if (!$education->isEmpty()) {
                return response()->json(['success' => true, 'message' => 'Başarılı.', 'education' => $education]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }




    public function educationCreate()
    {
        try {
            $package = Package::all();
            $games = Games::all();

            if (!empty($package)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'package' => $package , 'games' => $games]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }



    public function educationCreated(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'contents' => 'required|max:255',
                'package_options' => 'required',
                'games' => 'array',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->errors()], 400);
            }

            $packageId = $request->input('package_options.0');

            // Education oluştur
            $education = Education::create([
                'name' => $request->get('name'),
                'contents' => $request->get('contents'),
                'package_id' =>  $packageId,
            ]);

            if ($education) {
                $selectedGames = $request->input('games', []);

                foreach ($selectedGames as $gameId) {
                    $educationGame = new EducationGame([
                        'education_id' => $education->id,
                        'game_id' => $gameId,
                    ]);
                    $educationGame->save();
                }

                $selectedEducations = $request->input('package_options', []);
                $education->packages()->sync($selectedEducations);

                return response()->json(['success' => true, 'message' => 'Kayıt işlemi başarıyla tamamlandı.', 'data' => $education], 201);
            } else {
                return response()->json(['success' => false, 'error' => 'Kayıt işlemi sırasında bir hata oluştu. Veritabanına kayıt yapılamadı.'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }




    public function educationUpdate($id)
    {
        try {
            if (Auth::guard('api')->check()) {
                $education = Education::findOrFail($id);

                return response()->json(['success' => true, 'education' => $education]);
            } else {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 404);
        }
    }



    public function educationUpdated(Request $request)
    {
        try {
            $updatedEducationData = $request->all();

            if (isset($updatedEducationData['id'])) {
                $education = Education::find($updatedEducationData['id']);

                if ($education) {
                    $updateData = [
                        'name' => $updatedEducationData['name'],
                        'contents' => $updatedEducationData['contents'],
                        'photo' => $updatedEducationData['photo'],
                    ];

                    if ($this->isEducationDataDifferent($education, $updateData)) {
                        $education->update($updateData);
                        return response()->json(['success' => true, 'message' => 'Eğitim başarıyla güncellendi']);
                    } else {
                        return response()->json(['success' => true, 'message' => 'Değişiklik yapılmadı']);
                    }
                } else {
                    return response()->json(['error' => 'Eğitim bulunamadı'], 404);
                }
            } else {
                return response()->json(['error' => 'Güncellenen eğitimin ID bilgisi eksik'], 400);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    private function isEducationDataDifferent($education, $updateData)
    {
        foreach ($updateData as $key => $value) {
            if ($education->$key !== $value) {
                return true;
            }
        }

        return false;
    }

    public function educationDelete($id)
    {
        try {
            $loggedInUser = Auth::user();
            $education = Education::find($id);

            if (!$education) {
                return response()->json(['error' => 'Eğitim bulunamadı'], 404);
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

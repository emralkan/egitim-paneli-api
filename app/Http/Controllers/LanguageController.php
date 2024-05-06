<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use App\Models\Education;
use App\Models\Language;
use App\Models\LanguagesLine;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\Link;

class LanguageController extends Controller
{

    public function getTranslationsForLocale($locale)
    {
        $translations = LanguagesLine::where('language', $locale)->pluck('text',  'key')->toArray();
        return response()->json($translations);
    }


    public function language()
    {
        try {
            $language = Language::all();

            if (!empty($language)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'language' => $language]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    public function languageCreated(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'language' => 'required|max:255',
                'langkey' => 'required|max:255',
                'status' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->errors()], 400);
            }

            $language = Language::create([
                'language_key' => $request->get('langkey'),
                'language' => $request->get('language'),
                'status' => $request->get('status'),
            ]);


            if ($language) {
                return response()->json(['success' => true, 'message' => 'Kayıt işlemi başarıyla tamamlandı.', 'data' => $language], 201);
            } else {
                return response()->json(['success' => false, 'error' => 'Kayıt işlemi sırasında bir hata oluştu. Veritabanına kayıt yapılamadı.'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    public function languageUpdate($id)
    {
        try {
            if (Auth::guard('api')->check()) {
                $language = Language::find($id);

                return response()->json(['success' => true, 'language' => $language]);
            } else {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 404);
        }
    }

    public function languageUpdated(Request $request)
    {
        try {
            $updatedLanguageData = $request->all();

            if (isset($updatedLanguageData['id'])) {
                $language = Language::find($updatedLanguageData['id']);

                if ($language) {
                    $updateData = [
                        'language' => $updatedLanguageData['language'],
                        'language_key' => $updatedLanguageData['langkey'],
                        'status' => $updatedLanguageData['status'],
                    ];

                    if ( $updateData) {
                        $language->update($updateData);
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

    public function languageDelete($id)
    {
        try {
            $loggedInUser = Auth::user();
            $language = Language::find($id);

            if (!$language) {
                return response()->json(['error' => 'Dil bulunamadı'], 404);
            }

            $language->delete();

            return response()->json(['message' => 'Dil başarıyla silindi'], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function languagesLine()
    {
        try {
            $language = LanguagesLine::all();

            if (!empty($language)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'language' => $language]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function languagesLineCreate()
    {
        try {
            $language = Language::all();

            if (!empty($language)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'language' => $language]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function languagesLineCreated(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'key' => 'required|max:255',
                'text' => 'required',
                'language' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->errors()], 400);
            }

            $language = LanguagesLine::create([
                'key' => $request->get('key'),
                'text' => $request->get('text'),
                'language' => $request->get('language'),
            ]);


            if ($language) {
                return response()->json(['success' => true, 'message' => 'Kayıt işlemi başarıyla tamamlandı.', 'data' => $language], 201);
            } else {
                return response()->json(['success' => false, 'error' => 'Kayıt işlemi sırasında bir hata oluştu. Veritabanına kayıt yapılamadı.'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    public function languagesLineUpdate($id)
    {
        try {
            if (Auth::guard('api')->check()) {
                $languageLine = LanguagesLine::find($id);
                $language = Language::all();

                return response()->json(['success' => true, 'languageLine' => $languageLine , 'language' => $language]);
            } else {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 404);
        }
    }

    public function languagesLineUpdated(Request $request)
    {
        try {
            $updatedLanguageData = $request->all();

            if (isset($updatedLanguageData['id'])) {
                $language = LanguagesLine::find($updatedLanguageData['id']);

                if ($language) {
                    $updateData = [
                        'key' => $updatedLanguageData['key'],
                        'text' => $updatedLanguageData['text'],
                        'language' => $updatedLanguageData['language'],
                    ];

                    if ( $updateData) {
                        $language->update($updateData);
                        return response()->json(['success' => true, 'message' => 'Çeviri başarıyla güncellendi']);
                    } else {
                        return response()->json(['success' => true, 'message' => 'Değişiklik yapılmadı']);
                    }
                } else {
                    return response()->json(['error' => 'Çeviri bulunamadı'], 404);
                }
            } else {
                return response()->json(['error' => 'Güncellenen eğitimin ID bilgisi eksik'], 400);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function languagesLineDelete($id)
    {
        try {
            $loggedInUser = Auth::user();
            $language = LanguagesLine::find($id);

            if (!$language) {
                return response()->json(['error' => 'Dil bulunamadı'], 404);
            }

            $language->delete();

            return response()->json(['message' => 'Çeviri başarıyla silindi'], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}

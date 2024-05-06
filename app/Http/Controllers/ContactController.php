<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Get all contacts.
     *
     * @return JsonResponse
     */
    public function contact()
    {
        try {
            $contacts = Contact::all();

            if (!empty($contacts)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'contacts' => $contacts]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function contacts($id)
    {
        try {
            $contacts = Contact::find($id);

            if (!empty($contacts)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'contacts' => $contacts]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    public function contactmember(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'phone' => 'required|max:255',
                'subject' => 'required|max:255',
                'department' => 'required|max:255',
                'message' => 'required',
                'user_type' => 'in:bireysel,kurumsal',
                'email' => 'required|email|max:255|unique:users',
            ]);


            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->errors()], 400);
            }

            $user = Contact::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'subject' => $request->get('subject'),
                'department' => $request->get('department'),
                'message' => $request->get('message'),
                'phone' => $request->get('phone'),
                'user_type' => $request->get('user_type'),
            ]);


            if ($user) {
                return response()->json(['success' => true, 'message' => 'Kayıt işlemi başarıyla tamamlandı.'], 201);
            } else {
                return response()->json(['success' => false, 'error' => 'Kayıt işlemi sırasında bir hata oluştu. Veritabanına kayıt yapılamadı.'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

}

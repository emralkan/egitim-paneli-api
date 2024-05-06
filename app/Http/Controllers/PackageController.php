<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Education;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function packageCreate()
    {
        try {
            $education = Education::all();

            if (!empty($education)) {
                return response()->json(['success' => true, 'message' => 'ok.' , 'education' => $education]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }




    public function packageCreated(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'validityPeriod' => 'max:255',
                'price' => 'required|max:255',
                'discount' => 'required|max:255',
                'discount_period' => 'max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->errors()], 400);
            }

            $package = Package::create([
                'name' => $request->get('name'),
                'validityPeriod' => $request->get('validityPeriod'),
                'price' => $request->get('price'),
                'discount' => $request->get('discount'),
                'discount_period' => $request->get('discount_period'),
            ]);

            if ($package) {
                return response()->json(['success' => true, 'message' => 'Kayıt işlemi başarıyla tamamlandı.', 'package' => $package], 201);
            } else {
                return response()->json(['success' => false, 'error' => 'Kayıt işlemi sırasında bir hata oluştu. Veritabanına kayıt yapılamadı.'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            $package = Package::all();
            $loggedInUser = Auth::user();

            if (!empty($package)) {
                return response()->json([
                    'success' => true,
                    'message' => 'ok.',
                    'package' => $package,
                    'loggedInUser' => [
                        'id' => $loggedInUser->id,
                        'email' => $loggedInUser->email,
                        'password' => $loggedInUser->password,
                        'user_type' => $loggedInUser->user_type,

                    ],
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Veri bulunamadı.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    public function detail(Request $request, $id)
    {
        $package = Package::with('educations.games.modules', )->find($id);

        if ($package) {
            $userData = $package;

            return response()->json(['userData' => $userData]);
        } else {
            return response()->json(['error' => 'Paket bulunamadı'], 404);
        }
    }



}

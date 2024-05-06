<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */



    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        return $this->respondWithToken($token, $user);
    }


    public function dashboard()
    {
        if (Auth::check()) {
            $user = Auth::user();

            return response()->json(['user' => $user]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }


    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'phone' => 'required|max:255',
                'user_type' => 'required|in:bireysel,kurumsal',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6',
            ]);


            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->errors()], 400);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'password' => Hash::make($request->get('password')),
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

    public function users()
    {
        $users = User::where('user_type', 'bireysel')->orWhere('user_type', 'kurumsal')->orWhere('user_type', 'anonim')->get();
        return response()->json(['user' => $users]);
    }

    public function userUpdate($id)
    {
        try {
            $loggedInUser = Auth::user();

            $user = User::find($id);

            if (!$user || ($loggedInUser && $loggedInUser->user_type !== 'admin' && $loggedInUser->id != $user->id)) {
                return response()->json(['error' => 'Yetkisiz işlem!'], 403);
            }

            return response()->json(['user' => $user], 200);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function userUpdated(Request $request)
    {
        try {
            $updatedUserData = $request->all();

            if (isset($updatedUserData['id'])) {
                $user = User::find($updatedUserData['id']);

                if ($user) {
                    $updateData = [
                        'name' => $updatedUserData['name'],
                        'email' => $updatedUserData['email'],
                        'phone' => $updatedUserData['phone'],
                        'user_type' => $updatedUserData['user_type'],
                    ];

                    if (isset($updatedUserData['password']) && $updatedUserData['password'] !== $user->password) {
                        $updateData['password'] = Hash::make($updatedUserData['password']);
                    }

                    if ($this->isUserDataDifferent($user, $updateData)) {
                        $user->update($updateData);
                        return response()->json(['success' => true, 'message' => 'Kullanıcı başarıyla güncellendi']);
                    } else {
                        return response()->json(['success' => true, 'message' => 'Değişiklik yapılmadı']);
                    }
                } else {
                    return response()->json(['error' => 'Kullanıcı bulunamadı'], 404);
                }
            } else {
                return response()->json(['error' => 'Güncellenen kullanıcının ID bilgisi eksik'], 400);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    private function isUserDataDifferent($user, $updateData)
    {
        foreach ($updateData as $key => $value) {
            if ($user->$key !== $value) {
                return true;
            }
        }

        return false;
    }

    public function delete($id)
    {
        try {
            $loggedInUser = Auth::user();
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'Kullanıcı bulunamadı'], 404);
            }

            if (!$loggedInUser || ($loggedInUser->user_type !== 'admin' && $loggedInUser->id != $user->id)) {
                return response()->json(['error' => 'Yetkisiz işlem!'], 403);
            }

            $user->delete();

            return response()->json(['message' => 'Kullanıcı başarıyla silindi'], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }






    public function getAuthenticatedUser(): \Illuminate\Http\JsonResponse
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token , $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user

        ]);
    }
}

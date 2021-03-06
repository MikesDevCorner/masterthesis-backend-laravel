<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ApiAuthController extends Controller
{
    /**
     * @OA\Post(
     ** path="/login",
     *   tags={"Simple Auth"},
     *   summary="Logs user in and creates new auth token",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=202,
     *       description="Accepted",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated: Authorization information is missing or invalid."
     *   )
     *)
     **/
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNAUTHORIZED);
        }

        if (!auth()->attempt($validator->validate())) {
            return response()->json(['error' => 'Unauthorised'], Response::HTTP_UNAUTHORIZED);
        } else {
            $success['token'] = auth()->user()->createToken('authToken')->accessToken;
            $success['user'] = auth()->user();
            return response()->json(['success' => $success])->setStatusCode(Response::HTTP_ACCEPTED);
        }
    }


    /**
     * @OA\Post(
     ** path="/register",
     *   tags={"Simple Auth"},
     *   summary="Register new user",
     *   operationId="register",
     *
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *       description="Bad Request"
     *   )
     *)
     **/
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('authToken')->accessToken;
        $success['user'] = $user;
        return response()->json(['success' => $success])->setStatusCode(Response::HTTP_CREATED);
    }


    /**
     * @OA\Post(
     ** path="/logout",
     *   tags={"Simple Auth"},
     *   summary="Revokes current access token",
     *   operationId="logout",
     *   security={
     *   {
     *      "passport": {}},
     *   },
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   )
     *)
     **/
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
      $request->user()->token()->revoke();
      return response()->json(null, Response::HTTP_OK);
    }


  /**
   * @OA\Post(
   ** path="/unregister",
   *   tags={"Simple Auth"},
   *   summary="Deletes all user data from system",
   *   operationId="unregister",
   *   security={
   *   {
   *      "passport": {}},
   *   },
   *   @OA\Response(
   *      response=200,
   *       description="Success",
   *      @OA\MediaType(
   *           mediaType="application/json",
   *      )
   *   ),
   *   @OA\Response(
   *      response=401,
   *       description="Unauthenticated"
   *   )
   *)
   **/
  /**
   * details api
   *
   * @return \Illuminate\Http\Response
   */
  public function unregister(Request $request)
  {
    $user = User::where('id', Auth::user()->id)->firstOrFail();
    $user->delete();
    return response()->json(null, Response::HTTP_OK);
  }



    /**
     * @OA\Get(
     ** path="/me",
     *   tags={"Simple Auth"},
     *   summary="Shows info about logged in user",
     *   operationId="me",
     *   security={
     *   {
     *      "passport": {}},
     *   },
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   )
     *)
     **/
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        return response()->json(['success' => $request->user()], Response::HTTP_OK);
    }

}

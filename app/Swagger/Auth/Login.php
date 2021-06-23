<?php
/**
 * @OA\Post(
 *      path="/api/auth/login",
 *      tags={"Auth"},
 *      summary="Log in",
 *      description="Log in to client account to manage orders.",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Pass user credentials",
 *          @OA\JsonContent(
 *              required={"email","password","remember_me"},
 *              @OA\Property(property="email", description="Email of user (type email and max 60 symbols)", type="string", format="email", example="ruslanpanasovskyi@gmail.com"),
 *              @OA\Property(property="password", type="string", format="password", example="ilikelaravel"),
 *              @OA\Property(property="remember_me", type="boolean", example=true),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent()
 *       ),
 *      @OA\Response(
 *          response=422,
 *          description="Unauthenticated or Unprocessable Entity",
 *          @OA\JsonContent()
 *      )
 *  )
 */

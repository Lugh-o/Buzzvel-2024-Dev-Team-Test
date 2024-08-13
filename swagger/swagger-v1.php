<?php

/**
 *  @OA\Info(
 *      version="1.0.0",
 *      title="Holiday Plan API",
 *      description="API documentation for the Holiday Plan app.",
 *      @OA\Contact(
 *          email="lughfalcao@gmail.com"
 *      )
 *  )
 * 
 *  @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Please provide your Bearer token in the Authorization header."
 *  )
 *
 *  @OA\Schema(
 *     schema="HolidayPlan",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="date", type="string", format="date"),
 *     @OA\Property(property="location", type="string"),
 *     @OA\Property(
 *         property="participants",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Participant")
 *     )
 *  )
 * 
 *  @OA\Schema(
 *     schema="Participant",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string")
 *  )
 * 
 *  @OA\Schema(
 *     schema="StoreHolidayPlanRequest",
 *     type="object",
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="date", type="string", format="date"),
 *     @OA\Property(property="location", type="string"),
 *     @OA\Property(
 *         property="participants",
 *         type="array",
 *         @OA\Items(type="object", @OA\Property(property="name", type="string"))
 *     )
 *  )
 * 
 *  @OA\Schema(
 *     schema="UpdateHolidayPlanRequest",
 *     type="object",
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="date", type="string", format="date"),
 *     @OA\Property(property="location", type="string"),
 *     @OA\Property(
 *         property="participants",
 *         type="array",
 *         @OA\Items(type="object", @OA\Property(property="name", type="string"))
 *     )
 *  )
 * 
 *  @OA\Schema(
 *     schema="HolidayPlanCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/HolidayPlan")
 *     )
 *  )
 */
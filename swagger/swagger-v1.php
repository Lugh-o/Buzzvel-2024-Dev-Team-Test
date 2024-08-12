<?php

/**
 * @OA\Schema(
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
 * )
 */

/**
 * @OA\Schema(
 *     schema="Participant",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string")
 * )
 */

/**
 * @OA\Schema(
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
 * )
 */

/**
 * @OA\Schema(
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
 * )
 */
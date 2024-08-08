<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreHolidayPlanRequest;
use App\Http\Requests\V1\UpdateHolidayPlanRequest;
use App\Http\Resources\V1\HolidayPlanCollection;
use App\Http\Resources\V1\HolidayPlanResource;
use App\Models\HolidayPlan;
use App\Models\Participant;

class HolidayPlanController extends Controller
{
    
    /**
     * Retrieve all holiday plans
     * @return HolidayPlanCollection
     */
    public function index()
    {
        $holidayPlans = HolidayPlan::with('participants')->get();

        return new HolidayPlanCollection($holidayPlans);
    }

    /**
     * Retrieve a specific holiday plan by ID
     * @param \App\Models\HolidayPlan $holidayPlan
     * @return HolidayPlanResource
     */
    public function show(HolidayPlan $holidayPlan)
    {
        // Carregar os participantes relacionados
        $holidayPlan->load('participants');

        // Retornar o recurso formatado
        return new HolidayPlanResource($holidayPlan);
    }

    /**
     * Create a new holiday plan
     * @param \App\Http\Requests\V1\StoreHolidayPlanRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreHolidayPlanRequest $request)
    {
        // Filter the Holiday Plan
        $filteredData = $request->only([
            'title',
            'description',
            'date',
            'location',
            'participants'
        ]);

        // Filter the Participants
        $filteredParticipants = collect($filteredData['participants'] ?? [])
            ->map(function ($participant) {
                return array_intersect_key($participant, array_flip(['name']));
            });
        

        // Create the Holiday Plans
        $holidayPlan = HolidayPlan::create([
            'title' => $filteredData['title'],
            'description' => $filteredData['description'],
            'date' => $filteredData['date'],
            'location' => $filteredData['location']
        ]);
        
        // Create the participants and associate them with the Holiday Plan
        foreach ($filteredParticipants as $participant) {
            $holidayPlan->participants()->create($participant);
        }

        // Load the participants relationship
        $holidayPlan->load('participants');
        
        //return the formatted holiday plan
        return new HolidayPlanResource($holidayPlan);
    }

    
    /**
     * Update the specified holiday plan.
     *
     * @param \App\Http\Requests\V1\UpdateHolidayPlanRequest $request
     * @param \App\Models\HolidayPlan $holidayPlan
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateHolidayPlanRequest $request, HolidayPlan $holidayPlan)
    {
        // Filter the Holiday Plan
        $filteredData = $request->only([
            'title',
            'description',
            'date',
            'location',
            'participants'
        ]);

        // Filter the Participants
        $filteredParticipants = collect($filteredData['participants'] ?? [])
            ->map(function ($participant) {
                return array_intersect_key($participant, array_flip(['name']));
            });

        // Update the Holiday Plan
        $holidayPlan->update([
            'title' => $filteredData['title'],
            'description' => $filteredData['description'],
            'date' => $filteredData['date'],
            'location' => $filteredData['location']
        ]);

        // Update or create participants
        $currentParticipantIds = $holidayPlan->participants->pluck('id')->toArray();
        $updatedParticipantIds = collect($filteredParticipants)->pluck('id')->toArray();

        // Delete participants that are not in the updated list
        $participantsToDelete = array_diff($currentParticipantIds, $updatedParticipantIds);
        Participant::whereIn('id', $participantsToDelete)->delete();

        // Update existing participants and create new ones
        foreach ($filteredParticipants as $participant) {
            if (isset($participant['id']) && in_array($participant['id'], $currentParticipantIds)) {
                // Update existing participant
                $existingParticipant = Participant::find($participant['id']);
                $existingParticipant->update($participant);
            } else {
                // Create new participant
                $holidayPlan->participants()->create($participant);
            }
        }

        // Load the participants relationship
        $holidayPlan->load('participants');

        // Return the formatted holiday plan
        return new HolidayPlanResource($holidayPlan);
    }

    public function destroy(HolidayPlan $holidayPlan)
    {
        $holidayPlan->delete();
        return response()->json(null, 204);
    }
}

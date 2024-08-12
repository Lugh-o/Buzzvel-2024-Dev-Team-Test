<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreHolidayPlanRequest;
use App\Http\Requests\V1\UpdateHolidayPlanRequest;
use App\Http\Resources\V1\HolidayPlanCollection;
use App\Http\Resources\V1\HolidayPlanResource;
use App\Models\HolidayPlan;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Holiday Plan API",
 *      description="API documentation for the Holiday Plan app",
 *      @OA\Contact(
 *          email="lughfalcao@gmail.com"
 *      )
 * )
 * 
 *  * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Please provide your Bearer token in the Authorization header."
 * )
 *
 * @OA\PathItem(
 *     path="/api/v1"
 * )
 */

class HolidayPlanController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/holidayplans",
     *      operationId="getHolidayPlansList",
     *      tags={"Holiday Plans"},
     *      summary="Get list of holiday plans",
     *      description="Return a list of all Holiday Plans",
     *      security={{"sanctum": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/HolidayPlan")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     * 
     */
    public function index()
    {
        try {
            $holidayPlans = HolidayPlan::with('participants')->get();
            return new HolidayPlanCollection($holidayPlans);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/holidayplans/{id}",
     *      operationId="getHolidayPlanById",
     *      tags={"Holiday Plans"},
     *      summary="Get specific holiday plan",
     *      description="Retrieve a specific holiday plan by ID",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Holiday Plan ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/HolidayPlan")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Holiday Plan not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function show($id)
    {
        try {
            $holidayPlan = HolidayPlan::with('participants')->findOrFail($id);
            return new HolidayPlanResource($holidayPlan);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Holiday Plan not found'
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/v1/holidayplans",
     *      operationId="storeHolidayPlan",
     *      tags={"Holiday Plans"},
     *      summary="Create new holiday plan",
     *      description="Create a new holiday plan",
     *      security={{"sanctum": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreHolidayPlanRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent(ref="#/components/schemas/HolidayPlan")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function store(StoreHolidayPlanRequest $request)
    {
        $filteredData = $this->filterHolidayPlanData($request);

        try {
            $holidayPlan = HolidayPlan::create($filteredData['holidayPlan']);
            $this->syncParticipants($holidayPlan, $filteredData['participants']);

            return new HolidayPlanResource($holidayPlan->load('participants'));
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);        
        }
    }
 
    /**
     * @OA\Put(
     *      path="/api/v1/holidayplans/{id}",
     *      operationId="updateHolidayPlan",
     *      tags={"Holiday Plans"},
     *      summary="Update a holiday plan",
     *      description="Update an existing holiday plan",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Holiday Plan ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateHolidayPlanRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/HolidayPlan")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Holiday Plan not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function update(UpdateHolidayPlanRequest $request, $id)
    {
        try {
            $holidayPlan = HolidayPlan::findOrFail($id);
            $filteredData = $this->filterHolidayPlanData($request);
    
            $holidayPlan->update($filteredData['holidayPlan']);
            $this->syncParticipants($holidayPlan, $filteredData['participants']);
            return new HolidayPlanResource($holidayPlan->load('participants'));
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);          
        }
    }
        
    /**
     * @OA\Delete(
     *      path="/api/v1/holidayplans/{id}",
     *      operationId="deleteHolidayPlan",
     *      tags={"Holiday Plans"},
     *      summary="Delete a holiday plan",
     *      description="Delete a specific holiday plan by ID",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Holiday Plan ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Holiday Plan deleted successfully",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Holiday Plan not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function destroy(HolidayPlan $holidayPlan)
    {
        try {
            $holidayPlan->delete();
            return response()->json([
                'message' => 'Holiday Plan deleted successfully'
            ], 200);          
        } catch (ModelNotFoundException $th) {
            return response()->json([
                'error' => 'Holiday Plan not found'
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'An error occurred while deleting the holiday plan'
            ], 500);   
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/holidayplans/{id}/pdf",
     *      operationId="getHolidayPlanPdf",
     *      tags={"Holiday Plans"},
     *      summary="Generate PDF for a holiday plan",
     *      description="Trigger PDF generation and download for a specific holiday plan",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Holiday Plan ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="PDF generated successfully",
     *          @OA\MediaType(
     *              mediaType="application/pdf",
     *              @OA\Schema(type="string", format="binary")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Holiday Plan not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function getPdf($id)
    {
        try {
            $holidayPlan = HolidayPlan::with('participants')->findOrFail($id);
            $pdf = Pdf::loadView('holidayplanspdf', ['holidayplan' => new HolidayPlanResource($holidayPlan)]);
            return $pdf->download('holidayplan.pdf');
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Holiday Plan not found'
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Filter holiday plan and participants data from the request
     * @param Request $request
     * @return array
     */
    private function filterHolidayPlanData(Request $request)
    {
        $data = $request->only(['title', 'description', 'date', 'location', 'participants']);
        $participants = collect($data['participants'] ?? [])->map(fn($p) => array_intersect_key($p, array_flip(['name'])));
        return ['holidayPlan' => $data, 'participants' => $participants];
    }

    /**
     * Sync participants with the holiday plan
     * @param HolidayPlan $holidayPlan
     * @param \Illuminate\Support\Collection $participants
     */
    private function syncParticipants(HolidayPlan $holidayPlan, $participants)
    {
        $currentParticipantIds = $holidayPlan->participants->pluck('id')->toArray();
        $updatedParticipantIds = $participants->pluck('id')->toArray();
    
        $participantsToDelete = array_diff($currentParticipantIds, $updatedParticipantIds);
        Participant::whereIn('id', $participantsToDelete)->delete();
    
        foreach ($participants as $participant) {
            if (isset($participant['id']) && in_array($participant['id'], $currentParticipantIds)) {
                Participant::find($participant['id'])->update($participant);
            } else {
                $holidayPlan->participants()->create($participant);
            }
        }
    }
    }

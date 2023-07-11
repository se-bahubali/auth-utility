<?php

namespace StallionExpress\AuthUtility\Annotations;

use OpenApi\Annotations as OA;

class StallionExpressAnnotation
{
    
    /**
     * @OA\Get(
     *     path="/api/stallion/access/token/{token}",
     *     summary="Get bearer token to send with api call",
     *     operationId="st-get-bearer-token",
     *     tags={"Stallion Brain"},
     *     security={ {"sanctum": {} }},
     *
     *      @OA\Parameter(
     *          required=true,
     *          description="Pass the hashed string to get token",
     *          in="path",
     *          name="token",
     *          example="_st_MDFIMTNTQldITjlLOVhCWjVYMldLRzM1RTU",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *
     *      @OA\JsonContent(
     *
     *          @OA\Property(property="data",type="object",
     *                      @OA\Property(
     *                         property="token",
     *                         type="string",
     *                         example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5OTY4NTU3My0zOWU3LTRlNTctODE5MC1kMDc5MThiZWYxODMiLCJqdGkiOiI3NTg2YWEwZjJiZjU3YTIyYTViZWM1OWYyZjU2YWRmNDk5NjRkZGM1NzQxYTI0YjdjM2I5MmM0ZTk1MmE4NjI5NjE0NTQ5M2I0YTE4ZjU5MSIsImlhdCI6MTY4Nzg1NTMzMy4xNTk1NjgsIm5iZiI6MTY4Nzg1NTMzMy4xNTk1NzIsImV4cCI6MTcxOTQ3NzczMy4xMzgzODMsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.pjUVaZORMbjo6qeLfOJtQUvB2YSHSRV1JvaAfwNP0GdyJJMvlP1wNCrtaAnjKUjBZ9H3FVoMmDLoL7BL9Hzfxy_yZEZfxB9lvQKNkA5GczSWLxtklsYTJk1eilkrUkSFBBmF4kRfK80ZpXcFk6vLZ_QifAIU2z71N9zpzOgE1Z2ekir0KBZcXWMKOCARP-FE4aAV-6Lb1s9sncL3vochb-Wk3GYkeT0dXikEiJ3LWPcS5UCCZeCQJG2xjYGCBzat-DeJ1chnfypKan5vT1tycbdsjJHPrHcaVsrt-Uf_SebpkdMdU1cGf-T4Y-TQ0Audsi06PapBcEVWIeckzADZK9sIQ7DoolfNmCyPrExnt_Vyrs5RWQ5rtUxngyAkRYBuUhCU2bQfXljf1ihnf8kB7gK27H8-BQ81RPh6r1fxHYzoTcuPcPzRjLNAAi9AnoqROmqnG1pvQW3AN0HbaHdgaQIwhA9QP6OS0cO4onzjADIq9mtUOLcyosLMWYNlY08CRsbc50dTzlYrB5MMByMqjuuHZsv7ZkDbmc4aMrZrgka06sRhbts_Ayh3lfGxbt1IDlyq82KL03jfKefIldrVmG-w-bJeGY9CGqts74VqDsDARb700GjHo1Y7tIF-92DFoBL0wk5cKMn3810j6kITRCjTUnbrbapvBorE-SdJED0",
     *                      ),
     *
     *          ),
     *          @OA\Property(property="metadata",type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Token returned successfully"
     *               ),
     *             ),
     *          ),
     *   ),
     *
     *   @OA\Response(response=500,description="Internal server error"),
     *
     * )
     */
    public function returnToken()
    {
    }

     /**
     * @OA\Get(
     *     path="/api/stallion/{role}/scopes",
     *     summary="Get role scopes",
     *     operationId="st-user-role-scopes",
     *     tags={"Stallion Brain"},
     *     security={ {"sanctum": {} }},
     *
     *      @OA\Parameter(
     *          required=true,
     *          description="Pass the hashed user role value",
     *          in="path",
     *          name="role",
     *          example="_st_NA==",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *
     *          @OA\JsonContent(
     *
     *                  @OA\Property(
     *                         property="scopes",
     *                         type="array",
     *
     *                          @OA\Items(
     *
     *                              @OA\Property(
     *                                  property="label",
     *                                  type="string",
     *                                  example="Location"
     *                              ),
     *                              @OA\Property(
     *                                  property="key",
     *                                  type="string",
     *                                  example="location"
     *                              ),
     *                              @OA\Property(
     *                                  property="scopes",
     *                                  type="array",
     *
     *                                  @OA\Items(
     *
     *                                      @OA\Property(
     *                                          property="label",
     *                                          type="string",
     *                                          example="Create"
     *                                      ),
     *                                      @OA\Property(
     *                                          property="key",
     *                                          type="string",
     *                                          example="create"
     *                                      ),
     *                                  ),
     *                                  @OA\Items(
     *
     *                                      @OA\Property(
     *                                          property="label",
     *                                          type="string",
     *                                          example="Update"
     *                                      ),
     *                                      @OA\Property(
     *                                          property="key",
     *                                          type="string",
     *                                          example="update"
     *                                      ),
     *                                  ),
     *                              ),
     *                  ),
     *
     *          ),
     *					
     *
     *      ),
     *   ),
     *
     *   @OA\Response(response=500,description="Internal server error"),
     *
     * )
     */
    public function userScopes()
    {
    }
}

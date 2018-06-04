<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\HashId;
use Illuminate\Http\Request;
//use Log;
use App\Http\Responses\Response;

/**
 * Class HashIds
 * @package App\Http\Middleware
 */
class HashIds
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!empty($request->hashedId)) {
                $request->request->add(["id" => HashId::decode($request->hashedId)]);
            }

            if (!empty($request->hashedUserId)) {
                $request->request->add(['user_id' => HashId::decode($request->hashedUserId)]);
            }

            if (!empty($request->hashedCompanyId)) {
                $request->request->add(['company_id' => HashId::decode($request->hashedCompanyId)]);
            }

            if (!empty($request->hashedRequirementId)) {
                $request->request->add(['requirement_id' => HashId::decode($request->hashedRequirementId)]);
            }

            if (!empty($request->hashedServiceId)) {
                $request->request->add(['service_id' => HashId::decode($request->hashedServiceId)]);
            }

            if (!empty($request->hashedRequirementServiceId)) {
                $request->request->add(['requirement_service_id' => HashId::decode($request->hashedRequirementServiceId)]);
            }

            if (!empty($request->hashedAddressId)) {
                $request->request->add(['address_id' => HashId::decode($request->hashedAddressId)]);
            }

            return $next($request);
        } catch (\Exception $e) {
            //Log::error($e);
            return Response::buildDefaultResponse(
                trans('responses.error'),
                trans('responses.resource_not_valid')
            );
        }
    }
}

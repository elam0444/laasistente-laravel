<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\PostServiceRequest;
use App\Repositories\ServiceRepository;
use App\Repositories\ServiceCategoryRepository;
use App\Repositories\CompanyRepository;
use App\Http\Responses\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Log;

/**
 * Class ServiceController
 * @package App\Http\Controllers\Services
 */
class ServiceController extends Controller
{
    /**
     * @var ServiceRepository
     */
    protected $serviceRepository;

    /**
     * @var ServiceCategoryRepository
     */
    protected $serviceCategoryRepository;

    /**
     * @var CompanyRepository
     */
    protected $companyRepository;

    /**
     * RequirementController constructor.
     * @param ServiceRepository $serviceRepository
     * @param ServiceCategoryRepository $serviceCategoryRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(
        ServiceRepository $serviceRepository,
        ServiceCategoryRepository $serviceCategoryRepository,
        CompanyRepository $companyRepository
    )
    {
        $this->serviceRepository = $serviceRepository;
        $this->serviceCategoryRepository = $serviceCategoryRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * Returns Services
     *
     * Routes:
     *  GET service
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServices(Request $request)
    {
       try {
            $services = $this->serviceRepository->with('category')->findWhere([
                'service_category_id' => $request->get('service_category')
            ]);

            return Response::buildDefaultResponse(
                trans('responses.success'),
                trans('responses.qa.call_log.tags.search.success'),
                $services
            );
       } catch (\Exception $e) {
            return Response::buildDefaultResponse(
                trans('responses.error'),
                trans('responses.qa.call_log.tags.search.error')
            );
       }

    }

    /**
     * Returns Service
     *
     * Routes:
     *  GET service/{hashedRequirementId}
     *
     * @param Request $request
     * @param $hashedServiceId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getService(Request $request, $hashedServiceId = null)
    {
        $service = [];
        $serviceCategories = $this->serviceCategoryRepository->all();

        $companies = $this->companyRepository->all();

        if (!empty($request->get('requirement_id'))) {
            $service = $this->serviceRepository->with('category', 'company')
                ->find($request->get('service_id'));
        }

        return view('services.read', [
            'service' => $service,
            'serviceCategories' => $serviceCategories,
            'companies' => $companies
        ]);
    }

    /**
     * Save Service
     *
     * Routes:
     *  POST service/
     *
     * @param PostServiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createService(PostServiceRequest $request)
    {
        try {

            $serviceParams = [
                'company_id' => $request->get('company'),
                'service_category_id' => $request->get('service_category'),
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'units' => $request->get('units'),
                'units_min' => $request->get('minimum'),
                'cost_per_unit' => $request->get('cost_per_unit'),
            ];

            $service = $this->serviceRepository->create($serviceParams);

            return Response::buildDefaultResponse(
                trans('responses.success'),
                trans('responses.users.profile.update.success')
            );
        } catch (\Exception $e) {
            Log::error($e);
            return Response::buildDefaultResponse(
                trans('responses.error'),
                trans('responses.users.profile.update.error')
            );
        }
    }

}

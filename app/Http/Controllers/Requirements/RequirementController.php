<?php

namespace App\Http\Controllers\Requirements;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequirementRequest;
use App\Repositories\AddressRepository;
use App\Repositories\RequirementRepository;
use App\Http\Responses\Response;
use App\Repositories\RequirementServiceRepository;
use App\Repositories\ServiceCategoryRepository;
use App\Repositories\RequirementServiceStatusRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use Log;

/**
 * Class RequirementController
 * @package App\Http\Controllers\Requirements
 */
class RequirementController extends Controller
{
    /**
     * @var RequirementRepository
     */
    protected $requirementRepository;

    /**
     * @var RequirementServiceRepository
     */
    protected $requirementServiceRepository;

    /**
     * @var AddressRepository
     */
    protected $addressRepository;

    /**
     * @var ServiceCategoryRepository
     */
    protected $serviceCategoryRepository;

    /**
     * @var RequirementServiceStatusRepository
     */
    protected $requirementServiceStatusRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * RequirementController constructor.
     * @param RequirementRepository $requirementRepository
     * @param RequirementServiceRepository $requirementServiceRepository
     * @param AddressRepository $addressRepository
     * @param ServiceCategoryRepository $serviceCategoryRepository
     * @param RequirementServiceStatusRepository $requirementServiceStatusRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        RequirementRepository $requirementRepository,
        RequirementServiceRepository $requirementServiceRepository,
        AddressRepository $addressRepository,
        ServiceCategoryRepository $serviceCategoryRepository,
        RequirementServiceStatusRepository $requirementServiceStatusRepository,
        UserRepository $userRepository
    )
    {
        $this->requirementRepository = $requirementRepository;
        $this->requirementServiceRepository = $requirementServiceRepository;
        $this->addressRepository = $addressRepository;
        $this->serviceCategoryRepository = $serviceCategoryRepository;
        $this->requirementServiceStatusRepository = $requirementServiceStatusRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Returns Requirements
     *
     * Routes:
     *  GET requirement/list
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRequirements(Request $request)
    {
        /*$params = [
            'associate_user_id' => $request->get('user_id')
        ];*/

        // $requirements = $this->requirementRepository->with('requirementService')->findWhere($params);

        return view('requirements.list', [
            // 'requirements' => $requirements
        ]);
    }

    /**
     * Returns Requirements
     *
     * Routes:
     *  GET requirement/data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequirementsData(Request $request)
    {
        $params = [
            'user_id' => $request->user()->id
        ];

        $requirements = $this->requirementRepository->with(['requirementService.service', 'requirementService.status', 'user'])->findWhere($params);

        $status = $this->requirementServiceStatusRepository->all();

        foreach ($requirements as $requirement) {
            $requirement->address = $this->addressRepository->find($requirement->address_id);
            $requirement->user->hashed_id = $requirement->user->getHashedId();

            $requirement->hashed_url = route('requirement.edit', $requirement->getHashedId());

            foreach ($requirement->requirementService as $requirementService) {
                $associates = $this->userRepository->with('address')->findWhere([
                    'company_id' => $requirementService->service->company_id
                ]);

                $requirementService->select_associates = view('partials.requirements.select_associates', [
                    'associates' => $associates,
                    'associateUserId' => $requirementService->associate_user_id,
                    'requirementServiceId' => $requirementService->getHashedId()
                ])->render();

                $requirementService->select_status = view('partials.requirements.select_status', [
                    'requirementServiceStatusId' => $requirementService->requirement_service_status_id,
                    'status' => $status,
                    'requirementServiceId' => $requirementService->getHashedId()
                ])->render();
            }
        }

        return Datatables::collection($requirements)->make(true);
    }

    /**
     * Returns Requirement
     *
     * Routes:
     *  GET requirement/{hashedRequirementId}
     *
     * @param Request $request
     * @param $hashedRequirementId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRequirement(Request $request, $hashedRequirementId = null)
    {
        $requirement = [];

        $addresses = $this->addressRepository->findWhere([
            'user_id' => $request->user()->id
        ]);

        $serviceCategories = $this->serviceCategoryRepository->all();

        if (!empty($request->get('requirement_id'))) {
            $requirement = $this->requirementRepository->with('requirementService.service')
                ->find($request->get('requirement_id'));
        }

        return view('requirements.read', [
            'requirement' => $requirement,
            'addresses' => $addresses,
            'serviceCategories' => $serviceCategories
        ]);
    }

    /**
     * Update Requirement Details View
     *
     * Routes:
     *  POST requirement/
     *
     * @param PostRequirementRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRequirement(PostRequirementRequest $request)
    {
        try {

            $requirementParams = [
                'address_id' => $request->get('address'),
                'description' => $request->get('description'),
                'user_id' => $request->user()->id
            ];


            $requirement = $this->requirementRepository->create($requirementParams);

            $requirementServiceParams = [
                'requirement_id' => $requirement->id,
                'service_id' => $request->get('service'),
                'requirement_service_status_id' => 1,
                'delivery_date_time' => Carbon::now(),
                'qty' => $request->get('qty'),
                'total_cost' => $request->get('total_cost'),
            ];

            $requirementServiceParams = $this->requirementServiceRepository->create($requirementServiceParams);


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

    /**
     * Update Requirement Details View
     *
     * Routes:
     *  POST requirement/{hashedRequirementId}
     *
     * @param PostRequirementRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRequirement(PostRequirementRequest $request)
    {
        try {
            $requirement = $this->requirementRepository->find($request->get('requirement_id'));

            $requirementParams = [
                'address_id' => $request->get('address_id'),
                'description' => $request->get('description'),
            ];

            if (!empty($requirement)) {
                $this->requirementRepository->update($requirementParams, $requirement->id);
            }

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

    /**
     * Set Status for requirement service
     *
     * Routes:
     *  GET requirement-service/{hashedRequirementServiceId}/status
     *
     * @param Request $request
     * @param $hashedRequirementServiceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStatus(Request $request, $hashedRequirementServiceId = null)
    {
        try {
            $requirementService = $this->requirementServiceRepository->find($request->get('requirement_service_id'));

            if (!empty($requirementService)) {
                $requirementService = $this->requirementServiceRepository->update([
                    'requirement_service_status_id' => $request->get('status_id')
                ], $requirementService->id);
            }

            return Response::buildDefaultResponse(
                trans('responses.success'),
                trans('responses.requirement.service.status.update.success')
            );
        } catch (\Exception $e) {
            Log::error($e);
            return Response::buildDefaultResponse(
                trans('responses.error'),
                trans('responses.requirement.service.status.update.error')
            );
        }
    }

    /**
     * Set Associate for requirement service
     *
     * Routes:
     *  GET requirement-service/{hashedRequirementServiceId}/associate
     *
     * @param Request $request
     * @param $hashedRequirementServiceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAssociate(Request $request, $hashedRequirementServiceId = null)
    {
        try {
            $requirementService = $this->requirementServiceRepository->find($request->get('requirement_service_id'));

            if (!empty($requirementService)) {
                $requirementService = $this->requirementServiceRepository->update([
                    'associate_user_id' => $request->get('associate_id')
                ], $requirementService->id);
            }

            return Response::buildDefaultResponse(
                trans('responses.success'),
                trans('responses.requirement.service.associate.update.success')
            );
        } catch (\Exception $e) {
            Log::error($e);
            return Response::buildDefaultResponse(
                trans('responses.error'),
                trans('responses.requirement.service.associate.update.error')
            );
        }
    }

}

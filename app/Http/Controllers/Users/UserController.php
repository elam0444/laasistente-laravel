<?php

namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostProfileRequest;
use App\Repositories\UserRepository;
use App\Repositories\AddressRepository;
use App\Http\Responses\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Log;
use PragmaRX\Countries\Package\Countries;

/**
 * Class UserController
 * @package App\Http\Controllers\Users
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var AddressRepository
     *
     */
    protected $addressRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param AddressRepository $addressRepository
     */
    public function __construct(
        UserRepository $userRepository,
        AddressRepository $addressRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->addressRepository = $addressRepository;
    }

    /**
     * Returns User Details View
     *
     * Routes:
     *  GET user/{hashedId}
     *
     * @param Request $request
     * @param $hashedUserId
     * @return View
     */
    public function getProfile(Request $request, $hashedUserId)
    {
        $user = $this->userRepository->with('address')->find($request->get('user_id'));
        $addresses = $user->address;
        $countries = Countries::all()->sortBy('name.common')->pluck('name.common', 'cca3');

        return view('users.profile', [
            'user' => $user,
            'countries' => $countries,
            'addresses' => $addresses
        ]);
    }

    /**
     * Save User Details View
     *
     * Routes:
     *  POST user/{hashedId}
     *
     * @param PostProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(PostProfileRequest $request)
    {
        try {
            $user = $this->userRepository->find($request->get('user_id'));

            $userParams = [
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name')
            ];

            if (!empty($user)) {
                $this->userRepository->update($userParams, $user->id);
            }

            $addressData = [
                'name' => $request->get('address_name'),
                'user_id' => $user->id,
                'country' => $request->get('country'),
                'state' => $request->get('state'),
                'city' => $request->get('city'),
                'address' => $request->get('address'),
                'zip_code' => $request->get('zip_code'),
                'description' => $request->get('address_description')
            ];

            if (!empty($request->get('address_id'))) {
                $address = $this->addressRepository->update($addressData, $request->get('address_id'));
            } else if ($request->get('new_address_flag')) {
                $address = $this->addressRepository->create($addressData);
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

}

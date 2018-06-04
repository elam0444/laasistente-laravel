<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\HashId;
use App\Http\Requests\LoginRequest;
use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Http\Controllers\Controller;
use App\Http\Responses\Response;
use App\Http\Requests\PostSignUpRequest;
use App\Repositories\UserRepository;
use App\Repositories\GenderRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\AddressRepository;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;

/**
 * Class AuthenticationController
 * @package App\Http\Controllers\Auth
 */
class AuthenticationController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var GenderRepository
     */
    protected $genderRepository;

    /**
     * @var CompanyRepository
     */
    protected $companyRepository;

    /**
     * @var AddressRepository
     */
    protected $addressRepository;

    /**
     * AuthenticationController constructor.
     * @param UserRepository $userRepository
     * @param GenderRepository $genderRepository
     * @param CompanyRepository $companyRepository
     * @param AddressRepository $addressRepository
     */
    public function __construct(
        UserRepository $userRepository,
        GenderRepository $genderRepository,
        CompanyRepository $companyRepository,
        AddressRepository $addressRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->genderRepository = $genderRepository;
        $this->companyRepository = $companyRepository;
        $this->addressRepository = $addressRepository;
    }

    /**
     * Display a basic home view with user and role information
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        //$user = Sentinel::getUser();
        $user = $request->user();
        $user = $this->userRepository->find($user->id);

        return view('welcome', [
            'user' => $user
        ]);
    }

    /**
     * Display the login page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Process the login request and update the user variables based on this
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postLogin(LoginRequest $request)
    {
        $user = $this->userRepository->findByField('email', $request->get('email'))->first();

        $errorMessage = trans('responses.auth.login.error_default');

        $areCredentialsValid = Sentinel::validateCredentials($user, $request->only('email', 'password'));

        if ($areCredentialsValid) {
            Sentinel::login($user);
            return redirect()->intended(route('home'));
        }

        return redirect()->back()
            ->withInput()
            ->withErrors([
                'email' => $errorMessage
            ]);
    }

    /**
     * Get the view for signing up
     *
     * Route:
     *  GET /signup
     *
     * @return \Illuminate\View\View
     */
    public function getSignUpView()
    {
        $countries = Countries::all()->sortBy('name.common')->pluck('name.common', 'cca3');
        $gender = $this->genderRepository->all();

        return view('clients.sign_up', [
            'countries' => $countries,
            'gender' => $gender
        ]);
    }

    /**
     * Get the address
     *
     * Route:
     *  GET /address
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddress(Request $request)
    {
        $countries = Countries::all()->sortBy('name.common')->pluck('name.common', 'cca3');
        $data = [
            'countries' => $countries
        ];

        if (!empty($request->get('address_id'))) {
            $address = $this->addressRepository->find($request->get('address_id'));
            $data['address'] = $address;

            $states = Countries::where('cca3', $address->country)
                ->first()
                ->hydrateStates()
                ->states
                ->sortBy('name')
                ->pluck('name');
            $data['states'] = $states;

            $cities = Countries::where('cca3', $address->country)
                ->first()
                ->hydrate('cities')
                ->cities
                ->pluck('name');
            $data['cities'] = $cities;
        }

        return view('partials.users.address', $data)->render();
    }

    /**
     * Get the states for a country
     *
     * Route:
     *  GET /states
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStates(Request $request)
    {
        $states = Countries::where('cca3', $request->get('country'))
            ->first()
            ->hydrateStates()
            ->states
            ->sortBy('name')
            ->pluck('name');

        return Response::buildDefaultResponse(
            trans('responses.success'),
            trans('responses.system.states.success'),
            $states
        );
    }

    /**
     * Get the cities
     *
     * Route:
     *  GET /cities
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request)
    {
        $cities = Countries::where('cca3', $request->get('country'))
            ->first()
            ->hydrate('cities')
            ->cities
            ->pluck('name');

        return Response::buildDefaultResponse(
            trans('responses.success'),
            trans('responses.system.cities.success'),
            $cities
        );
    }

    /**
     * Creates a new account on Sign-Up
     *
     * Route:
     *  POST /signup
     *
     * @param PostSignUpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSignUp(PostSignUpRequest $request)
    {
        try {

            $userData = [
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => strtolower($request->get('email')),
                'password' => $request->get('password'),
                'gender_id' => $request->get('gender'),
                'phone_1' => $request->get('phone_1'),
                'phone_2' => $request->get('phone_2'),
                'title' => $request->get('title')
            ];

            if ($request->get('company_name')) {
                $company = $this->companyRepository->create([
                    'name' => $request->get('company_name'),
                ]);

                $userData['company_id'] = $company->id;
            }

            $user = $this->userRepository->create($userData);

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

            $address = $this->addressRepository->create($addressData);

            $role = Sentinel::findRoleBySlug(Role::SUPER_ADMIN);

            $role->users()->attach($user);

            //$user = Sentinel::findById(1);
            $activation = Activation::create($user);
            Activation::complete($user, $activation->code);

            return Response::buildDefaultResponse(
                trans('responses.success'),
                trans('responses.signup.success')
            );
        } catch (\Exception $e) {
            return Response::buildDefaultResponse(
                trans('responses.error'),
                trans('responses.signup.error')
            );
        }
    }

    /**
     * Display the logout page
     *
     * @return Response
     */
    public function getLogout()
    {
        $user = Sentinel::check();

        Sentinel::logout($user, true);

        return redirect()->intended(route('auth.login'));
    }
}

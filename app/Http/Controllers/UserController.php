<?php

namespace App\Http\Controllers;

use App\Http\DataTransferObjects\UserData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read users')->only('index');
        // $this->middleware('permission:create users')->only(['create', 'store']);
        // $this->middleware('permission:update users')->only(['update', 'edit']);
        // $this->middleware('permission:delete users')->only('destroy');
        // $this->middleware('permission:active users')->only('toggleActive');
    }

    public function index(): JsonResponse
    {
        $users = $users = User::orderBy('location_longitude', 'ASC')->orderBy('location_latitude', 'ASC')->get();
        return datatables()->of($users)
        ->addColumn('operations', function($row){
            $update = auth('admin')->user()->can('update users') ? '<a href="' . route('admin.users.edit', $row->id) . '" class="btn btn-primary btn-sm">Update</a>' : '';
            $delete = auth('admin')->user()->can('update users') ? '<a href="' . route('admin.users.destroy', $row->id) . '" class="btn btn-danger btn-sm delete-user-btn">Delete</a>' : '';
            $active = auth('admin')->user()->can('update users') ? '<a href="' . route('admin.users.toggleActive', $row->id) . '" class="btn btn-' . ($row->is_active ? 'danger' : 'success') . ' btn-sm active-user-btn">' . ($row->is_active ? 'Deactivate' : 'Activate') . '</a>' : '';
            return $update . " " . $delete . " " . $active;
        })
        ->addColumn('full_name', function ($row){
            return $row->first_name . ' ' . $row->mid_name . ' ' . $row->last_name;
        })
        ->addColumn('address', function($row){
            return 'Long: ' . $row->location_longitude . ' Lat: ' . $row->location_latitude;
        })
        ->rawColumns(['operations'])
        ->toJson();
    }

    public function create()
    {
        return view('users.create');
    }


    public function store(UserRequest $request)
    {
        $dto = UserData::fromRequest($request);
        $data = get_object_vars($dto);
        User::create($data);

        return redirect()->route('admin.dashboard');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $dto = UserData::fromRequest($request);
        $data = get_object_vars($dto);

        if($data['password'] == null)
            unset($data['password']);

        deleteImage('profile_images', $user->profile_image);

        if($user->drive_licence_image)
            deleteImage('drive_licence_images', $user->drive_licence_image);

        $user->update($data);

        return redirect()->route('admin.dashboard');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function toggleActive(User $user)
    {
        $user->update([
            'is_active' => ($user->is_active ? 0 : 1)
        ]);

        return response()->json([
            'msg' => 'success'
        ]);
    }
}

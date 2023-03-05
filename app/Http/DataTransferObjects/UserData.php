<?php

namespace App\Http\DataTransferObjects;

use App\Http\Requests\UserRequest;

class UserData {
    public string $first_name;
    public string $mid_name;
    public string $last_name;
    public string $email;
    public string $mobile;
    public ?string $password;
    // public string $address;
    public string $location_longitude;
    public string $location_latitude;
    public string $profile_image;
    public ?string $drive_licence_image;

    public static function fromRequest(UserRequest $request): UserData
    {
        $dto = new self();
        $dto->first_name = $request['first_name'];
        $dto->mid_name = $request['mid_name'] ?? null;
        $dto->last_name = $request['last_name'];
        $dto->email = $request['email'];
        $dto->mobile = $request['mobile'];
        $dto->password = $request['password'] ? bcrypt($request['password']) : null;
        // $dto->address = $request['address'];
        $dto->location_longitude = $request['location_longitude'];
        $dto->location_latitude = $request['location_latitude'];
        $dto->profile_image = uploadImage('profile_images', $request['profile_image']);
        $dto->drive_licence_image = isset($request['drive_licence_image']) ? uploadImage('drive_licence_images' ,$request['drive_licence_image']) : null;

        return $dto;
    }
}

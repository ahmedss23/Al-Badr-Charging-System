<?php

use Illuminate\Support\Facades\Storage;

function uploadImage($fs, $image, $folder = null): string
{
    return $image->store($folder, $fs);
}

function deleteImage($fs,$imageName): void
{
    Storage::Disk($fs)->delete($imageName);
}

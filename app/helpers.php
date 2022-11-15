<?php

use App\Constants;

if (! function_exists('getImageOrDefault')) {
    function getImageOrDefault($imageStoragePath) {
        return $imageStoragePath !== Constants::DEFAULT_IMAGE &&
            $imageStoragePath !== Constants::DEFAULT_IMAGE2
            ? Storage::url($imageStoragePath)
            : asset(Constants::DEFAULT_IMAGE);
    }
}

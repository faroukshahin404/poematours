<?php

namespace App\Enums;

/**
 * Canonical folders under the public disk (storage/app/public/{value}).
 * URLs are served as /storage/{value}/...
 */
enum StoragePath: string
{
    case Destinations = 'Destinations';

    case PackageCategories = 'PackageCategories';

    case Hotels = 'Hotels';

    case HotelRooms = 'HotelRooms';

    case Boats = 'Boats';

    case Packages = 'Packages';

    case Activities = 'Activities';

    case Reels = 'Reels';

    case Blogs = 'Blogs';

    /**
     * Directory name under storage/app/public/ (and URL segment after /storage/).
     */
    public function folder(): string
    {
        return $this->value;
    }

    /**
     * Public URL prefix, e.g. /storage/Destinations
     */
    public function publicStoragePrefix(): string
    {
        return '/storage/'.$this->value;
    }
}

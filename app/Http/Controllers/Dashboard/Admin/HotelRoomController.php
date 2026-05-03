<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\HotelRoomRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreHotelRoomRequest;
use App\Http\Requests\Dashboard\Admin\UpdateHotelRoomRequest;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HotelRoomController extends Controller
{
    public function __construct(
        private readonly HotelRoomRepositoryInterface $hotelRooms
    ) {}

    public function index(Hotel $hotel): Response
    {
        return Inertia::render('Dashboard/Admin/HotelRooms/Index', [
            'hotel' => [
                'id' => $hotel->id,
                'name' => $hotel->labelForDefaultLanguage(),
                'slug' => $hotel->slug,
            ],
            'rooms' => $this->hotelRooms->paginateByHotel($hotel, 15)->through(function (HotelRoom $room) {
                return [
                    'id' => $room->id,
                    'name' => $room->labelForDefaultLanguage(),
                    'slug' => $room->slug,
                    'capacity' => $room->capacity,
                    'area' => $room->area,
                    'base_price' => $room->base_price,
                    'single_supplement' => $room->single_supplement,
                    'images' => $room->media->map(fn ($m) => ['id' => $m->id, 'url' => $m->publicUrl()])->values(),
                    'creator' => $room->creator?->name,
                    'updater' => $room->updater?->name,
                ];
            }),
        ]);
    }

    public function create(Hotel $hotel): Response
    {
        return Inertia::render('Dashboard/Admin/HotelRooms/Create', [
            'hotel' => [
                'id' => $hotel->id,
                'name' => $hotel->labelForDefaultLanguage(),
                'slug' => $hotel->slug,
            ],
        ]);
    }

    public function store(StoreHotelRoomRequest $request, Hotel $hotel): RedirectResponse
    {
        $data = $request->roomPayload();
        $data['hotel_id'] = $hotel->id;

        $this->hotelRooms->create(
            $data,
            $request->file('images', []),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.hotels.rooms.index', $hotel)
            ->with('status', __('Hotel room created successfully.'));
    }

    public function edit(Hotel $hotel, HotelRoom $room): Response
    {
        abort_unless($room->hotel_id === $hotel->id, 404);
        $room->load(['media:id,path,model_type,model_id']);

        return Inertia::render('Dashboard/Admin/HotelRooms/Edit', [
            'hotel' => [
                'id' => $hotel->id,
                'name' => $hotel->labelForDefaultLanguage(),
                'slug' => $hotel->slug,
            ],
            'room' => [
                'id' => $room->id,
                'hotel_id' => $room->hotel_id,
                'slug' => $room->slug,
                'name_translations' => $room->nameTranslations(),
                'capacity' => $room->capacity,
                'area' => $room->area,
                'base_price' => $room->base_price,
                'single_supplement' => $room->single_supplement,
                'images' => $room->media->map(fn ($m) => ['id' => $m->id, 'url' => $m->publicUrl()])->values(),
            ],
        ]);
    }

    public function update(UpdateHotelRoomRequest $request, Hotel $hotel, HotelRoom $room): RedirectResponse
    {
        abort_unless($room->hotel_id === $hotel->id, 404);
        $data = $request->roomPayload();
        $data['hotel_id'] = $hotel->id;

        $this->hotelRooms->update(
            $room,
            $data,
            $request->file('images', []),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.hotels.rooms.index', $hotel)
            ->with('status', __('Hotel room updated successfully.'));
    }

    public function destroy(Request $request, Hotel $hotel, HotelRoom $room): RedirectResponse
    {
        abort_unless($room->hotel_id === $hotel->id, 404);
        $this->hotelRooms->delete($room, (int) $request->user()->id);

        return redirect()
            ->route('admin.hotels.rooms.index', $hotel)
            ->with('status', __('Hotel room deleted successfully.'));
    }
}

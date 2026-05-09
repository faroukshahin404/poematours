<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\ReservationQuestionRepositoryInterface;
use App\Models\ReservationQuestion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReservationQuestionRepository implements ReservationQuestionRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return ReservationQuestion::query()
            ->with(['creator:id,name', 'updater:id,name'])
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?ReservationQuestion
    {
        return ReservationQuestion::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, int $userId): ReservationQuestion
    {
        return DB::transaction(function () use ($data, $userId): ReservationQuestion {
            $question = new ReservationQuestion;
            $question->title = $data['title'];
            $question->description = $data['description'] ?: null;
            $question->type = $data['type'];
            $question->is_package_reservation = $data['is_package_reservation'];
            $question->is_reservation_page = $data['is_reservation_page'];
            $question->options = $data['options'] ?: null;
            $question->created_by = $userId;
            $question->save();

            return $question->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(ReservationQuestion $question, array $data, int $userId): ReservationQuestion
    {
        return DB::transaction(function () use ($question, $data, $userId): ReservationQuestion {
            $question->title = $data['title'];
            $question->description = $data['description'] ?: null;
            $question->type = $data['type'];
            $question->is_package_reservation = $data['is_package_reservation'];
            $question->is_reservation_page = $data['is_reservation_page'];
            $question->options = $data['options'] ?: null;
            $question->updated_by = $userId;
            $question->save();

            return $question->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ReservationQuestion $question): void
    {
        DB::transaction(function () use ($question): void {
            $question->delete();
        });
    }
}

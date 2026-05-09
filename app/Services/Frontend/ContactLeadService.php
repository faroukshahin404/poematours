<?php

namespace App\Services\Frontend;

use App\Models\Country;
use App\Models\CrmContact;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ContactLeadService
{
    public function __construct(
        private readonly ContactLeadNotificationService $contactLeadNotificationService
    ) {}

    /**
     * @param  array<string, string|null>  $payload
     */
    public function createWebsiteLead(array $payload): CrmContact
    {
        $contact = $this->createContact([
            'name' => trim(($payload['first_name'] ?? '').' '.($payload['last_name'] ?? '')),
            'phone' => (string) ($payload['phone_number'] ?? ''),
            'email' => $payload['email'] ?? null,
            'notes' => $payload['notes'] ?? null,
            'country_prefix' => $payload['country_prefix'] ?? null,
            'source' => CrmContact::SOURCE_WEBSITE,
        ]);

        $this->contactLeadNotificationService->notifyCreated($contact);

        return $contact;
    }

    /**
     * @param  array<string, string>  $payload
     */
    public function createNewsletterLead(array $payload): CrmContact
    {
        return $this->createContact([
            'name' => trim($payload['first_name'].' '.$payload['last_name']),
            'phone' => 'N/A',
            'email' => $payload['email'],
            'notes' => 'Newsletter subscription',
            'country_prefix' => null,
            'source' => CrmContact::SOURCE_NEWSLETTER,
        ]);
    }

    /**
     * @param  array{name: string, phone: string, email?: string|null, notes?: string|null, country_prefix?: string|null, source: string}  $payload
     */
    private function createContact(array $payload): CrmContact
    {
        $name = trim((string) $payload['name']);
        $systemUserId = $this->resolveSystemUserId();

        return DB::transaction(function () use ($payload, $name, $systemUserId) {
            $contact = new CrmContact;
            $contact->name = $name !== '' ? $name : 'Website Lead';
            $contact->phone = (string) $payload['phone'];
            $contact->email = $payload['email'] ?? null;
            $contact->country_id = $this->resolveCountryId($payload['country_prefix'] ?? null);
            $contact->status = CrmContact::STATUS_PENDING;
            $contact->source = $payload['source'];
            $contact->notes = $payload['notes'] ?? null;
            $contact->created_by = $systemUserId;
            $contact->updated_by = $systemUserId;
            $contact->save();

            return $contact;
        });
    }

    private function resolveCountryId(?string $countryPrefix): int
    {
        $slug = $countryPrefix === '+20' ? 'egypt' : 'usa';

        $country = Country::query()->where('slug', $slug)->first()
            ?? Country::query()->orderBy('id')->first();

        if ($country === null) {
            throw new RuntimeException('No countries configured. Please add countries in admin settings.');
        }

        return (int) $country->id;
    }

    private function resolveSystemUserId(): int
    {
        $user = User::query()
            ->where('is_admin', true)
            ->orderBy('id')
            ->first()
            ?? User::query()->orderBy('id')->first();

        if ($user === null) {
            throw new RuntimeException('No users found to attach CRM contact ownership.');
        }

        return (int) $user->id;
    }
}

<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    reservation: {
        type: Object,
        required: true,
    },
    bookingStatuses: {
        type: Array,
        default: () => [],
    },
    paymentStatuses: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    booking_status: props.reservation.booking_status,
    payment_status: props.reservation.payment_status,
    paid_amount: props.reservation.paid_amount ?? 0,
    total_amount: props.reservation.total_amount,
});

function labelize(value) {
    return String(value || '')
        .replaceAll('_', ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());
}

function formatAmount(amount, currency = 'USD') {
    const numericAmount = Number(amount ?? 0);

    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: String(currency || 'USD').toUpperCase(),
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number.isFinite(numericAmount) ? numericAmount : 0);
}

function submitStatus() {
    form
        .transform((data) => ({ ...data, _method: 'put' }))
        .post(`/admin/reservations/${props.reservation.id}/status`, { preserveScroll: true });
}
</script>

<template>
    <div>
        <Head :title="`Reservation #${reservation.id}`" />

        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <Link href="/admin/reservations" class="text-sm font-medium text-sky-700 hover:text-sky-900">← Reservations</Link>
                <h1 class="mt-1 text-xl font-semibold text-slate-900">Reservation #{{ reservation.id }}</h1>
            </div>
            <a
                :href="`/admin/reservations/${reservation.id}/receipt?print=1`"
                target="_blank"
                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
                Print receipt
            </a>
        </div>

        <div class="mb-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Status & Payment</h2>
            <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submitStatus">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Booking status</label>
                    <select v-model="form.booking_status" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                        <option v-for="status in bookingStatuses" :key="status" :value="status">{{ labelize(status) }}</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Payment status</label>
                    <select v-model="form.payment_status" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                        <option v-for="status in paymentStatuses" :key="status" :value="status">{{ labelize(status) }}</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Paid amount</label>
                    <input v-model.number="form.paid_amount" type="number" step="0.01" min="0" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Total amount</label>
                    <input v-model.number="form.total_amount" type="number" step="0.01" min="0" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
                        Update status
                    </button>
                </div>
            </form>

            <div class="mt-6 border-t border-slate-200 pt-4">
                <h3 class="mb-3 text-sm font-semibold text-slate-700">Payment transaction details</h3>
                <div v-if="reservation.payment_transaction" class="grid gap-2 text-sm text-slate-700 md:grid-cols-2">
                    <p><strong>Payment key:</strong> {{ reservation.payment_transaction.payment_key || '—' }}</p>
                    <p><strong>Stripe session ID:</strong> {{ reservation.payment_transaction.stripe_session_id || '—' }}</p>
                    <p><strong>Transaction status:</strong> {{ labelize(reservation.payment_transaction.status || '—') }}</p>
                    <p><strong>Session status:</strong> {{ labelize(reservation.payment_transaction.session_status || '—') }}</p>
                    <p><strong>Gateway payment status:</strong> {{ labelize(reservation.payment_transaction.payment_status || '—') }}</p>
                    <p><strong>Last webhook event:</strong> {{ reservation.payment_transaction.event_type || '—' }}</p>
                    <p>
                        <strong>Charge amount:</strong>
                        {{ formatAmount(reservation.payment_transaction.charge_amount, reservation.payment_transaction.currency) }}
                    </p>
                    <p><strong>Transaction created at:</strong> {{ reservation.payment_transaction.created_at || '—' }}</p>
                    <p v-if="reservation.payment_transaction.payment_link" class="md:col-span-2">
                        <strong>Payment link:</strong>
                        <a :href="reservation.payment_transaction.payment_link" target="_blank" class="text-sky-700 hover:text-sky-900">
                            Open payment page
                        </a>
                    </p>
                </div>
                <p v-else class="text-sm text-slate-500">No payment transaction is linked to this reservation yet.</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Contact & Address</h2>
                <div class="space-y-2 text-sm">
                    <p><strong>Name:</strong> {{ reservation.first_name }} {{ reservation.last_name }}</p>
                    <p><strong>Email:</strong> {{ reservation.email || '—' }}</p>
                    <p><strong>Phone:</strong> {{ reservation.contact_phone_number || '—' }}</p>
                    <p><strong>Itinerary cover name:</strong> {{ reservation.itinerary_cover_name || '—' }}</p>
                    <p><strong>Traveler emails:</strong> {{ reservation.traveler_emails?.join(', ') || '—' }}</p>
                    <p>
                        <strong>Address:</strong>
                        {{ [reservation.mailing_street, reservation.mailing_street_line_2, reservation.mailing_city, reservation.mailing_state, reservation.mailing_zip_code, reservation.mailing_country].filter(Boolean).join(', ') || '—' }}
                    </p>
                    <p><strong>Mobility concerns:</strong> {{ reservation.mobility_concerns?.join(', ') || '—' }}</p>
                    <p><strong>Dietary restrictions:</strong> {{ reservation.dietary_restrictions || '—' }}</p>
                    <p><strong>Other needs:</strong> {{ reservation.other_needs_or_requests || '—' }}</p>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Flight</h2>
                <div class="space-y-2 text-sm">
                    <p><strong>Option:</strong> {{ labelize(reservation.flight_option || '—') }}</p>
                    <p><strong>Arrival:</strong> {{ reservation.arrival_flight_date || '—' }} {{ reservation.arrival_flight_time || '' }}</p>
                    <p><strong>Arrival airline/number:</strong> {{ reservation.arrival_flight_airline || '—' }} / {{ reservation.arrival_flight_number || '—' }}</p>
                    <p><strong>Return:</strong> {{ reservation.return_flight_date || '—' }} {{ reservation.return_flight_departure_time || '' }}</p>
                    <p><strong>Return airline/number:</strong> {{ reservation.return_flight_airline || '—' }} / {{ reservation.return_flight_number || '—' }}</p>
                    <p><strong>Other flight notes:</strong> {{ reservation.flight_other_text || '—' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Dynamic Answers</h2>
            <div v-if="Object.keys(reservation.dynamic_answers || {}).length" class="space-y-2 text-sm">
                <div v-for="(value, key) in reservation.dynamic_answers" :key="key">
                    <strong>{{ key }}:</strong>
                    <span>{{ Array.isArray(value) ? value.join(', ') : value }}</span>
                </div>
            </div>
            <p v-else class="text-sm text-slate-500">No dynamic answers submitted.</p>
        </div>

        <div class="mt-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Travellers</h2>
            <div v-if="reservation.travellers?.length" class="space-y-4">
                <div v-for="traveller in reservation.travellers" :key="traveller.id" class="rounded-lg border border-slate-200 p-4 text-sm">
                    <p class="font-semibold text-slate-900">Traveller {{ traveller.sort_order }}</p>
                    <p>Name: {{ [traveller.first_name_on_passport, traveller.middle_name_on_passport, traveller.last_name_on_passport].filter(Boolean).join(' ') || '—' }}</p>
                    <p>Gender: {{ traveller.gender || '—' }}</p>
                    <p>Birthdate: {{ traveller.birthdate || '—' }}</p>
                    <p>Passport Country/No: {{ traveller.passport_country || '—' }} / {{ traveller.passport_number || '—' }}</p>
                    <p>Passport Expiration: {{ traveller.passport_expiration_date || '—' }}</p>
                    <p>Country of Birth: {{ traveller.country_of_birth || '—' }}</p>
                    <p>Father's First Name: {{ traveller.father_first_name || '—' }}</p>
                    <p v-if="traveller.passport_photo_url">
                        Passport Photo:
                        <a :href="traveller.passport_photo_url" target="_blank" class="text-sky-700 hover:text-sky-900">Open file</a>
                    </p>
                </div>
            </div>
            <p v-else class="text-sm text-slate-500">No traveller rows submitted.</p>
        </div>
    </div>
</template>

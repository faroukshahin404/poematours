<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Receipt #{{ $reservation->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 32px; color: #1f2937; }
        .receipt { max-width: 900px; margin: 0 auto; border: 1px solid #e5e7eb; padding: 24px; border-radius: 10px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 24px; }
        h1 { margin: 0; font-size: 24px; }
        h2 { margin: 24px 0 10px; font-size: 14px; text-transform: uppercase; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; font-size: 13px; vertical-align: top; text-align: left; }
        .meta p, .block p { margin: 4px 0; font-size: 13px; }
        .totals { margin-top: 16px; }
        .totals p { margin: 4px 0; font-weight: 600; }
        .actions { margin-top: 20px; }
        @media print { .actions { display: none; } body { padding: 0; } .receipt { border: 0; } }
    </style>
</head>
<body @if($print) onload="window.print()" @endif>
    <div class="receipt">
        <div class="header">
            <div>
                <h1>Reservation Receipt</h1>
                <p>#{{ $reservation->id }}</p>
            </div>
            <div class="meta">
                <p><strong>Date:</strong> {{ $reservation->created_at?->format('Y-m-d H:i') }}</p>
                <p><strong>Status:</strong> {{ ucwords(str_replace('_', ' ', (string) $reservation->booking_status)) }}</p>
                <p><strong>Payment:</strong> {{ ucwords(str_replace('_', ' ', (string) $reservation->payment_status)) }}</p>
            </div>
        </div>

        <h2>Guest</h2>
        <div class="block">
            <p><strong>Name:</strong> {{ $reservation->first_name }} {{ $reservation->last_name }}</p>
            <p><strong>Email:</strong> {{ $reservation->email ?: '—' }}</p>
            <p><strong>Phone:</strong> {{ $reservation->contact_phone_number ?: '—' }}</p>
        </div>

        <h2>Address</h2>
        <div class="block">
            <p>
                {{ collect([
                    $reservation->mailing_street,
                    $reservation->mailing_street_line_2,
                    $reservation->mailing_city,
                    $reservation->mailing_state,
                    $reservation->mailing_zip_code,
                    $reservation->mailing_country,
                ])->filter()->implode(', ') ?: '—' }}
            </p>
        </div>

        <h2>Travellers</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name on Passport</th>
                    <th>Gender</th>
                    <th>Birthdate</th>
                    <th>Passport</th>
                    <th>Expiration</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservation->travellers()->orderBy('sort_order')->get() as $traveller)
                    <tr>
                        <td>{{ $traveller->sort_order }}</td>
                        <td>{{ trim(($traveller->first_name_on_passport ?? '').' '.($traveller->middle_name_on_passport ?? '').' '.($traveller->last_name_on_passport ?? '')) ?: '—' }}</td>
                        <td>{{ $traveller->gender ?: '—' }}</td>
                        <td>{{ $traveller->birthdate?->format('Y-m-d') ?: '—' }}</td>
                        <td>{{ ($traveller->passport_country ?: '—').' / '.($traveller->passport_number ?: '—') }}</td>
                        <td>{{ $traveller->passport_expiration_date?->format('Y-m-d') ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No traveller rows submitted.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="totals">
            <p>Paid Amount: {{ number_format((float) ($reservation->paid_amount ?? 0), 2) }}</p>
            <p>Total Amount: {{ $reservation->total_amount !== null ? number_format((float) $reservation->total_amount, 2) : '—' }}</p>
        </div>

        <div class="actions">
            <button type="button" onclick="window.print()">Print</button>
        </div>
    </div>
</body>
</html>

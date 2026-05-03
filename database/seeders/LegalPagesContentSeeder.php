<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

class LegalPagesContentSeeder extends Seeder
{
    public function run(): void
    {
        $languageSlugs = Language::query()->orderBy('id')->pluck('slug')->all();
        if ($languageSlugs === []) {
            $languageSlugs = [Language::defaultSlug()];
        }

        $privacyPage = Page::query()->where('slug', 'privacy-policy')->first();
        if ($privacyPage) {
            PageSection::query()->updateOrCreate(
                ['page_id' => $privacyPage->id, 'key' => 'legal_privacy_policy'],
                [
                    'order' => 1,
                    'is_active' => true,
                    'content' => [
                        'breadcrumb_home_label_translations' => $this->translations($languageSlugs, 'Home'),
                        'breadcrumb_current_label_translations' => $this->translations($languageSlugs, 'Privacy Policy'),
                        'title_translations' => $this->translations($languageSlugs, 'Privacy Policy'),
                        'body_translations' => $this->translations($languageSlugs, <<<TEXT
This Privacy Policy explains how Poema Tours collects, uses, and protects personal data when you visit our website, contact us, or submit travel inquiries.

1. Information We Collect
We may collect your name, email address, phone number, travel preferences, and other details you voluntarily provide through inquiry and subscription forms.

2. How We Use Information
Your information is used to respond to requests, prepare travel proposals, manage communications, and send relevant promotions where permitted by law.

3. Data Sharing
We do not sell personal information. Where necessary, we may share limited data with trusted partners (such as hotels or transport providers) solely to support your requested services.

4. Data Security
We apply appropriate technical and organizational measures to protect your data against unauthorized access, loss, or misuse.

5. Your Rights
You may request access, correction, or deletion of your personal data by contacting us. You can also opt out of marketing emails at any time using the unsubscribe link.

6. Contact
For privacy requests, contact hello@poematours.com.
TEXT),
                        'contact_email' => 'hello@poematours.com',
                    ],
                ]
            );
        }

        $termsPage = Page::query()->where('slug', 'terms-and-conditions')->first();
        if ($termsPage) {
            PageSection::query()->updateOrCreate(
                ['page_id' => $termsPage->id, 'key' => 'legal_terms_of_use'],
                [
                    'order' => 1,
                    'is_active' => true,
                    'content' => [
                        'breadcrumb_home_label_translations' => $this->translations($languageSlugs, 'Home'),
                        'breadcrumb_current_label_translations' => $this->translations($languageSlugs, 'Terms of Use'),
                        'title_translations' => $this->translations($languageSlugs, 'Terms of Use'),
                        'body_translations' => $this->translations($languageSlugs, <<<TEXT
Welcome to Poema Tours. By accessing or using our website, you agree to the terms below. These terms govern your use of our content, services, and booking-related requests.

1. Website Use
You may browse and use this website for personal, non-commercial travel planning. You agree not to misuse the site, interfere with normal operation, or attempt unauthorized access to systems or data.

2. Content and Accuracy
We aim to keep all information accurate and current, including itineraries, prices, and availability. However, details may change without prior notice and final confirmations are always provided during direct consultation.

3. Bookings and Payments
Any booking request submitted through Poema Tours is subject to confirmation. Final pricing, cancellation policies, payment schedules, and inclusions are provided in writing before payment is completed.

4. Intellectual Property
All website materials, including text, visuals, branding, and design assets, are owned by Poema Tours or used with permission. You may not copy, reproduce, or distribute this content without written approval.

5. Limitation of Liability
Poema Tours is not liable for indirect or consequential loss related to website usage, temporary service interruptions, or third-party external links.

6. Contact
For terms inquiries, contact us at hello@poematours.com.
TEXT),
                        'contact_email' => 'hello@poematours.com',
                    ],
                ]
            );
        }
    }

    /**
     * @param  array<int, string>  $languageSlugs
     * @return array<string, string>
     */
    private function translations(array $languageSlugs, string $value): array
    {
        $translations = [];
        foreach ($languageSlugs as $slug) {
            $translations[$slug] = $value;
        }

        return $translations;
    }
}

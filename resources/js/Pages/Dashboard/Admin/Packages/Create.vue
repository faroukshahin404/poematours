<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';
export default { layout: MainLayout };
</script>

<script setup>
import EditableTextArea from '@/Components/Admin/EditableTextArea.vue';
import FileInput from '@/Components/Admin/FileInput.vue';
import MultiSelectBadges from '@/Components/Admin/MultiSelectBadges.vue';
import SearchableSelect from '@/Components/Admin/SearchableSelect.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
    categories: Array,
    labels: Array,
    activities: Array,
    packageInclusions: Array,
    destinations: Array,
    hotels: Array,
    boats: Array,
    rooms: Array,
    allPackages: Array,
});

const page = usePage();
const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    title: {},
    description: {},
    details: {
        overview_title: {},
        overview_intro: {},
        overview_lead: {},
        overview_support: {},
        ship_name: {},
        ship_description: {},
        map_image: '',
        overview_highlights: [],
        essential_info: [],
        offer_cards: [],
    },
    pdf: null,
    images: [],
    options: {
        featured: false,
        recommended: false,
        is_private: false,
        is_small_group: false,
    },
    package_category_ids: [],
    package_label_ids: [],
    activity_ids: [],
    package_inclusion_ids: [],
    itineraries: [],
    extensions: [],
    date_prices: [],
});

watch(languages, (langs) => {
    const title = { ...form.title };
    const description = { ...form.description };
    const details = { ...form.details };
    const detailsLocalizedFields = [
        'overview_title',
        'overview_intro',
        'overview_lead',
        'overview_support',
        'ship_name',
        'ship_description',
    ];
    for (const l of langs) {
        if (title[l.slug] === undefined) title[l.slug] = '';
        if (description[l.slug] === undefined) description[l.slug] = '';
        detailsLocalizedFields.forEach((field) => {
            const current = { ...(details[field] ?? {}) };
            if (current[l.slug] === undefined) current[l.slug] = '';
            details[field] = current;
        });
    }
    form.title = title;
    form.description = description;
    form.details = details;
    
}, { immediate: true });

const labelsOptions = computed(() => props.labels ?? []);
const featuredLabelId = computed(() => (props.labels ?? []).find((x) => x.slug === 'featured')?.id ?? null);
const recommendedLabelId = computed(
    () => (props.labels ?? []).find((x) => ['recommended', 'recommended'].includes(x.slug))?.id ?? null,
);

function addItinerary() {
    form.itineraries.push({
        title: '',
        description: '',
        meals_included: { breakfast: false, lunch: false, dinner: false, snacks: false },
        destination_id: '',
        hotel_id: '',
        boat_id: '',
    });
}
function removeItinerary(index) { form.itineraries.splice(index, 1); }
function addExtension() {
    form.extensions.push({ extension_package_id: '', type: 'pre_tour', sort_order: form.extensions.length, inclusions_text: '' });
}
function removeExtension(index) { form.extensions.splice(index, 1); }
const extensionPackageOptions = computed(() => props.allPackages ?? []);

function addDatePrice() {
    form.date_prices.push({
        from_date: '',
        to_date: '',
        available_seats: 0,
        price: '',
        offer: '',
        accommodations: [],
    });
}
function removeDatePrice(index) { form.date_prices.splice(index, 1); }
function addAccommodation(priceIndex) {
    form.date_prices[priceIndex].accommodations.push({ hotel_id: '', room_id: '' });
}
function removeAccommodation(priceIndex, accIndex) {
    form.date_prices[priceIndex].accommodations.splice(accIndex, 1);
}

function addHighlight() { form.details.overview_highlights.push(''); }
function removeHighlight(index) { form.details.overview_highlights.splice(index, 1); }
function addEssentialInfo() { form.details.essential_info.push({ question: '', answer: '' }); }
function removeEssentialInfo(index) { form.details.essential_info.splice(index, 1); }
function addOfferCard() { form.details.offer_cards.push({ title: '', description: '', link_label: 'Learn more', link_url: '' }); }
function removeOfferCard(index) { form.details.offer_cards.splice(index, 1); }

function itineraryHotels(destinationId) {
    if (!destinationId) return props.hotels ?? [];
    return (props.hotels ?? []).filter((hotel) => String(hotel.destination_id) === String(destinationId));
}

watch(
    () => form.itineraries.map((row) => [row.destination_id, row.hotel_id]),
    () => {
        form.itineraries.forEach((row) => {
            if (!row.hotel_id) return;
            const isValidHotel = itineraryHotels(row.destination_id).some(
                (hotel) => String(hotel.id) === String(row.hotel_id),
            );
            if (!isValidHotel) {
                row.hotel_id = '';
            }
        });
    },
    { deep: true },
);

function roomsByHotel(hotelId) {
    return (props.rooms ?? []).filter((r) => String(r.hotel_id) === String(hotelId));
}

function syncOptionLabels() {
    const ids = new Set(form.package_label_ids);

    if (featuredLabelId.value !== null) {
        if (form.options.featured) ids.add(featuredLabelId.value);
        else ids.delete(featuredLabelId.value);
    }

    if (recommendedLabelId.value !== null) {
        if (form.options.recommended) ids.add(recommendedLabelId.value);
        else ids.delete(recommendedLabelId.value);
    }

    form.package_label_ids = Array.from(ids);
}

function submit() {
    syncOptionLabels();
    form.transform((d) => ({
        ...d,
        featured: d.options?.featured ? 1 : 0,
        recommended: d.options?.recommended ? 1 : 0,
        is_private: d.options?.is_private ? 1 : 0,
        is_small_group: d.options?.is_small_group ? 1 : 0,
    })).post('/admin/packages', { preserveScroll: true, forceFormData: true });
}
</script>

<template>
    <div>

        <Head title="Add package" />
        <div class="mb-6">
            <Link href="/admin/packages" class="text-sm font-medium text-sky-700">← Packages</Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Add package</h1>
        </div>
        <form class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-800">Package title</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div v-for="lang in languages" :key="`title-${lang.slug}`"><label
                            class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }}</label><input
                            v-model="form.title[lang.slug]" type="text"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" /></div>
                        </div>
                </div>
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-800">Package description</h2>
                    <div v-for="lang in languages" :key="`desc-${lang.slug}`"><label
                            class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }}</label>
                        <EditableTextArea v-model="form.description[lang.slug]" />
                    </div>
                </div>
            </div>

            <div class="space-y-6 rounded-lg border border-slate-200 p-4">
                <h2 class="text-sm font-semibold text-slate-800">Package details page content</h2>

                <div class="space-y-4">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Overview content</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div v-for="lang in languages" :key="`ov-title-${lang.slug}`">
                            <label class="mb-1 block text-sm font-medium text-slate-700">Overview title ({{ lang.slug }})</label>
                            <input v-model="form.details.overview_title[lang.slug]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        </div>
                    </div>
                    <div v-for="lang in languages" :key="`ov-intro-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Overview intro ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.details.overview_intro[lang.slug]" />
                    </div>
                    <div v-for="lang in languages" :key="`ov-lead-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Overview lead ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.details.overview_lead[lang.slug]" />
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Overview highlights</h3>
                        <button type="button" class="rounded border px-2 py-1 text-xs" @click="addHighlight">Add</button>
                    </div>
                    <div v-for="(highlight, index) in form.details.overview_highlights" :key="`hl-${index}`" class="flex gap-2">
                        <input v-model="form.details.overview_highlights[index]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <button type="button" class="rounded border border-red-200 px-3 text-sm text-red-700" @click="removeHighlight(index)">Remove</button>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ship and map</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div v-for="lang in languages" :key="`ship-name-${lang.slug}`">
                            <label class="mb-1 block text-sm font-medium text-slate-700">Ship name ({{ lang.slug }})</label>
                            <input v-model="form.details.ship_name[lang.slug]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        </div>
                    </div>
                    <div v-for="lang in languages" :key="`ship-desc-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Ship description ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.details.ship_description[lang.slug]" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Route map image path (optional)</label>
                        <input v-model="form.details.map_image" type="text" placeholder="assets/images/placeholders/map.avif" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Essential info</h3>
                        <button type="button" class="rounded border px-2 py-1 text-xs" @click="addEssentialInfo">Add</button>
                    </div>
                    <div v-for="(item, index) in form.details.essential_info" :key="`essential-${index}`" class="space-y-2 rounded border border-slate-200 p-3">
                        <input v-model="item.question" type="text" placeholder="Question" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <textarea v-model="item.answer" rows="3" placeholder="Answer" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <button type="button" class="rounded border border-red-200 px-3 py-1 text-xs text-red-700" @click="removeEssentialInfo(index)">Remove</button>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Offer cards</h3>
                        <button type="button" class="rounded border px-2 py-1 text-xs" @click="addOfferCard">Add</button>
                    </div>
                    <div v-for="(offer, index) in form.details.offer_cards" :key="`offer-${index}`" class="space-y-2 rounded border border-slate-200 p-3">
                        <input v-model="offer.title" type="text" placeholder="Title" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <textarea v-model="offer.description" rows="2" placeholder="Description" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                            <input v-model="offer.link_label" type="text" placeholder="Link label" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                            <input v-model="offer.link_url" type="text" placeholder="Link URL (optional)" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        </div>
                        <button type="button" class="rounded border border-red-200 px-3 py-1 text-xs text-red-700" @click="removeOfferCard(index)">Remove</button>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Included services</h3>
                    <MultiSelectBadges
                        v-model="form.package_inclusion_ids"
                        label="Select services included in this package"
                        :options="packageInclusions ?? []"
                        placeholder="Search included services..."
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <FileInput label="Package PDF (optional)" accept=".pdf,application/pdf"
                    @update:model-value="form.pdf = $event" />
                <FileInput label="Package gallery" multiple @update:model-value="form.images = $event" />
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <MultiSelectBadges v-model="form.package_category_ids" label="Categories" :options="categories"
                    placeholder="Search categories..." />
                <MultiSelectBadges v-model="form.package_label_ids" label="Labels" :options="labelsOptions"
                    placeholder="Search labels..." />
                <MultiSelectBadges v-model="form.activity_ids" label="Activities" :options="activities"
                    placeholder="Search activities..." />
            </div>
            <div class="space-y-3 rounded-lg border border-slate-200 p-4">
                <h2 class="text-sm font-semibold text-slate-800">Package options</h2>
                <div class="flex flex-wrap gap-6 text-sm text-slate-700">
                    <label class="inline-flex items-center gap-2">
                        <input v-model="form.options.featured" type="checkbox" />
                        Featured
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input v-model="form.options.recommended" type="checkbox" />
                        Recommended
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input v-model="form.options.is_private" type="checkbox" />
                        Private
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input v-model="form.options.is_small_group" type="checkbox" />
                        Small group
                    </label>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-800">Extension packages</h2>
                    <button type="button" class="rounded border px-3 py-1 text-xs" @click="addExtension">Add extension</button>
                </div>
                <p class="text-xs text-slate-500">Link optional pre- or post-tour packages. Each extension must be a separate package with its own itinerary.</p>
                <div v-for="(ext, extIndex) in form.extensions" :key="`ext-${extIndex}`" class="rounded-lg border border-slate-200 p-4">
                    <div class="mb-3 flex justify-between">
                        <strong class="text-sm">Extension {{ extIndex + 1 }}</strong>
                        <button type="button" class="text-xs text-red-600" @click="removeExtension(extIndex)">Remove</button>
                    </div>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <SearchableSelect
                            v-model="ext.extension_package_id"
                            :options="extensionPackageOptions"
                            placeholder="Search extension package..."
                            empty-text="No packages found."
                        />
                        <select v-model="ext.type" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="pre_tour">Pre-tour extension</option>
                            <option value="post_tour">Post-tour extension</option>
                        </select>
                        <input v-model.number="ext.sort_order" type="number" min="0" placeholder="Sort order" class="rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <input v-model="ext.inclusions_text" type="text" placeholder="Inclusions note (e.g. Internal Air Included...)" class="rounded-lg border border-slate-200 px-3 py-2 text-sm md:col-span-2" />
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-800">Itinerary</h2><button type="button"
                        class="rounded border px-3 py-1 text-xs" @click="addItinerary">Add row</button>
                </div>
                <div v-for="(row, index) in form.itineraries" :key="`it-${index}`"
                    class="rounded-lg border border-slate-200 p-4">
                    <div class="mb-3 flex justify-between"><strong class="text-sm">Day {{ index + 1 }}</strong><button
                            type="button" class="text-xs text-red-600" @click="removeItinerary(index)">Remove</button>
                    </div>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <input v-model="row.title" placeholder="Title"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <select v-model="row.destination_id"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Destination</option>
                            <option v-for="d in destinations" :key="d.id" :value="d.id">{{ d.label }}</option>
                        </select>
                        <SearchableSelect v-model="row.hotel_id" :options="itineraryHotels(row.destination_id)"
                            placeholder="Search hotel (optional)..." empty-text="No hotels found for this destination." />
                        <SearchableSelect v-model="row.boat_id" :options="boats"
                            placeholder="Search boat (optional)..." empty-text="No boats found." />
                    </div>
                    <textarea v-model="row.description"
                        class="mt-3 block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" rows="3"
                        placeholder="Description" />
                    <div class="mt-3 flex flex-wrap gap-4 text-sm">
                        <label><input v-model="row.meals_included.breakfast" type="checkbox" /> Breakfast</label>
                        <label><input v-model="row.meals_included.lunch" type="checkbox" /> Lunch</label>
                        <label><input v-model="row.meals_included.dinner" type="checkbox" /> Dinner</label>
                        <label><input v-model="row.meals_included.snacks" type="checkbox" /> Snacks</label>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-800">Dates and prices</h2><button type="button"
                        class="rounded border px-3 py-1 text-xs" @click="addDatePrice">Add row</button>
                </div>
                <div v-for="(dp, dpIndex) in form.date_prices" :key="`dp-${dpIndex}`"
                    class="rounded-lg border border-slate-200 p-4">
                    <div class="mb-3 flex justify-between"><strong class="text-sm">Date window {{ dpIndex + 1
                            }}</strong><button type="button" class="text-xs text-red-600"
                            @click="removeDatePrice(dpIndex)">Remove</button></div>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-5">
                        <input v-model="dp.from_date" type="date"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <input v-model="dp.to_date" type="date"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <input v-model="dp.available_seats" type="number" min="0" placeholder="Seats"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <input v-model="dp.price" type="number" step="0.01" min="0" placeholder="Price"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                        <input v-model="dp.offer" type="number" step="0.01" min="0" placeholder="Offer discount"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                    </div>
                    <div class="mt-3 rounded border border-slate-200 p-3">
                        <div class="mb-2 flex items-center justify-between"><span
                                class="text-xs font-semibold uppercase text-slate-500">Accommodations</span><button
                                type="button" class="rounded border px-2 py-1 text-xs"
                                @click="addAccommodation(dpIndex)">Add</button></div>
                        <div v-for="(acc, accIndex) in dp.accommodations" :key="`acc-${dpIndex}-${accIndex}`"
                            class="mb-2 grid grid-cols-1 gap-2 md:grid-cols-3">
                            <select v-model="acc.hotel_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                                <option value="">Hotel</option>
                                <option v-for="h in hotels" :key="h.id" :value="h.id">{{ h.label }}</option>
                            </select>
                            <select v-model="acc.room_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                                <option value="">Room</option>
                                <option v-for="r in roomsByHotel(acc.hotel_id)" :key="r.id" :value="r.id">{{ r.label }}
                                </option>
                            </select>
                            <button type="button" class="rounded border border-red-200 px-3 py-2 text-sm text-red-700"
                                @click="removeAccommodation(dpIndex, accIndex)">Remove</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Save
                    package</button>
                <Link href="/admin/packages"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700">
                    Cancel</Link>
            </div>
        </form>
    </div>
</template>

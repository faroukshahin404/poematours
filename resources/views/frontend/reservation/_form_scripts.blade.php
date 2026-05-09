<script>
    (function () {
        const travellerCountInput = document.getElementById('travellers_count');
        const travellersContainer = document.getElementById('travellers-container');
        const oldTravellers = @json(old('travellers', []));

        function travellerField(name, index, label, type = 'text', value = '') {
            return `
                <div class="customize-field">
                    <label>${label}</label>
                    <input type="${type}" name="travellers[${index}][${name}]" value="${value || ''}">
                </div>
            `;
        }

        function renderTravellers() {
            const count = Number(travellerCountInput?.value || 0);
            if (!travellersContainer) {
                return;
            }
            travellersContainer.innerHTML = '';

            for (let i = 0; i < count; i += 1) {
                const row = oldTravellers[i] || {};
                const block = document.createElement('div');
                block.className = 'customize-card';
                block.style.marginBottom = '16px';
                block.innerHTML = `
                    <h3>Traveler ${i + 1}</h3>
                    <input type="hidden" name="travellers[${i}][sort_order]" value="${i + 1}">
                    <div class="customize-inline">
                        ${travellerField('first_name_on_passport', i, 'First Name on Passport', 'text', row.first_name_on_passport)}
                        ${travellerField('middle_name_on_passport', i, 'Middle Name on Passport', 'text', row.middle_name_on_passport)}
                    </div>
                    <div class="customize-inline">
                        ${travellerField('last_name_on_passport', i, 'Last Name on Passport', 'text', row.last_name_on_passport)}
                        <div class="customize-field">
                            <label>Gender</label>
                            <select name="travellers[${i}][gender]">
                                <option value="">Select</option>
                                <option value="male" ${row.gender === 'male' ? 'selected' : ''}>Male</option>
                                <option value="female" ${row.gender === 'female' ? 'selected' : ''}>Female</option>
                                <option value="other" ${row.gender === 'other' ? 'selected' : ''}>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="customize-inline">
                        ${travellerField('birthdate', i, 'Birthdate (MM/DD/YYYY)', 'date', row.birthdate)}
                        ${travellerField('passport_country', i, 'Passport Country', 'text', row.passport_country)}
                    </div>
                    <div class="customize-inline">
                        ${travellerField('passport_number', i, 'Passport Number', 'text', row.passport_number)}
                        ${travellerField('passport_expiration_date', i, 'Expiration Date (MM/DD/YYYY)', 'date', row.passport_expiration_date)}
                    </div>
                    <div class="customize-inline">
                        ${travellerField('country_of_birth', i, 'Country of Birth', 'text', row.country_of_birth)}
                        ${travellerField('father_first_name', i, "Father's First Name", 'text', row.father_first_name)}
                    </div>
                    <div class="customize-field">
                        <label>Passport Photo</label>
                        <input type="file" name="travellers[${i}][passport_photo]" accept="image/*">
                    </div>
                `;
                travellersContainer.appendChild(block);
            }
        }

        function toggleFlightFields() {
            const option = document.querySelector('input[name="flight_option"]:checked')?.value || '';
            const enterNowFields = document.getElementById('flight-enter-now-fields');
            const otherFields = document.getElementById('flight-other-fields');
            if (enterNowFields) {
                enterNowFields.style.display = option === 'enter_now' ? 'block' : 'none';
            }
            if (otherFields) {
                otherFields.style.display = option === 'other' ? 'block' : 'none';
            }
        }

        function addTravelerEmailRow(value = '') {
            const wrapper = document.getElementById('traveler-email-list');
            if (!wrapper) {
                return;
            }
            const row = document.createElement('div');
            row.className = 'customize-inline traveler-email-row';
            row.innerHTML = `
                <div class="customize-field" style="flex:1;">
                    <input type="email" name="traveler_emails[]" value="${value}">
                </div>
                <button type="button" class="traveler-email-remove" aria-label="Remove email">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            `;
            wrapper.appendChild(row);
        }

        travellerCountInput?.addEventListener('input', renderTravellers);
        document.querySelectorAll('input[name="flight_option"]').forEach((el) => {
            el.addEventListener('change', toggleFlightFields);
        });

        document.getElementById('add-traveler-email')?.addEventListener('click', function () {
            addTravelerEmailRow('');
        });

        document.addEventListener('click', function (event) {
            const removeButton = event.target.closest('.traveler-email-remove');
            if (!removeButton) {
                return;
            }
            const row = removeButton.closest('.traveler-email-row');
            if (row) {
                row.remove();
            }
        });

        renderTravellers();
        toggleFlightFields();
    })();
</script>

<div id="datetime-modal" class="fixed inset-0 hidden bg-black/40 z-50 items-center justify-center p-3">
    <div class="relative bg-gray-200 rounded-xl p-6 w-full max-w-2xl max-h-[90vh] shadow">
        <!-- Header -->
        <div class="flex flex-row mb-4">
            <span class="text-2xl mr-auto font-bold">Select Date & Time</span>
            <button id="closeDateTimeModal"
                    class="w-max h-min text-sm border px-5 py-2 border-black rounded hover:bg-gray-100 transition">
                Back
            </button>
        </div>

        <!-- Date Picker -->
        <div class="mb-5">
            <div class="bg-white rounded-xl p-5 shadow-lg mt-5">
                <label for="selected-date" class="block mb-2 font-semibold text-gray-800">Due Date <span class="text-red-500 ml-0.5">*</span></label>
                <input id="selected-date" type="text" readonly placeholder="Select a date"
                       class="w-full p-3 border-2 border-gray-200 rounded-lg text-base cursor-pointer bg-white focus:outline-none focus:border-blue-500"
                       onclick="openCalendarModal()" />
            </div>
        </div>

        <!-- Time Picker (component reused) -->
        <x-time />

        <!-- Submit -->
        <div class="flex justify-center">
            <button type="button" id="setDueDateBtn"
                    class="w-max h-min text-sm border mt-4 px-10 py-2 border-black rounded hover:bg-gray-100 transition">
                Set Due Date
            </button>
        </div>
    </div>
</div>

<!-- Calendar Modal (component reused) -->
<div id="calendar-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div id="calendar-container" class="flex items-center justify-center">
        <x-calendar :month="$month" :year="$year" />
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('datetime-modal');
        const openBtn = document.getElementById('selectDueDateBtn'); // your trigger button
        const closeBtn = document.getElementById('closeDateTimeModal');
        const setDueDateBtn = document.getElementById('setDueDateBtn');

        // Open modal
        if (openBtn) {
            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        }

        // Close modal
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        }

        // Save selected datetime
        setDueDateBtn.addEventListener('click', () => {
            const dateText = document.getElementById('selected-date').value;
            const originalDate = document.getElementById('selected-date').getAttribute('data-original-date');
            const hour = document.getElementById('hourValue').value.padStart(2, '0');
            const minute = document.getElementById('minuteValue').value.padStart(2, '0');
            const ampm = document.getElementById('amPm').textContent;

            if (dateText && originalDate) {
                const parsedDate = new Date(originalDate);
                let hour24 = parseInt(hour, 10);
                if (ampm === 'PM' && hour24 < 12) hour24 += 12;
                if (ampm === 'AM' && hour24 === 12) hour24 = 0;

                parsedDate.setHours(hour24);
                parsedDate.setMinutes(parseInt(minute, 10));
                parsedDate.setSeconds(0);

                const mysqlDateTime = parsedDate.getFullYear() + "-" +
                    String(parsedDate.getMonth() + 1).padStart(2, '0') + "-" +
                    String(parsedDate.getDate()).padStart(2, '0') + " " +
                    String(parsedDate.getHours()).padStart(2, '0') + ":" +
                    String(parsedDate.getMinutes()).padStart(2, '0') + ":" +
                    String(parsedDate.getSeconds()).padStart(2, '0');

                document.getElementById('selectedDueDate').textContent = parsedDate.toLocaleString('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });

                // hidden input for form submit
                if (!document.getElementById('dueDateInput')) {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'dueDate';
                    hidden.id = 'dueDateInput';
                    document.forms[0].appendChild(hidden);
                }
                document.getElementById('dueDateInput').value = mysqlDateTime;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    });

    // Calendar modal helpers
    function openCalendarModal() {
        document.getElementById("calendar-modal").classList.remove("hidden");
        document.getElementById("calendar-modal").classList.add("flex");
    }

    function closeModal() {
        document.getElementById("calendar-modal").classList.add("hidden");
        document.getElementById("calendar-modal").classList.remove("flex");
    }

    function loadCalendar(month, year) {
        const container = document.getElementById('calendar-container');

        // Use Laravel's route helper to get the correct URL
        fetch(`{{ route('calendar.ajax') }}?month=${month}&year=${year}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading calendar:', error);
            alert('Failed to load calendar. Please try again.');
        });
    }

    function selectDate(date) {
        const dateObj = new Date(date);
        const formattedDate = dateObj.toLocaleDateString('en-US', {
            day: 'numeric', month: 'long', year: 'numeric'
        });

        document.getElementById("selected-date").value = formattedDate;
        document.getElementById("selected-date").setAttribute('data-original-date', date);

        const timePicker = document.getElementById("timePicker");
        if (timePicker) {
            timePicker.classList.remove("hidden");
        }

        closeModal();
    }

    // Toggle AM/PM
    document.addEventListener('DOMContentLoaded', () => {
        const amPmBtn = document.getElementById('amPm');

        if (amPmBtn) {
            amPmBtn.addEventListener('click', () => {
                let current = amPmBtn.textContent.trim().toUpperCase();
                amPmBtn.textContent = (current === 'PM') ? 'AM' : 'PM';
            });
        }
    });
</script>

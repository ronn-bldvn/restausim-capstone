<x-layouts :title="'Dashboard | RestauSim'">

    <div class="flex-1 p-5 overflow-y-auto">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <p class="font-[Barlow] font-semibold text-black text-2xl my-1">
                    Welcome back, <span>{{ Auth::user()->name }}!</span>
                </p>
                <span class="font-[Barlow] mt-1 text-sm">Here's what happening to RestauSim today.</span>
            </div>
        </div>

        <!-- Summary Card-->
        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-5 gap-4 my-8 px-4">
            <div
                class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Students take the Pre-Test</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $quizTotal }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Mean Score</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ number_format($meanScore, 2) }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Meadian Score</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $medianScore }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Mode Score</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $modeScore }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Standard Deviation</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $stdDeviation }}</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">
                    Students per Section
                </h2>

                <div class="w-full md:w-1/2 mx-auto">
                    <canvas id="sectionPieChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">
                    Students by Age
                </h2>

                <div class="w-full md:w-1/2 mx-auto">
                    <canvas id="agePieChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">
                    Students by Sex
                </h2>

                <div class="w-full md:w-1/2 mx-auto">
                    <canvas id="sexPieChart"></canvas>
                </div>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const sectionLabels = @json($sectionData->keys());
        const sectionValues = @json($sectionData->values());

        const ctx = document.getElementById('sectionPieChart').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: sectionLabels,
                datasets: [{
                    data: sectionValues,
                    backgroundColor: [
                        '#4F46E5',
                        '#22C55E',
                        '#F97316',
                        '#EF4444',
                        '#06B6D4',
                        '#A855F7'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        const ageLabels = @json($ageData->keys());
        const ageValues = @json($ageData->values());

        const ageCtx = document.getElementById('agePieChart').getContext('2d');

        new Chart(ageCtx, {
            type: 'pie',
            data: {
                labels: ageLabels.map(age => age + ' years old'),
                datasets: [{
                    data: ageValues,
                    backgroundColor: [
                        '#0EA5E9',
                        '#22C55E',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6',
                        '#14B8A6'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        const sexLabels = @json($sexData->keys());
        const sexValues = @json($sexData->values());

        const sexCtx = document.getElementById('sexPieChart').getContext('2d');

        new Chart(sexCtx, {
            type: 'pie',
            data: {
                labels: sexLabels.map(sex => sex),
                datasets: [{
                    data: sexValues,
                    backgroundColor: [
                        '#0EA5E9',
                        '#22C55E',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6',
                        '#14B8A6'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

</x-layouts>

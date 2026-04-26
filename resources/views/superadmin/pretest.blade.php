<x-layouts>
    <div class="max-w-7xl mx-auto px-4 py-6">

        <!-- Filters -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

                <select id="age"
                    class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                    <option value="">All Ages</option>
                    @foreach ($ages as $age)
                        <option value="{{ $age }}">{{ $age }}</option>
                    @endforeach
                </select>

                <select id="sex"
                    class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                    <option value="">All Sex</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <select id="section"
                    class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                    <option value="">All Sections</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section }}">{{ $section }}</option>
                    @endforeach
                </select>

            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border-collapse">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border px-4 py-3 text-center">ID</th>
                            <th class="border px-4 py-3 text-center">Age</th>
                            <th class="border px-4 py-3 text-center">Sex</th>
                            <th class="border px-4 py-3 text-center">Section</th>
                            <th class="border px-4 py-3 text-center">Score</th>
                            <th class="border px-4 py-3 text-center">Completed At</th>
                        </tr>
                    </thead>

                    <tbody id="table-body">
                        @forelse ($preTest as $quiz)
                            <tr class="hover:bg-gray-50 text-center">
                                <td class="border px-4 py-2">{{ $quiz->id }}</td>
                                <td class="border px-4 py-2">{{ $quiz->age }}</td>
                                <td class="border px-4 py-2">{{ $quiz->sex }}</td>
                                <td class="border px-4 py-2">{{ $quiz->section }}</td>
                                <td class="border px-4 py-2 font-semibold">{{ $quiz->score }}</td>
                                <td class="border px-4 py-2">
                                    {{ \Carbon\Carbon::parse($quiz->completed_at)->format('M d \\a\\t h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-gray-500 text-center">
                                    No records found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t" id="pagination-links">
                {{ $preTest->links() }}
            </div>
        </div>

    </div>
</x-layouts>


<script>
    const filters = ['age', 'sex', 'section'];

    filters.forEach(id => {
        document.getElementById(id).addEventListener('change', () => fetchData());
    });

    function fetchData(page = 1) {
        const age = document.getElementById('age').value;
        const sex = document.getElementById('sex').value;
        const section = document.getElementById('section').value;

        const params = new URLSearchParams();

        // Only add non-empty values
        if (age) params.append('age', age);
        if (sex) params.append('sex', sex);
        if (section) params.append('section', section);
        params.append('page', page);

        fetch(`{{ route('pretest.filter') }}?${params}`)
            .then(res => res.json())
            .then(data => {
                const parser = new DOMParser();
                const htmlDoc = parser.parseFromString(data.table, 'text/html');

                const newTableBody = htmlDoc.querySelector('#table-body');
                const newPagination = htmlDoc.querySelector('#pagination-links');

                if (newTableBody) {
                    document.getElementById('table-body').innerHTML = newTableBody.innerHTML;
                }

                if (newPagination) {
                    document.getElementById('pagination-links').innerHTML = newPagination.innerHTML;
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Pagination AJAX
    document.addEventListener('click', function (e) {
        const paginationLink = e.target.closest('.pagination a');
        if (paginationLink) {
            e.preventDefault();
            const url = new URL(paginationLink.href);
            fetchData(url.searchParams.get('page'));
        }
    });
</script>

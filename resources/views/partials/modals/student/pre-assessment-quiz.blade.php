<!-- Data Privacy Modal (First) -->
<div id="dataPrivacyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black px-6 py-4">
            <h2 class="text-2xl font-bold">Data Privacy Notice</h2>
        </div>

        <div class="overflow-y-auto max-h-[calc(90vh-200px)] p-6">
            <div class="prose max-w-none">
                <p class="mb-4 text-sm">In accordance with the Data Privacy Act of 2012, you have the right to:</p>
                <ul class="list-disc pl-6 mb-4 space-y-2 text-xs">
                    <li>Be informed about how your personal data is collected and used</li>
                    <li>Access your personal data</li>
                </ul>
                <p class="mb-4 text-sm"><strong>Purpose of Data Collection</strong></p>
                <p class="mb-4 text-sm">The data collected in this Pre-Test will be used solely for the Capstone
                    Project. This Pre-Test is designed to measure students'
                    learning progress before and after using the RestauSim system.</p>
                <p class="mb-4 text-sm">The following data will be collected:</p>
                <ul class="list-disc pl-6 mb-4 space-y-2 text-xs">
                    <li>Student information (e.g., name and email address)</li>
                    <li>Date and time of Pre-Test completion</li>
                    <li>Pre-Test score</li>
                </ul>
                <p class="mb-4 text-sm"><strong>Data Use, Storage, and Retention</strong></p>
                <p class="mb-4 text-sm">All collected data will be accessed only by the researchers. Pre-Test results
                    will be analyzed for academic purposes only,
                    and individual results will not be shared with unauthorized parties or used outside the scope of the
                    research.</p>
                <p class="mb-4 text-sm">Collected data will be stored securely within the RestauSim system. Pre-Test
                    scores will be retained only for the period necessary to
                    complete academic evaluation and research requirements. After this period, the data will be securely
                    deleted or anonymized.</p>
                <p class="mb-4 text-sm"><strong>Consent</strong></p>
                <p class="mb-4 text-sm">By proceeding with the Pre-Test, you confirm that:</p>
                <ul class="list-disc pl-6 mb-4 space-y-2 text-xs">
                    <li>You have read and understood this Data Privacy Notice</li>
                    <li>You voluntarily consent to the collection and use of your data for the purposes stated above
                    </li>
                </ul>
                <p class="mb-4 text-sm">The RestauSim Team is committed to protecting your personal information and
                    respecting your right to data privacy.</p>
                <p class="mb-4 text-sm">For any inquiry or clarification you can email us to our email account</p>
                <p class="mb-2 text-sm">ronnel.baldovino@clsu2.edu.ph</p>
                <p class="mb-2 text-sm">franz.eda@clsu2.edu.ph</p>
                <p class="mb-4 text-sm">ejay.basinga@clsu2.edu.ph</p>
                <p class="text-sm text-gray-600">By clicking "I Agree & Continue," you consent to the collection and
                    processing of your assessment data as described above.</p>
            </div>
        </div>

        <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3">
            <button type="button" onclick="declinePrivacy()"
                class="border text-black px-6 py-2 rounded-lg font-semibold transition">
                Decline
            </button>
            <button type="button" onclick="acceptPrivacy()"
                class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black px-6 py-2 rounded-lg font-semibold transition">
                I Agree & Continue
            </button>
        </div>
    </div>
</div>

<!-- Student Information Modal (Second) -->
<div id="studentInfoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black px-6 py-4">
            <h2 class="text-2xl font-bold">Student Information</h2>
            <p class="text-sm mt-1">Please verify your information and complete required fields</p>
        </div>

        <div class="overflow-y-auto max-h-[calc(90vh-200px)] p-6">
            <form id="studentInfoForm">
                @csrf

                <!-- Name (Read-only from user table) -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        value="{{ auth()->user()->name }}"
                        readonly
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">This information is from your account</p>
                </div>

                <!-- Email (Read-only from user table) -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                        value="{{ auth()->user()->email }}"
                        readonly
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">This information is from your account</p>
                </div>

                <!-- Age (Input field) -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">
                        Age <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                        name="age"
                        id="studentAge"
                        min="1"
                        max="100"
                        required
                        placeholder="Enter your age"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                </div>

                <!-- Section (Input field) -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">
                        Section <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="section"
                        id="studentSection"
                        required
                        placeholder="Enter your section (e.g., BSIT 3-1)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Enter your current section or class</p>
                </div>

                <!-- Sex (Select field) -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">
                        Sex <span class="text-red-500">*</span>
                    </label>
                    <select name="sex"
                        id="studentSex"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                        <option value="">Select your sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
            <button type="button" onclick="goBackToPrivacy()"
                class="border text-black px-6 py-2 rounded-lg font-semibold transition">
                Back
            </button>
            <button type="button" onclick="submitStudentInfo()"
                class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black px-6 py-2 rounded-lg font-semibold transition">
                Continue to Assessment
            </button>
        </div>
    </div>
</div>

<!-- Part I Modal - PoS Operations -->
<div id="partOneModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black px-6 py-4">
            <h2 class="text-2xl font-bold">Part I: PoS Operations</h2>
            <p class="text-sm mt-1">Questions 1-10 | Complete all questions to proceed</p>
        </div>

        <div class="overflow-y-auto max-h-[calc(90vh-200px)] p-6">
            <form id="partOneForm">
                @csrf
                @php
                    $partOneQuestions = [
                        ['id' => 1, 'question' => 'What is the main purpose of a Point-of-Sale (PoS) system in a restaurant?', 'options' => ['Employee scheduling', 'Order and payment processing', 'Menu design', 'Customer feedback collection'], 'answer' => 'B'],
                        ['id' => 2, 'question' => 'Which action should be done first when a customer places an order?', 'options' => ['Print receipt', 'Process payment', 'Encode the order in the PoS system', 'Update inventory manually'], 'answer' => 'C'],
                        ['id' => 3, 'question' => 'What feature allows a cashier to remove an incorrect item before payment?', 'options' => ['Logout', 'Void or cancel item', 'Shutdown', 'Backup'], 'answer' => 'B'],
                        ['id' => 4, 'question' => 'What happens after a successful payment transaction?', 'options' => ['The system logs out', 'Inventory is updated', 'Menu is deleted', 'Order is ignored'], 'answer' => 'B'],
                        ['id' => 5, 'question' => 'Which payment method is commonly supported by PoS systems?', 'options' => ['Barter', 'Cash and digital payments', 'IOU', 'Coupons only'], 'answer' => 'B'],
                        ['id' => 6, 'question' => 'Why is order accuracy important in PoS operations?', 'options' => ['To increase waiting time', 'To avoid customer complaints and losses', 'To reduce menu options', 'To delay service'], 'answer' => 'B'],
                        ['id' => 7, 'question' => 'What should a cashier do if the PoS system shows an error during checkout?', 'options' => ['Ignore the error', 'Restart the restaurant', 'Troubleshoot or call the supervisor', 'Cancel all orders'], 'answer' => 'C'],
                        ['id' => 8, 'question' => 'What PoS feature records daily sales transactions?', 'options' => ['Inventory log', 'Sales report', 'Menu editor', 'User settings'], 'answer' => 'B'],
                        ['id' => 9, 'question' => 'What happens if an order is not recorded in the PoS system?', 'options' => ['It is still counted', 'Sales become inaccurate', 'Inventory increases', 'The system corrects it automatically'], 'answer' => 'B'],
                        ['id' => 10, 'question' => 'Which role typically uses the PoS system most frequently?', 'options' => ['Supplier', 'Manager', 'Cashier', 'Customer'], 'answer' => 'C'],
                    ];
                @endphp

                @foreach($partOneQuestions as $q)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="font-semibold mb-3">{{ $q['id'] }}. {{ $q['question'] }}</p>
                        @foreach(['A', 'B', 'C', 'D'] as $index => $letter)
                            <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-100 p-2 rounded">
                                <input type="radio" name="question_{{ $q['id'] }}" value="{{ $letter }}" required
                                    class="mr-3 w-4 h-4">
                                <span>{{ $letter }}. {{ $q['options'][$index] }}</span>
                            </label>
                        @endforeach
                    </div>
                @endforeach
            </form>
        </div>

        <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
            <span class="text-sm text-gray-600">Part 1 of 3</span>
            <button type="button" onclick="submitPartOne()"
                class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black px-6 py-2 rounded-lg font-semibold transition">
                Continue to Part II
            </button>
        </div>
    </div>
</div>

<!-- Part II Modal - Inventory Management -->
<div id="partTwoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black px-6 py-4">
            <h2 class="text-2xl font-bold">Part II: Inventory Management</h2>
            <p class="text-sm mt-1">Questions 11-20 | Complete all questions to proceed</p>
        </div>

        <div class="overflow-y-auto max-h-[calc(90vh-200px)] p-6">
            <form id="partTwoForm">
                @php
                    $partTwoQuestions = [
                        ['id' => 11, 'question' => 'What is inventory in a restaurant setting?', 'options' => ['Employee list', 'Menu prices', 'Ingredients and supplies', 'Sales records'], 'answer' => 'C'],
                        ['id' => 12, 'question' => 'Why is inventory tracking important?', 'options' => ['To increase food waste', 'To avoid stock shortages and losses', 'To slow operations', 'To confuse staff'], 'answer' => 'B'],
                        ['id' => 13, 'question' => 'What does "stock-out" mean?', 'options' => ['Overstocking items', 'Running out of ingredients', 'Receiving supplies', 'Updating prices'], 'answer' => 'B'],
                        ['id' => 14, 'question' => 'What happens when inventory reaches a minimum stock level?', 'options' => ['System shuts down', 'Alert or reorder is triggered', 'Menu disappears', 'Prices increase'], 'answer' => 'B'],
                        ['id' => 15, 'question' => 'Which item should be updated after every sale?', 'options' => ['Employee salary', 'Inventory quantity', 'Customer name', 'System password'], 'answer' => 'B'],
                        ['id' => 16, 'question' => 'What causes inaccurate inventory records?', 'options' => ['Proper monitoring', 'Regular updates', 'Unrecorded transactions', 'System automation'], 'answer' => 'C'],
                        ['id' => 17, 'question' => 'Why should spoiled items be removed from inventory?', 'options' => ['To increase profit', 'To maintain accurate stock records', 'To increase waste', 'To hide losses'], 'answer' => 'B'],
                        ['id' => 18, 'question' => 'Which report helps identify fast-moving items?', 'options' => ['Expense report', 'Sales and inventory report', 'Attendance log', 'Supplier list'], 'answer' => 'B'],
                        ['id' => 19, 'question' => 'Inventory systems help restaurants control which factor most?', 'options' => ['Staff behavior', 'Food cost', 'Customer attitude', 'Menu design'], 'answer' => 'B'],
                        ['id' => 20, 'question' => 'What is the effect of poor inventory management?', 'options' => ['Higher efficiency', 'Increased profit', 'Stock shortages and losses', 'Faster service'], 'answer' => 'C'],
                    ];
                @endphp

                @foreach($partTwoQuestions as $q)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="font-semibold mb-3">{{ $q['id'] }}. {{ $q['question'] }}</p>
                        @foreach(['A', 'B', 'C', 'D'] as $index => $letter)
                            <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-100 p-2 rounded">
                                <input type="radio" name="question_{{ $q['id'] }}" value="{{ $letter }}" required
                                    class="mr-3 w-4 h-4">
                                <span>{{ $letter }}. {{ $q['options'][$index] }}</span>
                            </label>
                        @endforeach
                    </div>
                @endforeach
            </form>
        </div>

        <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
            <button type="button" onclick="goBackToPartOne()"
                class="border text-black px-6 py-2 rounded-lg font-semibold transition">
                Back to Part I
            </button>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">Part 2 of 3</span>
                <button type="button" onclick="submitPartTwo()"
                    class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black px-6 py-2 rounded-lg font-semibold transition">
                    Continue to Part III
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Part III Modal - Hospitality Workflow -->
<div id="partThreeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black px-6 py-4">
            <h2 class="text-2xl font-bold">Part III: Hospitality Workflow & Scenarios</h2>
            <p class="text-sm mt-1">Questions 21-25 | Final part of the assessment</p>
        </div>

        <div class="overflow-y-auto max-h-[calc(90vh-200px)] p-6">
            <form id="partThreeForm" method="POST" action="{{ route('student.submit-quiz') }}">
                @csrf
                <input type="hidden" name="part_one_answers" id="partOneAnswers">
                <input type="hidden" name="part_two_answers" id="partTwoAnswers">
                <input type="hidden" name="age" id="hiddenAge">
<input type="hidden" name="section" id="hiddenSection">
<input type="hidden" name="sex" id="hiddenSex">  <!-- ADD THIS LINE -->

                @php
                    $partThreeQuestions = [
                        ['id' => 21, 'question' => 'A customer orders a dish that is out of stock. What is the best action?', 'options' => ['Proceed with the order', 'Inform the customer and suggest alternatives', 'Ignore the issue', 'Close the restaurant'], 'answer' => 'B'],
                        ['id' => 22, 'question' => 'How does a PoS system improve customer service?', 'options' => ['Slows order processing', 'Reduces order accuracy', 'Speeds up transactions', 'Removes menu items'], 'answer' => 'C'],
                        ['id' => 23, 'question' => 'Why is system training important for restaurant staff?', 'options' => ['To waste time', 'To reduce errors and improve efficiency', 'To increase workload', 'To confuse employees'], 'answer' => 'B'],
                        ['id' => 24, 'question' => 'What should be done after completing a customer transaction?', 'options' => ['Delete the order', 'Update sales and inventory records', 'Shutdown the system', 'Ignore the data'], 'answer' => 'B'],
                        ['id' => 25, 'question' => 'What skill is MOST improved by using RestauSim?', 'options' => ['Cooking', 'PoS and inventory management', 'Table decoration', 'Marketing'], 'answer' => 'B'],
                    ];
                @endphp

                @foreach($partThreeQuestions as $q)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="font-semibold mb-3">{{ $q['id'] }}. {{ $q['question'] }}</p>
                        @foreach(['A', 'B', 'C', 'D'] as $index => $letter)
                            <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-100 p-2 rounded">
                                <input type="radio" name="question_{{ $q['id'] }}" value="{{ $letter }}" required
                                    class="mr-3 w-4 h-4">
                                <span>{{ $letter }}. {{ $q['options'][$index] }}</span>
                            </label>
                        @endforeach
                    </div>
                @endforeach
            </form>
        </div>

        <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
            <button type="button" onclick="goBackToPartTwo()"
                class="border text-black px-6 py-2 rounded-lg font-semibold transition">
                Back to Part II
            </button>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">Part 3 of 3</span>
                <button type="submit" form="partThreeForm"
                    class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black px-6 py-2 rounded-lg font-semibold transition">
                    Submit Complete Assessment
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Store answers from each part and student info
    let partOneData = {};
    let partTwoData = {};
    let studentInfoData = {};

    document.addEventListener('DOMContentLoaded', function () {
        const hasCompletedQuiz = {{ $hasCompletedQuiz ? 'true' : 'false' }};

        if (!hasCompletedQuiz) {
            showModal('dataPrivacyModal');
        }
    });

    function showModal(modalId) {
        // Hide all modals
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        // Show specified modal
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function acceptPrivacy() {
        showModal('studentInfoModal');
    }

    function declinePrivacy() {
        alert('You must accept the data privacy terms to proceed with the assessment.');
    }

    function goBackToPrivacy() {
        showModal('dataPrivacyModal');
    }

function submitStudentInfo() {
    const age = document.getElementById('studentAge').value;
    const section = document.getElementById('studentSection').value;
    const sex = document.getElementById('studentSex').value;  // ADD THIS LINE

    if (!age || !section || !sex) {  // UPDATE THIS LINE
        alert('Please fill in all required fields (Age, Section, and Sex).');
        return;
    }

    if (age < 1 || age > 100) {
        alert('Please enter a valid age between 1 and 100.');
        return;
    }

    // Store student info
    studentInfoData = {
        age: age,
        section: section,
        sex: sex  // ADD THIS LINE
    };

    showModal('partOneModal');
}
    function submitPartOne() {
        const form = document.getElementById('partOneForm');
        const formData = new FormData(form);
        let allAnswered = true;

        for (let i = 1; i <= 10; i++) {
            if (!formData.get(`question_${i}`)) {
                allAnswered = false;
                break;
            }
        }

        if (!allAnswered) {
            alert('Please answer all questions in Part I before continuing.');
            return;
        }

        // Store Part I answers
        partOneData = Object.fromEntries(formData);
        showModal('partTwoModal');
    }

    function submitPartTwo() {
        const form = document.getElementById('partTwoForm');
        const formData = new FormData(form);
        let allAnswered = true;

        for (let i = 11; i <= 20; i++) {
            if (!formData.get(`question_${i}`)) {
                allAnswered = false;
                break;
            }
        }

        if (!allAnswered) {
            alert('Please answer all questions in Part II before continuing.');
            return;
        }

        // Store Part II answers
        partTwoData = Object.fromEntries(formData);
        showModal('partThreeModal');
    }

    function goBackToPartOne() {
        showModal('partOneModal');
    }

    function goBackToPartTwo() {
        showModal('partTwoModal');
    }

    // Final submission validation
document.getElementById('partThreeForm').addEventListener('submit', function (e) {
    const formData = new FormData(this);
    let allAnswered = true;

    for (let i = 21; i <= 25; i++) {
        if (!formData.get(`question_${i}`)) {
            allAnswered = false;
            break;
        }
    }

    if (!allAnswered) {
        e.preventDefault();
        alert('Please answer all questions in Part III before submitting.');
        return;
    }

    // Inject student info into hidden fields
    document.getElementById('hiddenAge').value = studentInfoData.age;
    document.getElementById('hiddenSection').value = studentInfoData.section;
    document.getElementById('hiddenSex').value = studentInfoData.sex;

        // Inject Part I answers as hidden inputs
        for (const [name, value] of Object.entries(partOneData)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            this.appendChild(input);
        }

        // Inject Part II answers as hidden inputs
        for (const [name, value] of Object.entries(partTwoData)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            this.appendChild(input);
        }

        // Combine all parts into hidden fields
        document.getElementById('partOneAnswers').value = JSON.stringify(partOneData);
        document.getElementById('partTwoAnswers').value = JSON.stringify(partTwoData);
    });
</script>

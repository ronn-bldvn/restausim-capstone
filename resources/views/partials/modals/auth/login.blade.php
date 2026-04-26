<div id="LoginForm"
     class="fixed inset-0 bg-black/50 {{ $errors->any() ? 'flex' : 'hidden' }} items-center justify-center z-50">
    <div class="relative z-50 w-[960px] h-auto bg-white rounded-2xl shadow-lg">
        <button id="closeLoginForm" class="absolute top-3 right-3 text-gray-600 hover:text-black text-2xl">&times;</button>

        <div class="flex flex-row">
            <!-- Left Side Image -->
            <div>
                <img class="max-w-xl h-auto rounded-2xl" src="{{ asset('images/fav-logo/logo-ver3.png') }}" alt="RestauSim Logo">
            </div>

            <!-- Right Side Form -->
            <div class="flex flex-col my-auto w-full h-full p-8 bg-white rounded-lg">

                <!-- Title -->
                <div class="flex justify-center items-center mb-6">
                    <span class="font-['Barlow'] text-3xl font-black text-[#0D0D54]">Sign</span>
                    <span class="font-['Barlow'] text-3xl font-black text-[#EA7C69]">In</span>
                </div>

                <!-- Alert Messages -->
                <x-alert type="success" :message="session('success')" />
                <x-alert type="error" :message="session('error')" />


                <!-- Login Form -->
                <x-form action="{{ route('login') }}" method="POST" class="flex flex-col h-full justify-between space-y-5">
                    @csrf
                    <div class="flex flex-col space-y-5">

                        <!-- Username/Email -->
                        <x-input
                            name="login"
                            label="Username/Email"
                            placeholder="Please enter your Username/Email"
                            variant="login"
                            required
                            class="w-full"
                        />

                        <!-- Password -->
                        <x-input
                            name="password"
                            type="password"
                            label="Password"
                            placeholder="Please enter your Password"
                            variant="login"
                            required
                            class="w-full"
                        />

                        <!-- Actions -->
                        <div class="flex items-center justify-between w-full mt-4">
                            <a href="#" class="text-xs text-gray-500 hover:text-[#EA7C69] underline">
                                Forgot Password?
                            </a>
                            <x-button variant="login" class="w-32 py-2 rounded-full">
                                Login
                            </x-button>
                        </div>
                    </div>
                </x-form>
            </div>
        </div>
    </div>
</div>

<!-- Script for Modal Behavior -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('openLoginForm');
        const modal = document.getElementById('LoginForm');
        const closeBtn = document.getElementById('closeLoginForm');

        if (openBtn) {
            openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        }

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        // Optional: Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });

        // 🔹 Automatically open the modal if there are errors
        @if ($errors->any() || session('error'))
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        @endif
    });
</script>

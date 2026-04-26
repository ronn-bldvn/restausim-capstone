<div id="RegisterForm" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="relative z-50 w-[960px] h-auto bg-white rounded-2xl shadow-lg">
        <button id="closeRegisterForm" class="absolute top-3 right-3 text-gray-600 hover:text-black text-2xl">&times;</button>

        <div class="flex flex-row">
            <div class="">
                <img class="max-w-xl h-auto rounded-2xl" src="{{ asset('images/fav-logo/logo-ver3.png') }}" alt="RestauSim Logo">
            </div>
            <div class="flex flex-col my-auto w-full h-full p-8 bg-white rounded-lg">
                <!-- Title -->
                <div class="flex justify-center items-center mb-10">
                    <span class="font-['Barlow'] text-3xl font-black text-[#0D0D54] mr-1">Create </span>
                    <span class="font-['Barlow'] text-3xl font-black text-[#EA7C69]">Account </span>
                </div>

                <!-- Alert Messages -->
                <x-alert type="success" :message="session('success')" />
                <x-alert type="error" :message="session('error')" />

                <!-- Form -->
                <x-form action="{{ route('register.store') }}" method="POST">
                    <x-input name="name" type="text" label="Full Name" placeholder="Please enter your Fullname" variant="register" class="w-full" required />

                    <div class="grid grid-cols-2 gap-x-4">
                        <x-input name="username" label="Username" placeholder="Enter your Username" variant="register" required />

                        <!-- Email Input with proper value and readonly handling -->
                        <x-input
                            name="email"
                            type="email"
                            label="Email"
                            placeholder="Enter your Email"
                            variant="register"
                            :value="session('emailToJoin') ?? old('email')"
                            :readonly="session('emailToJoin') ? true : false"
                            required
                        />
                    </div>

                    <!-- Hidden input for section ID -->
                    @if(session('sectionToJoin'))
                        <input type="hidden" name="section_to_join" value="{{ session('sectionToJoin') }}">
                    @endif

                    <div class="grid grid-cols-2 gap-x-4">
                        <x-input name="password" type="password" label="Password" placeholder="Enter your Password" variant="register" required />
                        <x-input name="password_confirmation" type="password" label="Confirm Password" placeholder="Confirm your Password" variant="register" required />
                    </div>

                    <div class="flex flex-row justify-center items-center mt-4">
                        <x-button variant="login">Register</x-button>
                    </div>
                </x-form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const openBtn = document.getElementById('openRegisterForm');
    const modal = document.getElementById('RegisterForm');
    const closeBtn = document.getElementById('closeRegisterForm');

    if (openBtn && modal && closeBtn) {
        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    }
});
</script>

@include('components.resources')
@include('partials.links.links')

<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">

<x-favicon />
<title>RestauSim</title>

<div class="relative min-h-screen w-full bg-cover bg-center bg-no-repeat flex items-center justify-center p-4" style="background-image: url('{{ asset('images/restausim-bg.png') }}');">

    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"></div>

    <x-alert type="success" :message="session('success')" />
    <x-alert type="error1"  :message="session('error')" />
    <x-alert type="info"    :message="session('info')" />

    @if ($errors->any())
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>⚠ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Login Section --}}
    <div id="Login" class="relative w-full max-w-4xl grid lg:grid-cols-2 rounded-3xl overflow-hidden shadow-2xl bg-neutral-900/60 backdrop-blur-xl border border-white/10 animate-fade-in">
        {{-- Branding Side --}}
        <div class="lg:flex flex-col items-center justify-center bg-[#0D0D54]/40 p-12 border-r border-white/5">
            <img class="w-64 h-auto drop-shadow-2xl rounded-3xl" src="{{ asset('images/fav-logo/logo-ver3.png') }}" alt="Logo">
            <div class="text-center mt-8">
                <h2 class="text-3xl font-black text-white">Welcome to <span class="text-[#EA7C69]">RestauSim</span></h2>
                <p class="text-white/60 mt-2">Skill-Building Made Simple.</p>
            </div>
        </div>

        {{-- Form Side --}}
        <div class="p-8 sm:p-12">
            <div class="mb-8">
                <h2 class="text-3xl font-black text-white">Sign <span class="text-[#EA7C69]">In</span></h2>
            </div>

            <x-form action="{{ route('login') }}" method="POST" class="space-y-5">
                <x-input name="login" label="Username or Email" placeholder="Username/Email" variant="auth" class="w-full" labelVariant="auth" required />

                <div class="relative">
                    <x-input id="login_pass" name="password" type="password" label="Password" placeholder="••••••••" variant="auth" class="w-full" labelVariant="auth" required />
                    <button type="button"
                            onclick="togglePassword('login_pass', this)"
                            class="absolute right-3 top-[38px] text-white/60 hover:text-[#EA7C69] transition">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <div class="flex items-center justify-between gap-3 pt-2">
                    <button type="button" onclick="openForgotModal()"
                            class="text-xs text-white/60 hover:text-[#EA7C69] transition-colors">
                        Forgot Password?
                    </button>
                    <x-button variant="login" class="px-8 py-2.5 rounded-xl shadow-lg shadow-[#EA7C69]/20 hover:scale-105 transition-transform">
                        Sign In
                    </x-button>
                </div>
            </x-form>

            <div class="px-9 my-6 flex items-center">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-3 text-gray-500 text-sm">OR</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <a href="{{ route('redirect.google') }}" class="w-full p-4 flex items-center justify-center gap-3 rounded-xl py-3 border border-white/10 bg-white/5 hover:bg-white/10 transition-all text-white font-medium">
                <img src="{{ asset('images/google/google-logo.png') }}" alt="G" class="w-5 h-5">
                <span class="text-small">Login with Google</span>
            </a>

            <p class="mt-8 text-center text-white/70 text-sm">
                Don't have an account?
                <button onclick="toggleForms()" class="text-[#EA7C69] font-bold hover:underline ml-1">Register Here!</button>
            </p>
        </div>
    </div>

    {{-- Register Section --}}
    @if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-400 bg-red-500/10 p-4 text-red-400">
        <div class="flex items-center gap-2 font-semibold">
            <i class="fa-solid fa-circle-exclamation"></i>
            Please fix the following errors:
        </div>
    
        <ul class="mt-2 text-sm list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div id="Register" class="hidden relative w-full max-w-4xl grid lg:grid-cols-2 rounded-3xl overflow-hidden shadow-2xl bg-neutral-900/60 backdrop-blur-xl border border-white/10 animate-fade-in">
        {{-- Branding Side --}}
        <div class="hidden lg:flex flex-col items-center justify-center bg-[#0D0D54]/40 p-12 border-r border-white/5">
            <img class="w-64 h-auto drop-shadow-2xl rounded-3xl" src="{{ asset('images/fav-logo/logo-ver3.png') }}" alt="Logo">
            <div class="text-center mt-8">
                <h2 class="text-3xl font-black text-white">Welcome to <span class="text-[#EA7C69]">RestauSim</span></h2>
                <p class="text-white/60 mt-2">Skill-Building Made Simple.</p>
            </div>
        </div>

        {{-- Form Side --}}
        <div class="p-8 sm:p-12">
            <div class="mb-8">
                <h2 class="text-3xl font-black text-white">Sign <span class="text-[#EA7C69]">Up</span></h2>
            </div>

            <x-form action="{{ route('register.store') }}" method="POST" class="space-y-4" id="registerForm">
                <x-input id="reg_name" name="name" type="text" label="Full Name" variant="auth" class="w-full" labelVariant="auth" placeholder="John Doe" required />

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <x-input id="reg_username" name="username" label="Username" variant="auth" labelVariant="auth" placeholder="johndoe" required />
                        <p id="usernameHelp" class="mt-1 text-xs text-white/60"></p>
                    </div>
                    <div>
                        <x-input id="reg_email" name="email" type="email" label="Email" variant="auth" labelVariant="auth"
                                placeholder="johndoe@clsu2.edu.ph"
                                :value="session('emailToJoin') ?? old('email')"
                                :readonly="session('emailToJoin') ? true : false" required />
                        <p id="emailHelp" class="mt-1 text-xs text-white/60"></p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    {{-- PASSWORD --}}
                    <div class="relative">
                        <x-input id="reg_password" name="password" type="password" label="Password"
                            variant="auth" labelVariant="auth" placeholder="••••••••" required />
                        <button type="button"
                            onclick="togglePassword('reg_password', this)"
                            class="absolute right-3 top-[38px] text-white/60 hover:text-[#EA7C69] transition">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <p id="passwordHelp" class="mt-1 text-xs text-white/60"></p>
                    </div>

                    {{-- CONFIRM --}}
                    <div class="relative">
                        <x-input id="reg_password_confirm" name="password_confirmation" type="password" label="Confirm"
                            variant="auth" labelVariant="auth" placeholder="••••••••" required />
                        <button type="button"
                            onclick="togglePassword('reg_password_confirm', this)"
                            class="absolute right-3 top-[38px] text-white/60 hover:text-[#EA7C69] transition">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <p id="confirmHelp" class="mt-1 text-xs text-white/60"></p>
                    </div>
                </div>

                <div class="pt-4">
                    <x-button
                        type="button"
                        variant="login"
                        class="w-full py-3 rounded-xl shadow-lg shadow-[#EA7C69]/20"
                        id="registerBtn"
                        onclick="sendOTP()"
                        disabled>
                        Create Account
                    </x-button>
                    <p id="formHelp" class="mt-2 text-xs text-white/50"></p>
                </div>
            </x-form>

            <p class="mt-8 text-center text-white/70 text-sm">
                Already Have an Account?
                <button onclick="toggleForms()" class="text-[#EA7C69] font-bold hover:underline ml-1">Login Here!</button>
            </p>
        </div>
    </div>

</div>

{{-- ===================== FORGOT PASSWORD MODAL ===================== --}}
<div id="forgotModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     aria-modal="true" role="dialog">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeForgotModal()"></div>

    {{-- Modal Card --}}
    <div class="relative w-full max-w-md rounded-3xl bg-neutral-900/90 border border-white/10 shadow-2xl p-8 animate-fade-in">

        {{-- Close button --}}
        <button onclick="closeForgotModal()"
                class="absolute top-4 right-5 text-white/40 hover:text-white text-xl transition">✕</button>

        {{-- Icon --}}
        <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-[#EA7C69]/20 mb-6 mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#EA7C69]" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 1.79-8 4v1h16v-1c0-2.21-3.582-4-8-4z"/>
            </svg>
        </div>

        <h3 class="text-2xl font-black text-white text-center mb-1">Forgot Password?</h3>
        <p class="text-white/50 text-sm text-center mb-6">
            Enter your registered email and we'll send you a reset link.
        </p>

        {{-- Idle state --}}
        <div id="forgotIdle">
            <form action="{{ route('password.email') }}" method="POST" class="space-y-4" id="forgotForm">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-white/70 mb-1.5 uppercase tracking-wide">
                        Email Address
                    </label>
                    <input id="forgot_email" name="email" type="email" required
                           placeholder="johndoe@gmail.com"
                           class="w-full rounded-xl bg-white/5 border border-white/10 text-white placeholder-white/30
                                  px-4 py-3 text-sm focus:outline-none focus:border-[#EA7C69]/60 focus:ring-1
                                  focus:ring-[#EA7C69]/40 transition" />
                    <p id="forgotEmailHelp" class="mt-1 text-xs text-red-400 hidden"></p>
                </div>

                <button type="submit" id="forgotSubmitBtn"
                        class="w-full py-3 rounded-xl bg-[#EA7C69] hover:bg-[#d96b57] text-white font-bold
                               shadow-lg shadow-[#EA7C69]/20 hover:scale-[1.02] transition-transform text-sm">
                    Send Reset Link
                </button>
            </form>
        </div>

        {{-- Success state (shown after submit) --}}
        <div id="forgotSuccess" class="hidden text-center space-y-4">
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-green-500/20 mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-400" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-white font-semibold">Reset link sent!</p>
            <p class="text-white/50 text-sm">Check your inbox and follow the instructions to reset your password.</p>
            <button onclick="closeForgotModal()"
                    class="mt-2 text-[#EA7C69] text-sm font-bold hover:underline">Back to Login</button>
        </div>

        <p class="mt-4 text-center text-white/40 text-xs">
            Remembered it?
            <button onclick="closeForgotModal()" class="text-[#EA7C69] hover:underline font-semibold ml-1">Go back</button>
        </p>
    </div>
</div>

{{-- ===================== OTP VERIFICATION MODAL ===================== --}}
<div id="otpModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-sm rounded-3xl bg-neutral-900/90 border border-white/10 shadow-2xl p-8">

        <h3 class="text-2xl font-black text-white text-center mb-2">
            Email Verification
        </h3>

        <p class="text-white/50 text-sm text-center mb-6">
            Enter the OTP sent to your CLSU email
        </p>

        <input id="otpInput"
               type="text"
               maxlength="6"
               placeholder="Enter 6-digit OTP"
               class="w-full rounded-xl bg-white/5 border border-white/10 text-white
                      px-4 py-3 text-center tracking-widest text-lg
                      focus:outline-none focus:border-[#EA7C69]">

        <p id="otpHelp" class="text-red-400 text-xs mt-2 hidden"></p>

        <button onclick="verifyOTP()"
                class="mt-4 w-full py-3 rounded-xl bg-[#EA7C69] hover:bg-[#d96b57]
                       text-white font-bold">
            Verify OTP
        </button>

        <button onclick="closeOTPModal()"
                class="mt-3 text-white/50 text-sm w-full hover:text-white">
            Cancel
        </button>

    </div>
</div>


<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    /* ─────────────────── FORGOT PASSWORD MODAL ─────────────────── */
    function openForgotModal() {
        const modal = document.getElementById('forgotModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('forgot_email')?.focus();
    }

    function closeForgotModal() {
        const modal = document.getElementById('forgotModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        // Reset to idle state for next open
        document.getElementById('forgotIdle').classList.remove('hidden');
        document.getElementById('forgotSuccess').classList.add('hidden');
        document.getElementById('forgotEmailHelp').classList.add('hidden');
        document.getElementById('forgotForm')?.reset();
    }

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeForgotModal();
    });

    // Forgot form: client-side email check + optional AJAX submit
    document.getElementById('forgotForm')?.addEventListener('submit', function(e) {
        const emailEl  = document.getElementById('forgot_email');
        const helpEl   = document.getElementById('forgotEmailHelp');
        const val      = (emailEl?.value || '').trim().toLowerCase();
        const isValid  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);

        if (!isValid) {
            e.preventDefault();
            helpEl.textContent = 'Please enter a valid email address.';
            helpEl.classList.remove('hidden');
            return;
        }

        helpEl.classList.add('hidden');

        e.preventDefault();
        const btn = document.getElementById('forgotSubmitBtn');
        btn.disabled    = true;
        btn.textContent = 'Sending…';

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                             ?? '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: val })
        })
        .then(res => {
            // Show success regardless (avoids email enumeration)
            document.getElementById('forgotIdle').classList.add('hidden');
            document.getElementById('forgotSuccess').classList.remove('hidden');
        })
        .catch(() => {
            helpEl.textContent = 'Something went wrong. Please try again.';
            helpEl.classList.remove('hidden');
            btn.disabled    = false;
            btn.textContent = 'Send Reset Link';
        });
        // ─────────────────────────────────────────────────────────
    });

    /* ─────────────────── REGISTER VALIDATION ─────────────────── */
    document.addEventListener("DOMContentLoaded", () => {
        const usernameEl = document.getElementById("reg_username");
        const emailEl    = document.getElementById("reg_email");
        const passEl     = document.getElementById("reg_password");
        const confirmEl  = document.getElementById("reg_password_confirm");
        const btn        = document.getElementById("registerBtn");
        const form       = document.getElementById("registerForm");

        const usernameHelp = document.getElementById("usernameHelp");
        const emailHelp    = document.getElementById("emailHelp");
        const passwordHelp = document.getElementById("passwordHelp");
        const confirmHelp  = document.getElementById("confirmHelp");
        const formHelp     = document.getElementById("formHelp");

        const allowedDomains = ["clsu2.edu.ph"];
        const touched = { username: false, email: false, password: false, confirm: false };
        let submitted = false;

        function clearMsg(el) {
            if (!el) return;
            el.textContent = "";
            el.classList.remove("text-red-400", "text-green-400");
            el.classList.add("text-white/60");
        }

        function setMsg(el, ok, msg) {
            if (!el) return;
            el.innerHTML = msg;
            el.classList.remove("text-white/60", "text-green-400", "text-red-400");
            el.classList.add(ok ? "text-green-400" : "text-red-400");
        }

        function validateUsername(show = false) {
            const v = (usernameEl?.value || "").trim();
            const ok = v.length >= 5;
            if (show) setMsg(usernameHelp, ok, ok ? "" : "Username must be at least 5 characters.");
            return ok;
        }

        function validateEmail(show = false) {
            if (emailEl?.hasAttribute("readonly")) {
                if (show) clearMsg(emailHelp);
                return true;
            }
            const v = (emailEl?.value || "").trim().toLowerCase();
            const isEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
            const domain = isEmail ? v.split("@").pop() : "";
            const ok = isEmail && allowedDomains.includes(domain);
            if (show) setMsg(emailHelp, ok, ok ? "" : "Email must be at @clsu2.edu.ph only.");
            return ok;
        }

        function validatePassword(show = false) {
            const v = passEl?.value || "";
            const hasLength  = v.length >= 8;
            const hasUpper   = /[A-Z]/.test(v);
            const hasNumber  = /[0-9]/.test(v);
            const hasSpecial = /[^A-Za-z0-9]/.test(v);
            const ok = hasLength && hasUpper && hasNumber && hasSpecial;
            if (show) {
                if (ok) {
                    setMsg(passwordHelp, true, "");
                } else {
                    const msgs = [];
                    if (!hasLength)  msgs.push("• At least 8 characters");
                    if (!hasUpper)   msgs.push("• At least 1 uppercase letter");
                    if (!hasNumber)  msgs.push("• At least 1 number");
                    if (!hasSpecial) msgs.push("• At least 1 special character");
                    setMsg(passwordHelp, false, msgs.join("<br>"));
                }
            }
            return ok;
        }

        function validateConfirm(show = false) {
            const p = passEl?.value || "";
            const c = confirmEl?.value || "";
            const ok = c.length > 0 && c === p;
            if (show) setMsg(confirmHelp, ok, ok ? "Passwords match" : "Passwords do not match.");
            return ok;
        }

        function updateFormState() {
            const okUser  = validateUsername(submitted || touched.username);
            const okEmail = validateEmail(submitted || touched.email);
            const okPass  = validatePassword(submitted || touched.password);
            const okConf  = validateConfirm(submitted || touched.confirm);
            const allOk   = okUser && okEmail && okPass && okConf;

            if (btn) btn.disabled = !allOk;

            if (formHelp) {
                if (!submitted) {
                    formHelp.textContent = "";
                } else {
                    formHelp.textContent = allOk
                        ? "You can create your account now."
                        : "Please fix the highlighted fields.";
                }
            }

            if (!submitted) {
                if (!touched.username) clearMsg(usernameHelp);
                if (!touched.email)    clearMsg(emailHelp);
                if (!touched.password) clearMsg(passwordHelp);
                if (!touched.confirm)  clearMsg(confirmHelp);
            }
        }

        function markTouched(key) { touched[key] = true; updateFormState(); }

        usernameEl?.addEventListener("input", () => markTouched("username"));
        usernameEl?.addEventListener("blur",  () => markTouched("username"));
        emailEl?.addEventListener("input", () => markTouched("email"));
        emailEl?.addEventListener("blur",  () => markTouched("email"));
        passEl?.addEventListener("input", () => markTouched("password"));
        passEl?.addEventListener("blur",  () => markTouched("password"));
        confirmEl?.addEventListener("input", () => markTouched("confirm"));
        confirmEl?.addEventListener("blur",  () => markTouched("confirm"));

        form?.addEventListener("submit", (e) => {
            submitted = true;
            touched.username = touched.email = touched.password = touched.confirm = true;
            updateFormState();
            if (btn?.disabled) e.preventDefault();
        });

        updateFormState();
    });

    /* ─────────────────── FORM TOGGLE ─────────────────── */
    function toggleForms() {
        document.getElementById('Login').classList.toggle('hidden');
        document.getElementById('Register').classList.toggle('hidden');
    }

    /* ─────────────────── PASSWORD VISIBILITY ─────────────────── */
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector("i");
        
        if (!input) return;
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    /* ─────────────────── SHOW REGISTER ON VALIDATION ERRORS ─────────────────── */
    document.addEventListener("DOMContentLoaded", function () {
        @if ($errors->has('name') || $errors->has('username') || $errors->has('email') || $errors->has('password_confirmation'))
            toggleForms();
        @endif
        
        @if (!empty($autoShowRegister))
            toggleForms();
        @endif
    });
    
    function sendOTP() {
        const email = document.getElementById("reg_email").value;
        const btn   = document.getElementById("registerBtn");
    
        btn.disabled    = true;
        btn.textContent = "Sending OTP...";
    
        fetch("/send-otp", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ email })
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (ok && data.success) {
                // Clear any previous errors and open the OTP modal
                setFieldError("emailHelp", null);
                document.getElementById("otpModal").classList.remove("hidden");
                return;
            }
    
            // Laravel validation errors → { errors: { email: ["The email has already been taken."] } }
            if (data.errors) {
                if (data.errors.email)    setFieldError("emailHelp",    data.errors.email[0]);
                if (data.errors.otp)      setFieldError("otpHelp",      data.errors.otp[0]);
            } else {
                // Custom errors from the controller (e.g. cooldown, wrong domain)
                const msg = data.error ?? data.message ?? "Something went wrong.";
                setFieldError("emailHelp", msg);
            }
        })
        .catch(() => setFieldError("emailHelp", "Network error. Please try again."))
        .finally(() => {
            btn.disabled    = false;
            btn.textContent = "Create Account";
        });
    }
    
    // Helper — pass null to clear the message
    function setFieldError(helpId, message) {
        const el = document.getElementById(helpId);
        if (!el) return;
    
        if (!message) {
            el.textContent = "";
            el.classList.remove("text-red-400");
            el.classList.add("text-white/60");
            return;
        }
    
        el.textContent = message;
        el.classList.remove("text-white/60", "text-green-400");
        el.classList.add("text-red-400");
    }
    
    function verifyOTP() {
        const otp   = document.getElementById("otpInput").value;
        const email = document.getElementById("reg_email").value;
        const help  = document.getElementById("otpHelp");
    
        fetch("/verify-otp", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",          // ← ADD THIS
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ otp, email })
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (ok && data.success) {
                document.getElementById("registerForm").submit();
            } else {
                help.textContent = data.error ?? (data.errors?.otp?.[0]) ?? "Invalid OTP.";
                help.classList.remove("hidden");
            }
        })
        .catch(err => {
            help.textContent = err.message || "Network error.";
            help.classList.remove("hidden");
        });
    }
    function closeOTPModal(){
        document.getElementById("otpModal").classList.add("hidden");
    }
</script>

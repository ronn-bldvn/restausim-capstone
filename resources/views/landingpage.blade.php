<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RestauSim | Immersive PoS & Inventory Training</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <x-favicon />

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700;900&family=Poppins:wght@400;600;700;900&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"
        integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Prevent horizontal overflow */
        html,
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        * {
            box-sizing: border-box;
        }
    </style>
</head>

<body class="bg-white text-gray-800 overflow-x-hidden">

    <header
        class="flex flex-wrap md:flex-nowrap items-center justify-between h-auto md:h-[70px] border-b border-gray-200 shadow-sm bg-white sticky top-0 z-40">
        <!-- Logo -->
        <div class="w-full md:w-[260px] h-[60px] md:h-full bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] flex items-center justify-center px-4"
            style="clip-path: polygon(0 0, 100% 0, 85% 100%, 15% 100%)">
            <h1 class="text-[26px] md:text-[30px] font-black text-[#0D0D54] font-[Barlow] tracking-tight">
                <a href="#">
                    Restau<span class="text-[#EA7C69]">Sim.</span>
                </a>
            </h1>
        </div>

        <!-- Nav - Only shown to guests -->
        <div class="w-full md:w-auto flex justify-center md:justify-end py-2 md:py-0 pr-0 md:pr-8 gap-4">
            <a href="{{ route('login') }}"
                class="hover:text-[#EA7C69] text-[#0D0D54] font-semibold transition-colors duration-300">
                Login
            </a>
            <span class="text-gray-400">|</span>
            <a href="{{ route('register') }}"
                class="hover:text-[#EA7C69] text-[#0D0D54] font-semibold transition-colors duration-300">
                Register
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="mt-10 px-2 sm:px-4 md:px-6 lg:px-8 overflow-hidden">
        <div class="text-gray-600 body-font bg-no-repeat bg-center bg-cover rounded-3xl overflow-hidden w-full max-w-7xl mx-auto"
            style="background-image: url('{{ asset('images/bg.png') }}');">
            <div
                class="container mx-auto flex flex-col lg:flex-row items-center justify-between h-auto lg:h-[495px] px-4 sm:px-6 md:px-8 lg:px-10 py-10 m-4">

                <!-- Text Section -->
                <div
                    class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left mt-8 lg:mt-0">
                    <h1
                        class="title-font text-3xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-medium text-gray-900 leading-tight break-words">
                        <span class="text-white block">WELCOME TO</span>
                        <span class="text-[#B9B9FF] font-black">Restau</span><span
                            class="text-[#EA7C69] font-black">Sim.</span>
                    </h1>
                    <p class="mb-8 text-white mt-4 text-sm sm:text-base lg:text-lg text-justify max-w-2xl px-2 sm:px-0">
                        Bridge the gap between classroom theory and industry practice. RestauSim provides
                        1st-year CLSU DHTM students with immersive, hands-on training in restaurant Point
                        of Sale and inventory management—essential skills that only 2% of hospitality curricula
                        currently address. Experience real-world operations through role-based simulations
                        accessible on web and mobile.
                    </p>
                    <!-- Button for guests only -->
                    <a href="{{ route('register') }}"
                        class="bg-[#EA7C69] hover:bg-[#d76a5b] text-white font-bold px-8 py-3 rounded-full shadow-md transition duration-300">
                        Get Started
                    </a>
                </div>

                <!-- Image Section (hidden on small & medium devices) -->
                <div class="hidden lg:flex w-full lg:w-1/2 justify-center items-center mt-8 lg:mt-0">
                    <img class="w-4/5 lg:w-[80%] h-auto rounded-2xl object-cover scale-110" alt="hero"
                        src="{{ asset('images/image.png') }}">
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section class="py-16 bg-white overflow-hidden" id="features">
        <div class="container mx-auto px-2 sm:px-4 md:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-center mb-14 text-[#0D0D54] break-words"
                data-aos="fade-up">
                Key Features
            </h2>

            <!-- Responsive Grid -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4 sm:gap-6 md:gap-8 max-w-7xl mx-auto">

                <!-- Feature Cards -->
                <div class="p-6 sm:p-8 bg-gradient-to-br from-[#fceabb] to-[#fceabb]/50 rounded-2xl shadow-lg transition transform hover:-translate-y-2 w-full"
                    data-aos="zoom-in">
                    <div class="w-14 h-14 bg-[#EA7C69] rounded-full flex items-center justify-center mb-5">
                        <i class="fas fa-utensils text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 text-[#0D0D54]">Order Management</h3>
                    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
                        Waiters can create and customize orders, display categorized menus, and send them to the
                        kitchen.
                    </p>
                </div>

                <div class="p-6 sm:p-8 bg-gradient-to-br from-[#B9B9FF]/30 to-[#B9B9FF]/10 rounded-2xl shadow-lg transition transform hover:-translate-y-2 w-full"
                    data-aos="zoom-in">
                    <div class="w-14 h-14 bg-[#B9B9FF] rounded-full flex items-center justify-center mb-5">
                        <i class="fas fa-concierge-bell text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 text-[#0D0D54]">Kitchen Display</h3>
                    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
                        Tracks orders with color-coded statuses and prioritized queues for smooth kitchen operations.
                    </p>
                </div>

                <div class="p-6 sm:p-8 bg-gradient-to-br from-[#EA7C69]/30 to-[#EA7C69]/10 rounded-2xl shadow-lg transition transform hover:-translate-y-2 w-full"
                    data-aos="zoom-in">
                    <div class="w-14 h-14 bg-[#EA7C69] rounded-full flex items-center justify-center mb-5">
                        <i class="fas fa-cash-register text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 text-[#0D0D54]">Billing & Payment</h3>
                    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
                        Automates billing, applies discounts, and supports multiple payment options.
                    </p>
                </div>

                <div class="p-6 sm:p-8 bg-gradient-to-br from-[#B9B9FF]/30 to-[#EA7C69]/10 rounded-2xl shadow-lg transition transform hover:-translate-y-2 w-full"
                    data-aos="zoom-in">
                    <div class="w-14 h-14 bg-[#0D0D54] rounded-full flex items-center justify-center mb-5">
                        <i class="fas fa-boxes text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 text-[#0D0D54]">Inventory Tracking</h3>
                    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
                        Monitors stock levels, sends low-stock alerts, and auto-updates inventory.
                    </p>
                </div>

                <div class="p-6 sm:p-8 bg-gradient-to-br from-[#fceabb]/50 to-[#B9B9FF]/20 rounded-2xl shadow-lg transition transform hover:-translate-y-2 w-full"
                    data-aos="zoom-in">
                    <div class="w-14 h-14 bg-[#EA7C69] rounded-full flex items-center justify-center mb-5">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 text-[#0D0D54]">Reports & Analytics</h3>
                    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
                        View detailed sales and inventory reports with visual performance insights.
                    </p>
                </div>

                <div class="p-6 sm:p-8 bg-gradient-to-br from-[#B9B9FF]/30 to-[#B9B9FF]/5 rounded-2xl shadow-lg transition transform hover:-translate-y-2 w-full"
                    data-aos="zoom-in">
                    <div class="w-14 h-14 bg-[#B9B9FF] rounded-full flex items-center justify-center mb-5">
                        <i class="fas fa-users-cog text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 text-[#0D0D54]">Role-Based Simulation</h3>
                    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
                        Train as a manager, cashier, or waiter in real-world restaurant simulations.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem & Solution Section -->
    <section class="py-16 bg-gradient-to-br from-gray-50 to-white overflow-hidden">
        <div class="container mx-auto px-2 sm:px-4 md:px-6 lg:px-8 max-w-6xl">
            <div class="grid md:grid-cols-2 gap-6 md:gap-8 lg:gap-12 items-center">
                <div data-aos="fade-right">
                    <h3 class="text-2xl sm:text-3xl font-bold text-[#0D0D54] mb-4 break-words">Bridging the Skills Gap
                    </h3>
                    <p class="text-gray-700 mb-4 text-sm sm:text-base">
                        Only 2% of tourism curricula focuses on technology and innovation. CLSU DHTM students
                        currently learn through theory alone, lacking hands-on experience with industry-standard
                        tools like Point of Sale and Inventory Management systems.
                    </p>
                    <p class="text-gray-700 text-sm sm:text-base">
                        RestauSim transforms this by providing immersive, practical training that prepares
                        students for real-world hospitality operations.
                    </p>
                </div>
                <div data-aos="fade-left">
                    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg">
                        <h4 class="font-bold text-[#EA7C69] text-lg sm:text-xl mb-4">What Students Gain:</h4>
                        <ul class="space-y-3 text-gray-700 text-sm sm:text-base">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#EA7C69] mt-1 mr-3 flex-shrink-0"></i>
                                <span>Hands-on PoS & inventory experience</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#EA7C69] mt-1 mr-3 flex-shrink-0"></i>
                                <span>Multi-role operational understanding</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#EA7C69] mt-1 mr-3 flex-shrink-0"></i>
                                <span>Industry-ready technical skills</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#EA7C69] mt-1 mr-3 flex-shrink-0"></i>
                                <span>Real-time performance feedback</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Educational Context Section -->
    <section class="py-16 bg-[#0D0D54] text-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 max-w-6xl text-center">
            <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-6 break-words" data-aos="fade-up">
                Designed for HM 1135 & TM 1135
            </h3>
            <p class="text-base sm:text-lg text-gray-300 mb-8 max-w-3xl mx-auto" data-aos="fade-up">
                Applied Business Tools and Technologies - A foundational course for 1st-year
                CLSU DHTM students learning digital tools in hospitality and tourism management.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mt-12">
                <div data-aos="zoom-in" data-aos-delay="100">
                     <div class="text-3xl sm:text-4xl font-bold text-[#EA7C69] mb-2">Real-World</div>
                    <p class="text-gray-300 text-sm sm:text-base">Restaurant Operations Training</p>
                </div>
                <div data-aos="zoom-in" data-aos-delay="200">
                    <div class="text-3xl sm:text-4xl font-bold text-[#B9B9FF] mb-2">5</div>
                    <p class="text-gray-300 text-sm sm:text-base">Professional Roles to Master</p>
                </div>
                <div data-aos="zoom-in" data-aos-delay="300">
                    <div class="text-3xl sm:text-4xl font-bold text-[#fceabb] mb-2">100%</div>
                    <p class="text-gray-300 text-sm sm:text-base">Practical, Hands-On Learning</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Simulation-Based Learning Section -->
    <section class="py-16 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 max-w-6xl">
            <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-12 text-[#0D0D54] break-words"
                data-aos="fade-up">
                Why Simulation-Based Training?
            </h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-gradient-to-br from-[#B9B9FF]/10 to-white p-6 sm:p-8 rounded-2xl" data-aos="fade-right">
                    <i class="fas fa-graduation-cap text-3xl sm:text-4xl text-[#B9B9FF] mb-4"></i>
                    <h4 class="text-lg sm:text-xl font-bold text-[#0D0D54] mb-3">Bridges Academic-Industry Gap</h4>
                    <p class="text-gray-700 text-sm sm:text-base">
                        Addresses the skills gap identified in hospitality education where graduates
                        lack practical operational experience, particularly in billing precision and
                        management tasks.
                    </p>
                </div>
                <div class="bg-gradient-to-br from-[#EA7C69]/10 to-white p-6 sm:p-8 rounded-2xl" data-aos="fade-left">
                    <i class="fas fa-brain text-3xl sm:text-4xl text-[#EA7C69] mb-4"></i>
                    <h4 class="text-lg sm:text-xl font-bold text-[#0D0D54] mb-3">Enhances Decision-Making</h4>
                    <p class="text-gray-700 text-sm sm:text-base">
                        Research shows simulation-based learning improves decision-making, problem-solving,
                        and teamwork skills while linking theory to practice.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- Access Information Section -->
    <section class="py-16 bg-gradient-to-br from-[#fceabb]/20 to-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 max-w-4xl text-center">
            <h3 class="text-2xl sm:text-3xl font-bold text-[#0D0D54] mb-6 break-words" data-aos="fade-up">
                Accessible Anywhere, Anytime
            </h3>
            <p class="text-base sm:text-lg text-gray-700 mb-8" data-aos="fade-up">
                Available on web and mobile platforms, RestauSim ensures students can practice
                and learn whether in the classroom or at home.
            </p>
            <div class="flex justify-center gap-6 flex-wrap">
                <div class="bg-white p-6 rounded-xl shadow-lg" data-aos="zoom-in">
                    <i class="fas fa-desktop text-3xl sm:text-4xl text-[#B9B9FF] mb-2"></i>
                    <p class="font-semibold text-gray-800 text-sm sm:text-base">Web Platform</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg" data-aos="zoom-in" data-aos-delay="100">
                    <i class="fas fa-mobile-screen-button text-3xl sm:text-4xl text-[#EA7C69] mb-2"></i>
                    <p class="font-semibold text-gray-800 text-sm sm:text-base">Responsive Web Platform</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Research Foundation -->
    <section class="py-16 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 max-w-4xl text-center">
            <h3 class="text-2xl sm:text-3xl font-bold text-[#0D0D54] mb-8 break-words" data-aos="fade-up">
                Built on Solid Research
            </h3>
            <p class="text-base sm:text-lg text-gray-700 mb-6" data-aos="fade-up">
                RestauSim is developed through rigorous research using the Modified Waterfall SDLC methodology,
                incorporating feedback from DHTM faculty and aligned with industry standards.
            </p>
            <div class="bg-gradient-to-r from-[#B9B9FF]/10 to-[#EA7C69]/10 p-6 sm:p-8 rounded-2xl" data-aos="zoom-in">
                <p class="text-gray-800 italic mb-4 text-sm sm:text-base">
                    "This system addresses the critical need for practical, technology-focused training
                    in hospitality education, preparing students for the digital economy."
                </p>
                <p class="font-semibold text-[#0D0D54] text-sm sm:text-base">— CLSU DHTM Research Team</p>
            </div>
        </div>
    </section>



    <!-- Footer -->
    <footer class="bg-[#0D0D54] text-white py-10 overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 text-center">
            <p class="text-xl sm:text-2xl font-[Barlow] font-black tracking-wide">
                <span class="text-[#B9B9FF]">Restau</span><span class="text-[#EA7C69]">Sim.</span>
            </p>
            <p class="text-gray-400 mt-3 text-xs sm:text-sm md:text-base">
                © 2025 RestauSim. Training Tool for CLSU DHTM Students.
            </p>
        </div>
    </footer>

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>

</body>

</html>

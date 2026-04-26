<aside id="sidebar" class="fixed top-[58px] left-0 w-20 h-[calc(100vh-58px)] bg-white border-r border-black
           flex flex-col z-40 transition-all duration-300 ease-in-out
           -translate-x-full md:translate-x-0">

    <!-- Navigation -->
    <nav class="flex flex-col mt-4 px-2">

        @php
            $role = Auth::user()->role;
            $sectionUrl = $role === 'student' ? 'student/section' : 'faculty/section';

            $isSection = request()->is("$role/section*")
                || request()->is("$role/activity/*")
                || request()->is("$role/student/*")
                || request()->is('faculty/simulation/all-submissions/*')
                || request()->is('faculty/simulation/review/*')
                || request()->is('faculty/announcement/*');

            $isGrades = request()->is('student/grades');

            $archivedSectionUrl = $role === 'student' ? 'student.archivedSections' : 'faculty.archivedSections';

            $isArchivedSection = request()->is("$role/archivedsection")
                || request()->is("$role/archived/*");

            if ($role === 'faculty') {
                $sections = \App\Models\Section::where('user_id', Auth::id())->get();
            } else {
                $sections = \App\Models\Section::whereHas('members', function ($query) {
                    $query->where('user_id', Auth::id());
                })->get();
            }
        @endphp


        <!-- Home -->
        @if ($role === 'faculty')
            <a href="{{ route('faculty.dashboard') }}" class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                                {{ request()->routeIs('faculty.dashboard')
            ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium'
            : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-house"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Home
                </span>
            </a>
        @endif

        <!-- Sections -->
        @if ($role === 'faculty' || $role === 'student')
            <a href="{{ url($sectionUrl) }}"
                class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                    {{ $isSection ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium' : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-layer-group"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Sections
                </span>
            </a>
        @endif

        {{-- @if ($role === 'faculty')
        <a href="{{ route('faculty.students') }}" class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                        {{ request()->routeIs('faculty.students')
                ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium'
                : 'text-gray-800 hover:bg-gray-100' }}">
            <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                <i class="fa-solid fa-users"></i>
            </span>
            <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                Students
            </span>
        </a>
        @endif --}}
        
         <!--student.my-submissions-->
        
        @if ($role === 'student')
            <a href="{{ route('student.my-submissions') }}"
               class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
               {{ request()->routeIs('student.my-submissions') 
                    ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium' 
                    : 'text-gray-800 hover:bg-gray-100' }}">
            
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-clipboard-check"></i>
                </span>
            
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    My Submissions
                </span>
            </a>
        @endif

        <!-- Grades  add || faculty (for faculty)-->
        @if ($role === 'student')
        {{-- @if ($role === 'student' || $role === 'faculty') --}}
            <a href="{{ auth()->user()->role === 'faculty' ? route('faculty.allGrades') : route('student.grades') }}"
                class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                                    {{ $isGrades ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium' : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-file-lines"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Grades
                </span>
            </a>
        @endif
        

        <!-- Archived Section -->
        @if ($role === 'faculty' || $role === 'student')
            <a href="{{ route($archivedSectionUrl) }}"
                class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                        {{ $isArchivedSection ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium' : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-archive"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Archived Section
                </span>
            </a>
        @endif

        @if ($role === 'admin')
            <!-- Home -->
            <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                                {{ request()->routeIs('admin.dashboard')
            ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium'
            : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-house"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Home
                </span>
            </a>

            <!-- Faculty -->
            <a href="{{ route('admin.faculty') }}" class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                                {{ request()->routeIs('admin.faculty')
            ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium'
            : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Faculty
                </span>
            </a>

            <!-- Students -->
            <a href="{{ route('admin.students') }}" class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                                {{ request()->routeIs('admin.students')
            ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium'
            : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-users"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Students
                </span>
            </a>

            <a href="{{ route('admin.sections') }}" class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                                {{ request()->routeIs('admin.sections')
            ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium'
            : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-layer-group"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Sections
                </span>
            </a>

            <!-- Roles -->
            {{-- <a href="{{ route('faculty.dashboard') }}" class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                                {{ request()->routeIs('faculty.dashboard')
            ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium'
            : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-users-gear"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Roles
                </span>
            </a> --}}
        @endif

        @if ($role === 'superadmin')
            <!-- Home -->
            <a href="{{ route('superadmin.dashboard') }}" class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                                {{ request()->routeIs('superadmin.dashboard')
            ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium'
            : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-house"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Home
                </span>
            </a>

            <!-- Faculty -->
            <a href="{{ route('superadmin.pretest') }}" class="nav-item flex items-center px-3 py-3 mx-2 rounded-full transition-all duration-200 mb-1
                                {{ request()->routeIs('superadmin.pretest')
            ? 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#EA7C69] font-medium'
            : 'text-gray-800 hover:bg-gray-100' }}">
                <span class="flex justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-file-circle-check"></i>
                </span>
                <span class="sidebar-text ml-3 whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Faculty
                </span>
            </a>

        @endif
        
       
    </nav>

    <!-- Logout -->
    <div class="mt-3 px-2 pb-4">
        <form action="{{ route('logout') }}" method="POST"
            class="nav-item flex items-center px-3 py-3 mx-2 mb-4 rounded-full transition-all duration-200 text-gray-500 hover:bg-gray-100">
            @csrf
            <button type="submit" class="flex items-center w-full">
                <span class="flex text-black justify-center items-center w-6 h-6 flex-shrink-0">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </span>
                <span
                    class="sidebar-text ml-3 text-black whitespace-nowrap hidden opacity-0 transition-opacity duration-300">
                    Logout
                </span>
            </button>
        </form>
    </div>
</aside>

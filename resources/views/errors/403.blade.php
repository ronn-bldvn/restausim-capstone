<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Unauthorized Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="mx-auto h-24 w-24 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <h1 class="text-4xl font-bold text-gray-800 mb-4">403</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Access Denied</h2>
            <p class="text-gray-600 mb-8">
                @if(isset($exception) && method_exists($exception, 'getMessage'))
                    {{ $exception->getMessage() }}
                @else
                    You do not have permission to access this page.
                @endif
            </p>

            <div class="space-y-3">
                @auth
                    @if (Auth::user()->role === 'faculty')
                        <a href="{{ route('faculty.dashboard') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('student.section') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                            Go to Section
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        Go to Login
                    </a>
                @endauth

                <button onclick="window.history.back()" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Go Back
                </button>
            </div>
        </div>
    </div>
</body>
</html>

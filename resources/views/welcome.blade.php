<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Repository | QA & Accreditation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="max-w-5xl bg-white shadow-lg rounded-lg p-10 flex items-center">
        <div class="w-1/2">
            <img src="{{ asset('images/bg.jpg') }}" alt="QA System" class="rounded-lg shadow-md">
        </div>
        <div class="w-1/2 text-center px-6">
            <h1 class="text-5xl font-bold text-blue-600">PSU </h1>
            <h2 class="text-1xl font-bold text-blue-600"> Program Accreditation Portal</h2>
            <p class="text-lg text-gray-600 mt-1">
                A cutting-edge web-based system for Quality Assurance & Accreditation Management. 
                Organize and access essential accreditation documents efficiently.
            </p>
            <div class="mt-6 flex justify-center">
                <a href="{{ url('/login') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-200">Login to Portal</a>
            </div>
        </div>
    </div>
</body>
</html>
  
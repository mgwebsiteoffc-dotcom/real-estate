@extends('layouts.guest')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
    <p class="mt-2 text-sm text-gray-600">Sign in to your account to continue</p>
</div>

@if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email Address
        </label>
        <input 
            id="email" 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            autofocus
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="you@example.com"
        >
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password
        </label>
        <input 
            id="password" 
            type="password" 
            name="password" 
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="- - - - - - - - "
        >
    </div>

    <!-- Remember Me -->
    <div class="flex items-center justify-between">
        <label class="flex items-center">
            <input 
                type="checkbox" 
                name="remember"
                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
            >
            <span class="ml-2 text-sm text-gray-600">Remember me</span>
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">
                Forgot password?
            </a>
        @endif
    </div>

    <!-- Submit Button -->
    <button 
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
    >
        Sign In
    </button>

    <!-- Register Link -->
    <p class="text-center text-sm text-gray-600">
        Don't have an account? 
        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
            Sign up
        </a>
    </p>
</form>
@endsection
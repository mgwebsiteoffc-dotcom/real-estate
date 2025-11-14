<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .has-mobile-footer { padding-bottom: 62px !important; }
    </style>
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transition-transform duration-300 lg:translate-x-0 lg:static">
            <div class="flex items-center justify-center h-16 bg-blue-600">
                <span class="text-white text-xl font-bold">Real Estate CRM</span>
            </div>
            <div class="px-4 py-3 bg-gray-50 border-b">
                <p class="text-xs text-gray-500 uppercase">Role</p>
                <p class="text-sm font-semibold text-gray-800 capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
            </div>
            <nav class="mt-6 px-4 overflow-y-auto" style="max-height: calc(100vh - 180px);">
                @if(Auth::user()->role === 'super_admin')
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase mb-2">System Management</p>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-building w-5"></i>
                        <span class="ml-3">Companies</span>
                    </a>
                    <a href="{{ route('packages.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-box w-5"></i>
                        <span class="ml-3">Packages</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-users-cog w-5"></i>
                        <span class="ml-3">All Users</span>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-bell w-5"></i>
                        <span class="ml-3">Notifications</span>
                    </a>
                @elseif(Auth::user()->role === 'company_admin')
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase mb-2">Company Management</p>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    <a href="{{ route('leads.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-users w-5"></i>
                        <span class="ml-3">Leads</span>
                    </a>
                    <a href="{{ route('properties.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-building w-5"></i>
                        <span class="ml-3">Properties</span>
                    </a>
                    <a href="{{ route('projects.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-folder w-5"></i>
                        <span class="ml-3">Projects</span>
                    </a>
                    <a href="{{ route('tasks.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-tasks w-5"></i>
                        <span class="ml-3">Tasks</span>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-bell w-5"></i>
                        <span class="ml-3">Notifications</span>
                    </a>
                    
                    <hr class="my-4">
                     <a href="{{ route('integrations.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
        <i class="fas fa-plug w-5"></i>
        <span class="ml-3">Integrations</span>
    </a>
    <a href="{{ route('automations.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
        <i class="fas fa-robot w-5"></i>
        <span class="ml-3">Automations</span>
    </a>
    <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
        <i class="fas fa-chart-bar w-5"></i>
        <span class="ml-3">Reports</span>
    </a>
                    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-user-cog w-5"></i>
                        <span class="ml-3">Team Members</span>
                    </a>
                    <a href="{{ route('settings.company.edit') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-cog w-5"></i>
                        <span class="ml-3">Company Settings</span>
                    </a>
                    <a href="{{ route('settings.profile.edit') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-user-cog w-5"></i>
                        <span class="ml-3">Profile Settings</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    <a href="{{ route('leads.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-users w-5"></i>
                        <span class="ml-3">My Leads</span>
                    </a>
                    <a href="{{ route('properties.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-building w-5"></i>
                        <span class="ml-3">Properties</span>
                    </a>
                    <a href="{{ route('tasks.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-tasks w-5"></i>
                        <span class="ml-3">My Tasks</span>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-bell w-5"></i>
                        <span class="ml-3">Notifications</span>
                    </a>
                    <a href="{{ route('settings.profile.edit') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-2">
                        <i class="fas fa-user-cog w-5"></i>
                        <span class="ml-3">Profile Settings</span>
                    </a>
                @endif
                {{-- Common Logout Button --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg w-full text-left">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-3">Logout</span>
                    </button>
                </form>
            </nav>
        </aside>
        <!-- Main Content with Header -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 lg:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2" style="display: none;">
                            <a href="{{ route('settings.profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                                <i class="fas fa-user-cog mr-2"></i> My Profile
                            </a>
                            @can('update', Auth::user()->company)
                            <a href="{{ route('settings.company.edit') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                                <i class="fas fa-cog mr-2"></i> Company Settings
                            </a>
                            @endcan
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main section -->
            <main class="flex-1 overflow-y-auto p-6 has-mobile-footer">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Mobile Bottom Nav (visible only on mobile) -->
    <nav class="fixed bottom-0 left-0 right-0 z-50 block lg:hidden bg-white border-t shadow-lg h-[60px]">
        <div class="flex justify-between items-center h-full">
            <a href="{{ route('dashboard') }}" class="flex-1 flex flex-col items-center justify-center h-full {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-600' }}"><i class="fas fa-home text-lg"></i><span class="text-xs mt-1">Dashboard</span></a>
            <a href="{{ route('leads.index') }}" class="flex-1 flex flex-col items-center justify-center h-full {{ request()->routeIs('leads.*') ? 'text-blue-600' : 'text-gray-600' }}"><i class="fas fa-user-friends text-lg"></i><span class="text-xs mt-1">Leads</span></a>
            <a href="{{ route('tasks.index') }}" class="flex-1 flex flex-col items-center justify-center h-full {{ request()->routeIs('tasks.*') ? 'text-blue-600' : 'text-gray-600' }}"><i class="fas fa-tasks text-lg"></i><span class="text-xs mt-1">Tasks</span></a>
            <a href="{{ route('properties.index') }}" class="flex-1 flex flex-col items-center justify-center h-full {{ request()->routeIs('properties.*') ? 'text-blue-600' : 'text-gray-600' }}"><i class="fas fa-building text-lg"></i><span class="text-xs mt-1">Properties</span></a>
        </div>
    </nav>
</body>
</html>

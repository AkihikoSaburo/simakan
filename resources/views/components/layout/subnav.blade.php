@php
    $role = auth()->user()->role;
    $currentRoute = request()->route()->getName();
@endphp

@if($role === 'superadmin')
    <div class="bg-white border-b border-brand-light shadow-sm mb-8">
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8">
                <a href="{{ route('superadmin.dashboard') }}" 
                   class="py-4 px-3 text-sm font-bold border-b-2 transition-all duration-200 flex items-center {{ Str::startsWith($currentRoute, 'superadmin.dashboard') ? 'border-brand-primary text-brand-primary' : 'border-transparent text-brand-gray hover:text-brand-dark' }}">
                    <i class="fa-solid fa-chart-line mr-2"></i> Dashboard
                </a>
                <a href="{{ route('superadmin.admins.index') }}" 
                   class="py-4 px-3 text-sm font-bold border-b-2 transition-all duration-200 flex items-center {{ Str::startsWith($currentRoute, 'superadmin.admins') ? 'border-brand-primary text-brand-primary' : 'border-transparent text-brand-gray hover:text-brand-dark' }}">
                    <i class="fa-solid fa-user-shield mr-2"></i> Manajemen Admin
                </a>
            </div>
        </div>
    </div>
@elseif($role === 'admin')
    <div class="bg-white border-b border-brand-light shadow-sm mb-8">
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('admin.dashboard') }}" 
                   class="py-4 px-3 text-sm font-bold border-b-2 transition-all duration-200 flex items-center {{ Str::startsWith($currentRoute, 'admin.dashboard') ? 'border-brand-primary text-brand-primary' : 'border-transparent text-brand-gray hover:text-brand-dark' }}">
                    <i class="fa-solid fa-chart-line mr-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="py-4 px-3 text-sm font-bold border-b-2 transition-all duration-200 flex items-center {{ Str::startsWith($currentRoute, 'admin.users') ? 'border-brand-primary text-brand-primary' : 'border-transparent text-brand-gray hover:text-brand-dark' }}">
                    <i class="fa-solid fa-users mr-2"></i> Manajemen Akun
                </a>
                <a href="{{ route('admin.bangsals.index') }}" 
                   class="py-4 px-3 text-sm font-bold border-b-2 transition-all duration-200 flex items-center {{ Str::startsWith($currentRoute, 'admin.bangsals') ? 'border-brand-primary text-brand-primary' : 'border-transparent text-brand-gray hover:text-brand-dark' }}">
                    <i class="fa-solid fa-hospital mr-2"></i> Manajemen Bangsal
                </a>
                <a href="{{ route('admin.settings.edit') }}" 
                   class="py-4 px-3 text-sm font-bold border-b-2 transition-all duration-200 flex items-center {{ Str::startsWith($currentRoute, 'admin.settings') ? 'border-brand-primary text-brand-primary' : 'border-transparent text-brand-gray hover:text-brand-dark' }}">
                    <i class="fa-solid fa-gears mr-2"></i> Pengaturan Sistem
                </a>
            </div>
        </div>
    </div>
@endif

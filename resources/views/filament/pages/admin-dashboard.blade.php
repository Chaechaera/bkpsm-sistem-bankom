<x-filament-panels::page>
    <h1>Dashboard</h1>

    @php
        $role = auth()->user()->role ?? null;
    @endphp

    @if ($role === 'superadmin')
        <div class="p-4 bg-red-100 rounded-lg">
            <h2>Super Admin Panel</h2>
            <p>Ini hanya terlihat oleh Superadmin.</p>
        </div>
    @elseif ($role === 'admin')
        <div class="p-4 bg-blue-100 rounded-lg">
            <h2>Admin Panel</h2>
            <p>Ini hanya terlihat oleh Admin.</p>
        </div>
    @endif

    <div class="p-4 bg-gray-100 rounded-lg mt-4">
        <h2>Statistik Umum</h2>
        <p>Ini bisa dilihat oleh semua role yang punya akses.</p>
    </div>
</x-filament-panels::page>

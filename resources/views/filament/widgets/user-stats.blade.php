<x-filament-widgets::widget>
    <x-filament::section>
        <x-filament::card>
            <h2 class="text-lg font-bold">Statistik</h2>

            @if(auth()->user()->role === 'superadmin')
                <p class="text-red-600">📊 Statistik Khusus Superadmin</p>
                <ul class="list-disc ml-5">
                    <li>Total Admin: 12</li>
                    <li>Total User: 350</li>
                </ul>
            @elseif(auth()->user()->role === 'admin')
                <p class="text-blue-600">📊 Statistik Khusus Admin</p>
                <ul class="list-disc ml-5">
                    <li>Total Laporan: 25</li>
                    <li>User Aktif: 120</li>
                </ul>
            @endif

            <p class="mt-4 text-gray-600">📌 Statistik umum yang bisa dilihat semua role.</p>
        </x-filament::card>
    </x-filament::section>
</x-filament-widgets::widget>
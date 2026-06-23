<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak {{ $title }} - Imako Studio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body { background-color: white !important; margin: 0; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .print-hidden { display: none !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            @page { size: auto; margin: 0mm; }
            body { padding: 1.5cm; }
            .invoice-box { border: none !important; box-shadow: none !important; max-width: 100% !important; padding: 0 !important; }
        }
        body { background-color: #f9fafb; color: #1f2937; }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 40px;
            border: 1px solid #eee;
            background-color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border-radius: 8px;
        }
    </style>
</head>
<body class="p-4 md:p-8 text-sm" onload="window.print()">
    <!-- Action buttons (Hidden when printing) -->
    <div class="print-hidden mb-6 flex justify-between max-w-[800px] mx-auto">
        <button onclick="window.close()" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 text-xs font-bold transition shadow-sm">Tutup Tab</button>
        <button onclick="window.print()" class="px-5 py-2.5 bg-brand-primary text-white rounded-xl hover:bg-brand-dark text-xs font-bold transition shadow-[0_2px_10px_rgb(0,0,0,0.1)] flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Dokumen
        </button>
    </div>

    <div class="invoice-box">
        <!-- Header -->
        <table cellpadding="0" cellspacing="0" class="w-full mb-10">
            <tr class="top">
                <td colspan="2" class="pb-6 border-b-2 border-gray-100">
                    <table class="w-full">
                        <tr>
                            <td class="title align-top w-1/2">
                                <img src="{{ asset('images/(watermark) logo imako grey.png') }}" style="max-height: 48px; object-fit: contain;" alt="Imako Studio Logo">
                            </td>
                            <td class="text-right align-top w-1/2">
                                <h1 class="text-2xl md:text-3xl font-black text-brand-dark tracking-tight uppercase">Laporan Keuangan</h1>
                                <p class="text-gray-500 font-bold mt-1">{{ $title }}</p>
                                <p class="text-[10px] text-gray-400 mt-2 font-medium">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Summary section -->
        <div class="grid grid-cols-3 gap-6 mb-12">
            <div class="border-t-[3px] border-green-500 bg-gray-50/50 p-5 rounded-b-xl border-x border-b border-gray-100">
                <p class="text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1">Total Pemasukan</p>
                <h3 class="text-xl md:text-2xl font-black text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="border-t-[3px] border-red-400 bg-gray-50/50 p-5 rounded-b-xl border-x border-b border-gray-100">
                <p class="text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1">Total Pengeluaran</p>
                <h3 class="text-xl md:text-2xl font-black text-red-500">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
            </div>
            <div class="border-t-[3px] border-brand-primary bg-gray-50/50 p-5 rounded-b-xl border-x border-b border-gray-100">
                <p class="text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1">Pendapatan Bersih</p>
                <h3 class="text-xl md:text-2xl font-black {{ $netIncome >= 0 ? 'text-brand-dark' : 'text-red-500' }}">Rp {{ number_format($netIncome, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Pemasukan Table -->
        <div class="mb-12">
            <div class="flex items-center gap-2 mb-4 border-b-2 border-gray-100 pb-2">
                <div class="w-2 h-6 bg-green-500 rounded-full"></div>
                <h3 class="text-sm font-extrabold text-brand-dark uppercase tracking-wider">Rincian Pemasukan</h3>
            </div>
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-400 border-b-2 border-gray-100 text-[11px] uppercase tracking-wider">
                        <th class="py-3 font-bold w-32">Tanggal</th>
                        <th class="py-3 font-bold">Klien</th>
                        <th class="py-3 font-bold">Paket</th>
                        <th class="py-3 font-bold text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($payments as $idx => $payment)
                    <tr class="border-b border-gray-50 {{ $idx % 2 === 0 ? 'bg-white' : 'bg-gray-50/30' }}">
                        <td class="py-3 font-medium">{{ \Carbon\Carbon::parse($payment->verified_at)->translatedFormat('d M Y') }}</td>
                        <td class="py-3 font-bold text-brand-dark">{{ $payment->booking?->user?->name ?? 'Guest' }}</td>
                        <td class="py-3 text-gray-500">{{ $payment->booking?->package?->name ?? 'Kustom' }}</td>
                        <td class="py-3 text-right font-black text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-gray-400 italic font-medium">Tidak ada data pemasukan pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pengeluaran Table -->
        <div class="mb-8" style="page-break-before: always;">
            <div class="flex items-center gap-2 mb-4 border-b-2 border-gray-100 pb-2">
                <div class="w-2 h-6 bg-red-400 rounded-full"></div>
                <h3 class="text-sm font-extrabold text-brand-dark uppercase tracking-wider">Rincian Pengeluaran</h3>
            </div>
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-400 border-b-2 border-gray-100 text-[11px] uppercase tracking-wider">
                        <th class="py-3 font-bold w-32">Tanggal</th>
                        <th class="py-3 font-bold w-48">Kategori</th>
                        <th class="py-3 font-bold">Catatan</th>
                        <th class="py-3 font-bold text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($expenses as $idx => $expense)
                    <tr class="border-b border-gray-50 {{ $idx % 2 === 0 ? 'bg-white' : 'bg-gray-50/30' }}">
                        <td class="py-3 font-medium">{{ \Carbon\Carbon::parse($expense->expense_date)->translatedFormat('d M Y') }}</td>
                        <td class="py-3"><span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-extrabold">{{ $expense->category }}</span></td>
                        <td class="py-3 text-gray-500 text-xs">{{ $expense->notes ?: '-' }}</td>
                        <td class="py-3 text-right font-black text-red-500">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-gray-400 italic font-medium">Tidak ada data pengeluaran pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-16 pt-6 border-t-2 border-dashed border-gray-200 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Imako Studio - Studio Management System</p>
        </div>
    </div>
</body>
</html>

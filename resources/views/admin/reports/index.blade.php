@extends('layouts.admin')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">Laporan</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Laporan Penjualan -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border">
                    <div class="bg-blue-500 text-white px-6 py-4">
                        <h3 class="text-xl font-semibold">Laporan Penjualan</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.reports.sales') }}" method="GET" target="_blank" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Laporan</label>
                                <select name="periode"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    required>
                                    <option value="harian">Harian</option>
                                    <option value="mingguan">Mingguan</option>
                                    <option value="bulanan">Bulanan</option>
                                </select>
                            </div>
                            <input type="hidden" name="format" value="pdf">
                            <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150">
                                Download PDF
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Laporan Keuangan -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border">
                    <div class="bg-green-500 text-white px-6 py-4">
                        <h3 class="text-xl font-semibold">Laporan Keuangan</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.reports.finance') }}" method="GET" target="_blank"
                            class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Laporan</label>
                                <select name="periode"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                                    required>
                                    <option value="harian">Harian</option>
                                    <option value="mingguan">Mingguan</option>
                                    <option value="bulanan">Bulanan</option>
                                </select>
                            </div>
                            <input type="hidden" name="format" value="pdf">
                            <button type="submit"
                                class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150">
                                Download PDF
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Laporan Event -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border">
                    <div class="bg-cyan-500 text-white px-6 py-4">
                        <h3 class="text-xl font-semibold">Laporan Event</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.reports.events') }}" method="GET" target="_blank" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Laporan</label>
                                <select name="periode"
                                    class="w-full rounded-lg border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200"
                                    required>
                                    <option value="harian">Harian</option>
                                    <option value="mingguan">Mingguan</option>
                                    <option value="bulanan">Bulanan</option>
                                </select>
                            </div>
                            <input type="hidden" name="format" value="pdf">
                            <button type="submit"
                                class="w-full bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded transition duration-150">
                                Download PDF
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Laporan Customer -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border">
                    <div class="bg-yellow-500 text-white px-6 py-4">
                        <h3 class="text-xl font-semibold">Laporan Customer</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.reports.customers') }}" method="GET" target="_blank"
                            class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Laporan</label>
                                <select name="periode"
                                    class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200"
                                    required>
                                    <option value="harian">Harian</option>
                                    <option value="mingguan">Mingguan</option>
                                    <option value="bulanan">Bulanan</option>
                                </select>
                            </div>
                            <input type="hidden" name="format" value="pdf">
                            <button type="submit"
                                class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition duration-150">
                                Download PDF
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

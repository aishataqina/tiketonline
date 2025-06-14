@extends('layouts.user')

@section('title', 'Beranda')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4 text-2xl font-bold">Event & Tiket Tersedia</h1>
        <div class="row">
            @forelse($events as $event)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    @include('components.event-card', ['event' => $event])
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Belum ada event tersedia.</div>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>
@endsection

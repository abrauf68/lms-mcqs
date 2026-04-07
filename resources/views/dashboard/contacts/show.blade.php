@extends('layouts.master')

@section('title', __('Show Contact'))

@section('css')
    <style>
        .info-box {
            background: #f9fafb;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #eee;
            height: 100%;
        }

        .info-box label {
            font-size: 12px;
            color: #888;
            margin-bottom: 2px;
            display: block;
        }

        .info-box p {
            font-weight: 500;
            margin: 0;
        }
    </style>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.contacts.index') }}">Contacts</a></li>
    <li class="breadcrumb-item active">Show Contact</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                {{-- HEADER --}}
                <div class="d-flex align-items-center gap-3 mb-4">

                    <div
                        class="avatar avatar-xl bg-label-primary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ti ti-user ti-lg"></i>
                    </div>

                    <div>
                        <h4 class="mb-0">{{ $contact->name }}</h4>
                        <small class="text-muted">{{ $contact->email }}</small>
                    </div>

                    <div class="ms-auto">
                        <span class="badge bg-label-info">
                            {{ $contact->created_at->format('d M Y') }}
                        </span>
                    </div>

                </div>

                {{-- DETAILS --}}
                <div class="row g-4">

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>Phone</label>
                            <p>{{ $contact->phone ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>Interest</label>
                            <p>{{ $contact->interest ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>Source</label>
                            <p>{{ $contact->source ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>Submitted At</label>
                            <p>{{ $contact->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-box">
                            <label>Message</label>
                            <p class="mb-0">{{ $contact->message }}</p>
                        </div>
                    </div>

                </div>

                {{-- ACTION --}}
                <div class="mt-4">
                    <a href="{{ route('dashboard.contacts.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back to Contacts
                    </a>
                </div>

            </div>
        </div>

    </div>
@endsection

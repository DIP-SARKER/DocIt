{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('layouts.guest')

@section('title', 'Confirm Password - DocIt')

@section('content')
    <div class="container" style="max-width: 600px;">
        <!-- Register Card -->
        <div class="card">
            <!-- Register Form -->
            <form class="d-flex flex-column" id="resetPassForm" method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="form-group mb-0">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <div style="position: relative;">
                        <!-- ✅ FIXED NAME (NO SPACES, NO LINE BREAK) -->
                        <input type="password" name="password_confirmation" id="confirmPassword" class="form-control"
                            placeholder="Confirm your password" autocomplete="new-password" required>
                        <button type="button" id="toggleCP" class="i-btn"
                            style="position: absolute; right: 0; top: 0; height: 100%; color: var(--text-muted); border: none; background: transparent; margin-right: 15px; cursor: pointer;">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-muted" id="hintCP"
                        style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                        <i class="fas fa-info-circle"></i>
                        Ensure this matches your chosen password.
                    </p>
                </div>

                @if ($errors->any())
                    <div id="registerError" class="alert alert-danger align-center d-flex gap-2 mt-4">
                        <i class="fas fa-exclamation-circle"></i>
                        <div id="errorText">
                            <ul class="mb-0" style="padding-left: var(--space-sm)">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- ✅ Remove disabled unless JS enables it -->
                <button type="submit" id="registerSubmitBtn" class="btn btn-outline mt-2 form-btn">
                    <i class="fas fa-check"></i>
                    Confirm
                </button>
            </form>
        </div>
    </div>
@endsection

@props(['class' => 'mb-6'])

@if (session('error') || $errors->any() || session('success'))
    <div {{ $attributes->merge(['class' => $class . ' space-y-4']) }}>
        {{-- Flash Session Error --}}
        @if (session('error'))
            <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-900 shadow-xs" role="alert">
                <i class="fa-solid fa-circle-exclamation text-red-500 text-lg mt-0.5 shrink-0"></i>
                <div class="flex-1 text-sm">
                    <p class="font-semibold text-red-800 mb-0.5">Failed to save record</p>
                    <p class="text-red-700 leading-relaxed">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Validation Errors Summary --}}
        @if ($errors->any())
            <div class="flex items-start gap-3 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 shadow-xs" role="alert">
                <i class="fa-solid fa-triangle-exclamation text-rose-500 text-lg mt-0.5 shrink-0"></i>
                <div class="flex-1 text-sm">
                    <p class="font-semibold text-rose-800 mb-1.5">Error encounter ({{ $errors->count() }}):</p>
                    <ul class="space-y-1 text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Flash Session Success --}}
        @if (session('success'))
            <div class="flex items-start gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-xs" role="alert">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                <div class="flex-1 text-sm">
                    <p class="font-semibold text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif
    </div>
@endif

<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $title }}</h5>
        <div class="input-group">
            <x-shared::button-instant-submit :button="$button" />
            <x-shared::select-instant-submit :disabled="$button->selectDisabled">
                <x-slot name="options">
                    <x-shared::select-options :options="$options" />
                </x-slot>
            </x-shared::select-instant-submit>
        </div>
    </div>
</div>

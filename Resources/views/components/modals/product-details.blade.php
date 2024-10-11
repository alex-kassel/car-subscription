@props(['button', 'btnClass'])
@php($id = 'productDetails')

<x-shared::modal id="{{ $id }}" btnClass="{{ $btnClass }}" dialogClass="modal-cm modal-dialog-scrollable">
    <x-slot name="button">{{ $button }}</x-slot>
    <x-slot name="label">Details</x-slot>
    <x-slot name="bodyContent">{{-- filled by JS --}}</x-slot>
    <x-slot name="dismissButton">Schließen</x-slot>
</x-shared::modal>

@pushonce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-bs-target="#{{ $id }}"]').forEach(button => {
                button.addEventListener('click', function() {
                    const modalBody = document.querySelector('#{{ $id }} .modal-body');
                    const card = this.closest('.card');
                    const cardContent = card.cloneNode(true);

                    cardContent.querySelectorAll('.button-container').forEach(el => {
                        el.remove();
                    });

                    cardContent.querySelectorAll('.info-content').forEach(el => {
                        el.classList.add('border', 'bg-light', 'shadow', 'rounded');
                    });


                    cardContent.querySelectorAll('div').forEach(el => {
                        el.classList.remove('d-none', 'row', 'info-block');

                        Object.assign(el.style, {
                            overflow: '',
                            overflowX: '',
                            overflowY: '',
                            maxHeight: ''
                        });
                    });

                    modalBody.innerHTML = '';
                    modalBody.appendChild(cardContent);
                });
            });
        });
    </script>
@endpushonce

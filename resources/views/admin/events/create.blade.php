<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Event</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                    @include('admin.events._form', ['event' => null, 'kategoris' => $kategoris])
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

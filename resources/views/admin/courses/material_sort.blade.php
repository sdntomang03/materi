@extends('layouts.app')

@section('title', 'Atur Urutan Materi - ' . $chapter->name)

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6">

    <!-- Tombol Kembali -->
    <a href="{{ url()->previous() }}"
        class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors group mb-8">
        <div
            class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-3 group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
            <i class="fas fa-arrow-left text-[10px]"></i>
        </div>
        Kembali ke Konten Bab
    </a>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 mb-2">Atur Urutan Materi</h1>
        <p class="text-slate-500">Bab: <span class="font-bold text-indigo-600">{{ $chapter->name }}</span></p>
    </div>

    <!-- Alpine.js Component -->
    <div x-data="materialSorter()" class="bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-200 shadow-sm">
        <p class="text-sm text-slate-500 mb-6 font-medium bg-slate-50 p-4 rounded-xl border border-slate-100">
            <i class="fas fa-info-circle text-indigo-500 mr-2"></i> Gunakan tombol panah di sebelah kanan untuk
            menaikkan atau menurunkan posisi materi. Klik <b>Simpan Urutan</b> jika sudah selesai.
        </p>

        <!-- Daftar Materi Interaktif -->
        <div class="space-y-3 mb-8">
            <template x-for="(mat, idx) in materials" :key="mat.id">
                <div
                    class="flex items-center justify-between p-4 bg-white border-2 border-slate-100 rounded-2xl hover:border-indigo-200 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-sm"
                            x-text="idx + 1"></div>
                        <div>
                            <h3 class="font-bold text-slate-700 text-lg" x-text="mat.title"></h3>
                            <p class="text-xs text-slate-400">Urutan Asli: <span x-text="mat.order_num"></span></p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <!-- Pindah Ke Atas -->
                        <button type="button" @click="moveUp(idx)" :disabled="idx === 0"
                            class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 disabled:opacity-30 disabled:cursor-not-allowed transition-all shadow-sm">
                            <i class="fas fa-chevron-up"></i>
                        </button>
                        <!-- Pindah Ke Bawah -->
                        <button type="button" @click="moveDown(idx)" :disabled="idx === materials.length - 1"
                            class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 disabled:opacity-30 disabled:cursor-not-allowed transition-all shadow-sm">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
            </template>

            <div x-show="materials.length === 0"
                class="text-center p-8 text-slate-400 border-2 border-dashed border-slate-200 rounded-2xl">
                Belum ada materi di bab ini.
            </div>
        </div>

        <div class="flex justify-end pt-6 border-t border-slate-100">
            <button type="button" @click="saveOrder()" :disabled="isSaving || materials.length === 0"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-3 rounded-xl shadow-lg transition-transform hover:-translate-y-1 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                <i class="fas" :class="isSaving ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Urutan Baru'"></span>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
    Alpine.data('materialSorter', () => ({
        materials: @json($chapter->materials->sortBy('order_num')->values()),
        isSaving: false,

        moveUp(idx) {
            if (idx > 0) {
                let temp = this.materials[idx];
                this.materials[idx] = this.materials[idx - 1];
                this.materials[idx - 1] = temp;
            }
        },

        moveDown(idx) {
            if (idx < this.materials.length - 1) {
                let temp = this.materials[idx];
                this.materials[idx] = this.materials[idx + 1];
                this.materials[idx + 1] = temp;
            }
        },

        async saveOrder() {
            this.isSaving = true;
            const orderedIds = this.materials.map(m => m.id);

            const metaToken = document.querySelector('meta[name="csrf-token"]');
            const csrf = metaToken ? metaToken.getAttribute('content') : '{{ csrf_token() }}';

            try {
                const response = await fetch('{{ route('admin.courses.material.reorder', $chapter->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ordered_ids: orderedIds })
                });

                const data = await response.json();
                if (data.success) {
                    alert('Urutan berhasil diperbarui!');
                    // Kembali ke halaman konten bab
                    window.location.href = "{{ url()->previous() }}";
                } else {
                    alert('Gagal memperbarui urutan.');
                }
            } catch (error) {
                alert('Terjadi kesalahan koneksi.');
            } finally {
                this.isSaving = false;
            }
        }
    }));
});
</script>
@endsection
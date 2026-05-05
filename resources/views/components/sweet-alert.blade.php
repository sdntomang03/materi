@if(session('success') || $errors->any())
<!-- Load SweetAlert2 via CDN (Hapus baris ini jika sudah memasangnya di layout utama) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

            // 1. Notifikasi Sukses
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{!! addslashes(session('success')) !!}",
                    confirmButtonColor: '#10b981', // Menggunakan warna emerald-500 Tailwind
                    confirmButtonText: 'Tutup'
                });
            @endif

            // 2. Notifikasi Error Validasi
            @if($errors->any())
                // Susun pesan error ke dalam bentuk list HTML
                let errorMessages = '<ul style="text-align: left; list-style-type: disc; margin-left: 20px; color: #b91c1c; font-size: 0.875rem;">';
                @foreach ($errors->all() as $error)
                    errorMessages += '<li>{!! addslashes($error) !!}</li>';
                @endforeach
                errorMessages += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Terdapat Kesalahan!',
                    html: errorMessages,
                    confirmButtonColor: '#ef4444', // Menggunakan warna red-500 Tailwind
                    confirmButtonText: 'Perbaiki'
                });
            @endif

        });
</script>
@endif
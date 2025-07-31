<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Landing Page Kopma UGM</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans">

  <!-- HERO SECTION -->
  <section class="relative bg-[#4fb476] min-h-screen flex flex-col justify-center px-8 py-20">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 items-center">
      
      <!-- Text Block -->
      <div>
        <!-- Editable by Admin -->
        <h1 class="text-5xl md:text-6xl font-bold text-black font-montserrat leading-tight mb-6">
          <!-- @can('admin') : tampilkan form untuk edit -->
          Welcome to <br/>Kopma UGM!
        </h1>
        <p class="text-lg text-black mb-8">
          <!-- @can('admin') : tampilkan textarea -->
          Koperasi mahasiswa Kopma Universitas Gadjah Mada adalah wadah bagi mahasiswa untuk menumbuhkan jiwa kewirausahaan melalui koperasi yang inklusif.
        </p>
        <a href="#" class="inline-block bg-[#066A31] text-white text-xl px-10 py-3 rounded-full shadow hover:bg-green-800 transition">Join Now</a>
      </div>

      <!-- Image Block (Editable by Admin) -->
      <div>
        <img src="https://placehold.co/500x500" alt="Hero Image" class="rounded-xl shadow-lg w-full max-w-md mx-auto" />
      </div>
    </div>
  </section>

  <!-- PENGENALAN WEBSITE -->
  <section class="bg-[#F3FBF6] py-20 px-10">
    <div class="max-w-5xl mx-auto text-center">
      <h2 class="text-4xl font-bold font-montserrat mb-6">Pengenalan Website</h2>
      <!-- @can('admin') : tampilkan form -->
      <p class="text-lg text-gray-700 leading-relaxed mb-8">
        Website resmi Kopma UGM adalah platform digital untuk mengenal, mendaftar, dan berpartisipasi dalam kegiatan koperasi mahasiswa. Semua informasi terpusat di sini, mulai dari pendaftaran hingga akses layanan.
      </p>
    </div>
  </section>

  <!-- TATA CARA PENDAFTARAN -->
  <section class="bg-white py-20 px-10">
    <div class="max-w-5xl mx-auto text-center">
      <h2 class="text-4xl font-bold font-montserrat mb-10">Tata Cara Pendaftaran</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
        <!-- Gambar Editable by Admin -->
        <img src="https://placehold.co/300x200" alt="Step 1" class="rounded-xl shadow">
        <img src="https://placehold.co/300x200" alt="Step 2" class="rounded-xl shadow">
        <img src="https://placehold.co/300x200" alt="Step 3" class="rounded-xl shadow">
      </div>
    </div>
  </section>

  <!-- KEUNTUNGAN MENJADI MEMBER -->
  <section class="bg-[#F3FBF6] py-20 px-10">
    <div class="max-w-6xl mx-auto text-center">
      <h2 class="text-4xl font-bold font-montserrat mb-10">Keuntungan Menjadi Member Kopma</h2>
      <div class="grid md:grid-cols-3 gap-10 text-left">
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-xl font-bold mb-2 text-[#066A31]">Akses Keuntungan Usaha</h3>
          <p class="text-gray-700">Dapatkan pembagian SHU setiap akhir tahun sebagai bagian dari koperasi aktif.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-xl font-bold mb-2 text-[#066A31]">Peluang Magang & Wirausaha</h3>
          <p class="text-gray-700">Jaringan kerja dan pengembangan bisnis sejak mahasiswa.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-xl font-bold mb-2 text-[#066A31]">Akses Eksklusif Event & Pelatihan</h3>
          <p class="text-gray-700">Ikuti pelatihan, seminar, dan kegiatan anggota secara eksklusif.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER (Editable by Admin) -->
  <footer class="bg-[#066A31] text-white py-16 px-10 rounded-t-[4rem] mt-10">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10">
      <!-- Alamat -->
      <div>
        <h4 class="text-2xl font-bold mb-3">Alamat</h4>
        <!-- @can('admin') : form input -->
        <p>Jl. Cik Di Tiro No.14, Terban<br/>Gondokusuman, Yogyakarta 55223</p>
      </div>

      <!-- Maps -->
      <div>
        <h4 class="text-2xl font-bold mb-3">Lokasi Kami</h4>
        <!-- @can('admin') : form iframe url -->
        <iframe class="w-full h-40 rounded-lg" src="https://www.google.com/maps/embed?pb=!1m18..." allowfullscreen loading="lazy"></iframe>
      </div>

      <!-- Dukungan Pelayanan -->
      <div>
        <h4 class="text-2xl font-bold mb-3">Dukungan Pelayanan</h4>
        <!-- @can('admin') : form social media links -->
        <div class="flex space-x-4 mt-4">
          <a href="#" target="_blank"><img src="https://placehold.co/40x40?text=IG" alt="Instagram" class="rounded-full" /></a>
          <a href="#" target="_blank"><img src="https://placehold.co/40x40?text=YT" alt="YouTube" class="rounded-full" /></a>
          <a href="#" target="_blank"><img src="https://placehold.co/40x40?text=TW" alt="Twitter" class="rounded-full" /></a>
          <a href="#" target="_blank"><img src="https://placehold.co/40x40?text=LI" alt="LinkedIn" class="rounded-full" /></a>
        </div>
      </div>
    </div>
    <div class="text-center mt-10 text-sm text-white/70">
      &copy; 2025 Koperasi Mahasiswa UGM. All rights reserved.
    </div>
  </footer>

</body>
</html>

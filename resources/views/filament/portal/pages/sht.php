<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Landing Page - Kopma UGM</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans">

  <!-- HERO SECTION -->
  <section class="relative bg-[#4fb476] overflow-hidden h-screen">
    <!-- Decorative Background Image -->
    <img src="https://placehold.co/580x725" alt="Background Hero" class="absolute right-0 top-0 h-[725px] opacity-30" />

    <!-- Navbar -->
    <div class="flex justify-between items-center px-20 pt-10">
      <img src="{{ asset('images/kopma-brand.png') }}" alt="Kopma UGM Logo" class="h-8">
      <nav class="flex space-x-10 text-black font-bold text-lg">
        <a href="#">Home</a>
        <a href="#">Service</a>
        <a href="#">Blog</a>
        <a href="#">FAQ</a>
        <a href="#">Shop</a>
      </nav>
    </div>

    <!-- Content -->
    <div class="px-20 mt-20 max-w-3xl">
      <h1 class="text-6xl font-bold font-montserrat text-black mb-6 leading-tight">Welcome to<br>Kopma UGM!</h1>
      <p class="text-xl text-black mb-8 leading-relaxed">
        Koperasi mahasiswa Kopma Universitas Gadjah Mada<br/>
        merupakan wadah bagi mahasiswa untuk menanamkan jiwa<br/>
        wirausaha dalam bentuk perkoperasian yang inklusif.
      </p>
      <a href="#" class="inline-block bg-[#066A31] text-white text-xl px-10 py-3 rounded-full shadow hover:bg-green-800 transition">Join Now</a>
    </div>

    <!-- Hero Illustration -->
    <img src="https://placehold.co/658x822" class="absolute top-[160px] left-[720px] shadow-lg" alt="Illustration">
  </section>

  <!-- ABOUT SECTION -->
  <section class="bg-[#F3FBF6] py-20 relative">
    <div class="text-center text-5xl font-bold font-montserrat mb-16">Tentang Kami</div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 max-w-6xl mx-auto px-8">
      <!-- Sejarah Card -->
      <div class="bg-[#70917e99] rounded-[3rem] p-10">
        <h2 class="text-[#066A31] text-4xl font-bold mb-6">Sejarah</h2>
        <p class="text-lg leading-7">
          Lahirnya Koperasi “Kopma UGM” berawal dari gagasan perlunya pemenuhan kebutuhan kesejahteraan mahasiswa melalui unit usaha yang dikelola oleh mahasiswa sendiri. Keinginan tersebut dikembangkan pada rapat persiapan tanggal 12 Maret 1982 yang dihadiri oleh utusan dari Senat Mahasiswa Fakultas di lingkungan UGM. Dalam rapat tersebut sebanyak 43 peserta dari 52 peserta yang hadir langsung mencatatkan diri menjadi anggota sekaligus sebagai pendiri.
        </p>
        <a href="#" class="mt-6 inline-block bg-[#066A31] text-white text-lg font-bold px-6 py-2 rounded-full shadow hover:bg-green-800 transition">Read More</a>
      </div>

      <!-- Gelora Sinergi Card -->
      <div class="bg-[#70917e99] rounded-[3rem] p-10">
        <h2 class="text-[#066A31] text-4xl font-bold mb-6">Gelora Sinergi</h2>
        <p class="text-lg leading-7">
          Merupakan budaya organisasi yang diurung kepengurusan Kopma UGM 2024/2025, GELORA adalah akronim dari Gesit, Logis, dan Kekeluargaan yang menjadi dasar tindakan dalam setiap langkah yang dilakukan oleh elemen Koperasi Mahasiswa UGM. Sinergi yang melibatkan setiap unsur dalam koperasi membawa kemajuan koperasi ke arah yang lebih baik.
        </p>
        <a href="#" class="mt-6 inline-block bg-[#066A31] text-white text-lg font-bold px-6 py-2 rounded-full shadow hover:bg-green-800 transition">Read More</a>
      </div>
    </div>
  </section>

  <!-- GALLERY SECTION -->
  <section class="bg-[#F3FBF6] py-20">
    <div class="text-center text-5xl font-bold font-montserrat mb-16">Galeri Kegiatan</div>
    <div class="flex flex-wrap justify-center gap-10 px-8">
      <img src="https://placehold.co/514x376" class="rounded-2xl shadow-lg" alt="Galeri 1" />
      <img src="https://placehold.co/502x376" class="rounded-2xl shadow-lg" alt="Galeri 2" />
      <img src="https://placehold.co/545x408" class="rounded-2xl shadow-lg" alt="Galeri 3" />
    </div>
  </section>

  <!-- BLOG + FOOTER SECTION -->
  <section class="bg-[#F3FBF6] py-20 relative">
    <div class="max-w-7xl mx-auto px-8">
      <h2 class="text-5xl font-bold text-center mb-12">Postingan Terkini</h2>

      <div class="bg-[#70917e99] rounded-[3rem] p-10 flex flex-col lg:flex-row gap-10">
        <img src="https://placehold.co/318x195" class="rounded-xl shadow" alt="Blog Image" />
        <div>
          <h3 class="text-2xl font-bold text-white mb-4">
            Dari Stan Winter sampai Maskot Aciko: Kopma UGM Curi Perhatian di Gelex 2024
          </h3>
          <p class="text-black text-lg mb-4">
            Gelanggang Expo merupakan agenda tahunan yang diselenggarakan oleh Universitas Gadjah Mada (UGM) dengan tujuan memperkenalkan berbagai Unit Kegiatan Mahasiswa (UKM) kepada mahasiswa baru...
          </p>
          <a href="#" class="inline-block bg-white text-[#066A31] text-lg font-bold px-6 py-2 rounded-full shadow hover:bg-gray-200 transition">Read More</a>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="mt-24 bg-[#066A31] py-10 rounded-t-[4rem] text-white">
      <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 px-8">
        <div>
          <h4 class="text-2xl font-bold mb-3">Alamat</h4>
          <p>Jl. Cik Di Tiro No.14, Terban, Kec. Gondokusuman<br/>Yogyakarta, 55223</p>
        </div>
        <div><!DOCTYPE html>
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

          <h4 class="text-2xl font-bold mb-3">Dukungan Pelayanan</h4>
          <p>Email: <a href="mailto:info@kopma-ugm.net" class="underline">info@kopma-ugm.net</a><br/>
          Phone: (0274) 565774</p>
        </div>
      </div>
      <div class="mt-8 text-center text-sm text-white/70">
        &copy; 2025 Koperasi Mahasiswa UGM. All rights reserved.
      </div>
    </footer>
  </section>

</body>
</html>

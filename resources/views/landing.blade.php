<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>Portal Kopma UGM</title>


    <!-- Font import: Poppins from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

    <!-- Link CSS eksternal -->
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/unicons.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
<!-- Tabler Icons Webfont -->
<link href="https://unpkg.com/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">

<!-- Tabler Icons CDN -->
<link href="https://unpkg.com/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">


    <!-- MAIN STYLE -->
     <link rel="stylesheet" href="css/tooplate-style.css">

    <style>
      /* Minimal inline fallback CSS untuk demo */
      body {
        margin: 0;
        background-color: #f1f9f2;
        font-family: 'Poppins', sans-serif;
        color: #000;
      }
      a {
        text-decoration: none;
        color: #000;
      }
      a:hover {
        text-decoration: underline;
      }
      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
      }

      

      header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        padding-bottom: 30px;
      }
      header .logo {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 18px;
        color: #004820;
      }
      header .logo svg {
        width: 32px;
        height: 32px;
      }
      nav a {
        margin-left: 30px;
        font-weight: 600;
        font-size: 16px;
        color: #000;
      }
      nav a:hover,
      nav a.active {
        text-decoration: underline;
        color: #000;
      }
      nav .search-icon {
        margin-left: 30px;
        cursor: pointer;
        width: 18px;
        height: 18px;
        stroke: #000;
      }

      /* Hero Section */
      .hero {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 100px;
        gap: 15px;
      }
      .hero-left {
        flex: 1 1 400px;
        max-width: 600px;
        padding-right: 20px;
      }
      .hero-left h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 25px;
        color: #000;
      }
      .hero-left p {
        font-size: 16px;
        line-height: 1.5;
        margin-bottom: 25px;
        max-width: 480px;
        color: #000;
      }
      .btn-join {
        background-color: #146936;
        color: #fff;
        padding: 12px 32px;
        font-weight: 600;
        font-size: 16px;
        border-radius: 24px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.3s ease;
      }

      
      .btn-join:hover {
        background-color: #095321;
      }
      .btn-join svg {
        stroke: #fff;
        width: 16px;
        height: 16px;
      }

      .hero-right {
        flex: 1 1 400px;
        max-width: 480px;
        display: grid;
        grid-template-columns: auto auto;
        gap: 15px 10px;
        justify-content: flex-end;
      }

      .hero-stat {
        background-color: #146936;
        border-radius: 16px;
        color: #fff;
        padding: 20px 24px;
        font-weight: 700;
        font-size: 26px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: start;
        position: relative;
        min-width: 120px;
        height: 120px;
      }
      .hero-stat small {
        font-weight: 400;
        font-size: 14px;
        opacity: 0.7;
        margin-top: 4px;
      }
      .hero-stat .arrow-up {
        position: absolute;
        top: 12px;
        left: 12px;
        stroke: #a4d4af;
        opacity: 0.5;
        width: 24px;
        height: 24px;
      }
      .hero-stat-inv {
        background-color: #335f4d;
        border-radius: 12px;
        color: #8bb59c;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        cursor: default;
        user-select: none;
      }
      .hero-stat-inv svg {
        width: 20px;
        height: 20px;
        stroke: #8bb59c;
      }
      .hero-image-small,
      .hero-image-big {
        border-radius: 16px;
        overflow: hidden;
        height: 120px;
        object-fit: cover;
        box-shadow: 0 8px 15px rgb(0 0 0 / 0.05);
        position: relative;
        width: 100%;
      }
      .hero-image-small {
        grid-column: 2;
        height: 120px;
      }
      .hero-image-big {
        grid-column: 1 / span 2;
        height: 140px;
      }
      .hero-image-small img,
      .hero-image-big img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
      }

      /* Decorative circles and shapes */
      .decorative-circle-big {
        position: absolute;
        width: 220px;
        height: 220px;
        border: 40px solid #a9c3b2;
        border-radius: 50%;
        top: 90px;
        right: 20px;
        z-index: -1;
        opacity: 0.3;
      }
      .decorative-arc {
        position: absolute;
        width: 80px;
        height: 80px;
        border: 20px solid #a9c3b2;
        border-left-color: transparent;
        border-bottom-color: transparent;
        border-radius: 50% 0 0 0;
        bottom: 100px;
        left: 10px;
        opacity: 0.3;
      }

      /* Section Tentang Kami */
      section.tentang-kami {
        max-width: 1000px;
        margin: 0 auto 100px;
        padding: 0 20px;
      }
      section h2.title {
        font-size: 36px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 40px;
      }
      .tentang-content {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        justify-content: center;
      }
      .tentang-block {
        flex: 1 1 440px;
        min-width: 300px;
        max-width: 480px;
      }
      .tentang-block .image-placeholder {
        width: 100%;
        height: 160px;
        background-color: #a9c3b2;
        border-radius: 20px;
        margin-bottom: 20px;
      }
      .tentang-block h3 {
        color: #146936;
        font-weight: 700;
        font-size: 22px;
        margin-bottom: 10px;
      }
      .tentang-block p {
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 15px;
        color: #000;
      }
      .btn-readmore {
        background-color: #146936;
        color: #fff;
        padding: 10px 28px;
        font-weight: 600;
        border-radius: 22px;
        font-size: 14px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background-color 0.3s ease;
      }
      .btn-readmore:hover {
        background-color: #0a3c23;
      }
      .btn-readmore svg {
        stroke: #fff;
        width: 16px;
        height: 16px;
      }

      /* Gelora Sinergi section has text on left and placeholder on right reversed layout on smaller screen */
      .gelora-sinergi {
        margin-top: 80px;
      }

      /* Section Card Components Visi Misi Tujuan */
      .card-row {
        max-width: 1000px;
        margin: 0 auto 120px;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
      }
      .card {
        background-color: #146936;
        border-radius: 20px;
        overflow: hidden;
        width: 30%;
        min-width: 280px;
        color: #fff;
        font-weight: 600;
        font-size: 22px;
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 16px;
        transition: background-color 0.3s ease;
      }
      .card:hover {
        background-color: #0a3c23;
      }
      .card img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
        border-radius: 20px;
        filter: brightness(0.4);
        transition: filter 0.3s ease;
      }
      .card:hover img {
        filter: brightness(0.7);
      }
      .card span {
        position: relative;
        z-index: 2;
      }
      .card .arrow-right {
        font-size: 24px;
        position: absolute;
        right: 12px;
        bottom: 14px;
        stroke: #fff;
        width: 24px;
        height: 24px;
      }

      /* Galeri Kegiatan */
      .gallery-section {
        max-width: 1000px;
        margin: 0 auto 100px;
        padding: 0 20px;
      }
      .gallery-section h2.title {
        font-size: 36px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 40px;
      }
      .gallery-container {
        display: flex;
        justify-content: center;
      }
      .gallery-image-wrapper {
        position: relative;
        width: 500px;
        max-width: 100%;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 15px rgb(0 0 0 / 0.1);
      }
      .gallery-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: block;
      }

      /* Postingan Terkini */
      .postingan-section {
        max-width: 1000px;
        margin: 0 auto 100px;
        padding: 0 20px;
        display: flex;
        align-items: center;
        gap: 30px;
      }
      .postingan-text {
        flex: 1 1 300px;
        font-weight: 700;
        font-size: 32px;
      }
      .postingan-card {
        flex: 2 1 600px;
        background-color: #a9c3b2;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 8px 15px rgb(0 0 0 / 0.05);
        display: flex;
        gap: 20px;
        align-items: flex-start;
      }
      .postingan-card img {
        width: 140px;
        height: 100px;
        object-fit: cover;
        border-radius: 16px;
        flex-shrink: 0;
      }
      .postingan-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }
      .postingan-title {
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 8px;
      }
      .postingan-desc {
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 12px;
        color: #000;
      }
      .btn-readmore-post {
        background-color: #146936;
        color: #fff;
        padding: 8px 20px;
        font-weight: 600;
        border-radius: 22px;
        font-size: 14px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
      }
      .btn-readmore-post:hover {
        background-color: #0a3c23;
      }
      .btn-readmore-post svg {
        stroke: #fff;
        width: 16px;
        height: 16px;
      }

      /* Find Us Section */
      footer.find-us {
        background-color: #146936;
        color: white;
        padding: 40px 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
      }
      footer.find-us h2 {
        width: 100%;
        text-align: center;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 30px;
      }
      .map-container {
        flex: 1 1 320px;
        min-width: 280px;
        max-width: 400px;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 18px rgb(0 0 0 / 0.4);
        position: relative;
      }
      .map-container iframe {
        width: 100%;
        height: 220px;
        border: 0;
      }
      .contact-info {
        flex: 1 1 320px;
        min-width: 280px;
        max-width: 400px;
        display: flex;
        flex-direction: column;
        gap: 24px;
        justify-content: center;
      }
      .contact-block {
        display: flex;
        gap: 12px;
        align-items: center;
      }
      .contact-block svg {
        fill: #146936;
        background: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        padding: 4px;
      }
      .contact-text {
        font-size: 14px;
        max-width: 80%;
      }
      
      .contact-text a {
        color: #65b876;
        font-weight: 600;
      }
      /* Social Icons
      .social-icons {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        gap: 18px;
        padding-top: 12px;
      } */
      /* .social-icons a svg {
        fill: white;
        width: 28px;
        height: 28px;
        transition: fill 0.3s ease;
      }
      .social-icons a:hover svg {
        fill: #a9c3b2;
      } */

      .social-icons {
  display: flex;
  gap: 16px;
}

.social-icons a .icon {
  width: 24px;
  height: 24px;
  color: white;
  transition: color 0.3s ease, fill 0.3s ease;
}
a {
  pointer-events: auto;
}

.social-icons a:hover .icon {
  color: #a9c3b2;
  fill: #a9c3b2;
}


      
      /* Responsive */
      @media (max-width: 920px) {
        .hero {
          flex-direction: column;
          padding-bottom: 50px;
        }
        header nav {
          display: none;
        }
        .tentang-content {
          flex-direction: column;
        }
        .card-row {
          flex-direction: column;
          gap: 40px;
          align-items: center;
        }
        .card {
          width: 80%;
        }
        .postingan-section {
          flex-direction: column;
          gap: 20px;
        }
        footer.find-us {
          flex-direction: column;
        }


      }

    </style>
</head>
<body>
        <!-- Navigasi Bar -->
    <header class="container" role="banner">
        <div class="logo" aria-label="KOPMA UGM logo" style="display: flex; align-items: center; gap: 6px;">
  <img src="/images/kopma-brand.png" alt="Logo KOPMA UGM" width="210" height="30">
 
</div>


        <nav role="navigation" aria-label="Main navigation navigation links">
          <a href="#" class="active" aria-current="page">Portal</a>
          <a href="#project">Tentang Kami</a>
          <a href="#resume">Alur Pendaftaran</a>
          <a href="#findUsTitle">Kontak</a>
          <a href="/portal/login">Login</a>
          <a href="#" aria-label="Search icon">
            <!-- <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <circle cx="11" cy="11" r="7"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg> -->
          </a>
        </nav>
    </header>

    <!-- Jumbotron Website -->
    <section class="container hero" aria-label="Welcome to Kopma UGM">
      <div class="hero-left">
        <h1>Selamat Datang di Portal Keanggotaan KOPMA UGM!</h1>
        <p>Portal resmi Kopma UGM untuk mengakses layanan keanggotaan, informasi kegiatan, serta transaksi Anggota KOPMA UGM.</p>
        <button class="btn-join" aria-label="Join Now button" onclick="location.href='/portal/register'">
          Join Now
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
           stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
           <line x1="5" y1="12" x2="19" y2="12"></line>
           <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
    
        </button>
      </div>
      <div class="hero-right" aria-hidden="true">
  <!-- Kotak kosong untuk ikon membership -->
  <div class="membership-box-placeholder"></div>
</div>
      <div class="hero-right" aria-hidden="true">
       
      </div>
      <div class="decorative-circle-big" aria-hidden="true"></div>
      <div class="decorative-arc" aria-hidden="true"></div> 
    </section>

    <section class="project py-5" id="project">
  <div class="container">
    <div class="row">
      <div class="col-lg-11 text-center mx-auto col-12">
        <div class="col-lg-8 mx-auto">
          <h2>Tentang Kami</h2>
        </div>
        

        <!-- Carousel -->
        <div class="owl-carousel owl-theme">
          <div class="item">
            <div class="project-info">
              <img src="images/project/1.jpg" class="img-fluid" alt="project image 1">
            </div>
          </div>
          <div class="item">
            <div class="project-info">
              <img src="images/project/2.jpg" class="img-fluid" alt="project image 2">
            </div>
          </div>
          <div class="item">
            <div class="project-info">
              <img src="images/project/3.jpg" class="img-fluid" alt="project image 3">
            </div>
          </div>
          <div class="item">
            <div class="project-info">
              <img src="images/project/4.jpg" class="img-fluid" alt="project image 4">
            </div>
          </div>
          
        </div>
        <!-- End Carousel -->
        <div> 
            <p> Kopma UGM merupakan koperasi mahasiswa yang hadir sebagai wadah pengembangan jiwa kewirausahaan, kemandirian ekonomi, dan semangat kolaborasi di kalangan mahasiswa Universitas Gadjah Mada. Didirikan dan dikelola sepenuhnya oleh mahasiswa, Kopma UGM tidak hanya menjadi tempat berlatih berwirausaha, tetapi juga ruang belajar bersama untuk membangun etos kerja, tanggung jawab sosial, dan solidaritas antarmahasiswa. </p>
            <p></p>
            <p>Melalui berbagai program usaha dan pelatihan, kami mendorong anggota untuk aktif berkontribusi, berinovasi, dan tumbuh bersama dalam ekosistem koperasi yang inklusif dan berkelanjutan. Semangat "dari mahasiswa, oleh mahasiswa, untuk mahasiswa" menjadi landasan kami dalam menciptakan generasi muda yang mandiri secara ekonomi serta mampu bersaing di dunia profesional dan kewirausahaan.</p>
    </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
    <section class="resume py-5 d-lg-flex justify-content-center align-items-center" id="resume">
        <div class="container">
            <!-- Judul Section -->
    <div class="row mb-5">
      <div class="col-lg-8 mx-auto text-center">
        <h2>Alur Pendaftaran</h2>
        <p class="text-muted">Langkah-langkah untuk menjadi anggota Kopma UGM</p>
      </div>
    </div>

                <div class="col-lg-6 col-12">
                  <h3 class="mb-4">Melalui Website</h3>

                    <div class="timeline">
                        <div class="timeline-wrapper">
                             <div class="timeline-yr">
                                  <span>01.</span>
                             </div>
                             <div class="timeline-info">
                                
                                  <h3><span>Kunjungi Website Portal Kopma UGM</span></h3>
                                  <p>Klik Join Now atau klik tombol Login yang ada di halaman atas</p>
                                  <img src="images/alurpendaftaran/1.jpg" alt="Langkah 1" class="img-fluid mb-3" style="max-width: 600px;">
                             </div>
                        </div>

                        <div class="timeline-wrapper">
                            <div class="timeline-yr">
                                <span>02.</span>
                            </div>
                            <div class="timeline-info">
                                <h3><span>Registrasi</span></h3>
                                <p>Setelah itu, calon anggota dapat membuat akun baru terlebih dahulu</p>
                                <img src="images/alurpendaftaran/2.jpg" alt="Langkah 2" class="img-fluid mb-3" style="max-width: 600px;">
                            </div>
                        </div>

                        <div class="timeline-wrapper">
                            <div class="timeline-yr">
                                <span>03.</span>
                            </div>
                            <div class="timeline-info">
                                <h3><span>Data Diri</h3>
                                <p>Masukkan data diri secara lengkap dan benar</p>
                                <img src="images/alurpendaftaran/3.jpg" alt="Langkah 3" class="img-fluid mb-3" style="max-width: 600px;">
                            </div>
                        </div>
                        
                        <div class="timeline-wrapper">
                            <div class="timeline-yr">
                                <span>04.</span>
                            </div>
                            <div class="timeline-info">
                                <h3><span>Verifikasi</h3>
                                <p>Lihat pesan yang masuk ke email yang telah didaftarkan</p>
                                <img src="images/alurpendaftaran/4.jpg" alt="Langkah 4" class="img-fluid mb-3" style="max-width: 600px;">
                            </div>
                        </div>

                        <div class="timeline-wrapper">
                            <div class="timeline-yr">
                                <span>05.</span>
                            </div>
                            <div class="timeline-info">
                                <h3><span>Login</h3>
                                <p>Pada halaman verifikasi bisa di refresh atau login kembali ke situs portal, masukkan email dan password yg telah didaftarkan</p>
                                <img src="images/alurpendaftaran/5.jpg" alt="Langkah 5" class="img-fluid mb-3" style="max-width: 600px;">
                            </div>
                        </div>

                        <div class="timeline-wrapper">
                            <div class="timeline-yr">
                                <span>05.</span>
                            </div>
                            <div class="timeline-info">
                                <h3><span>Lengkapi Data Diri</h3>
                                <p>Lengkapi data diri yang kurang dan isi secara lengkap dan benar</p>
                                <img src="images/alurpendaftaran/6.jpg" alt="Langkah 6" class="img-fluid mb-3" style="max-width: 600px;">
                            </div>
                        </div>

                        <div class="timeline-wrapper">
                            <div class="timeline-yr">
                                <span>06.</span>
                            </div>
                            <div class="timeline-info">
                                <h3><span>Menghubungi Contact Person</h3>
                                <p>Setelah melakukan pengisian data, calon anggota harap segera menghubungi contact person yang tertera di dashboard akun</p>
                                <img src="images/alurpendaftaran/7.jpg" alt="Langkah 7" class="img-fluid mb-3" style="max-width: 600px;">
                            </div>
                        </div>

                        

                    </div>
                </div>

              
            </div>
        </div>
    </section>

  
    <footer class="find-us" role="contentinfo" aria-labelledby="findUsTitle">
      <h2 id="findUsTitle" style="color: white;">Kontak</h2>
      <div class="map-container" aria-label="Map location of KOPMA UGM">
  <iframe
    src="https://maps.app.goo.gl/37YgR4DFBsxTeeZ86"
    width="100%"
    height="300"
    style="border:0;"
    allowfullscreen=""
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade"
    title="Peta lokasi Koperasi Mahasiswa Universitas Gadjah Mada di Jl. Cik Di Tiro No.14, Terban, Kec. Gondokusuman Kota Yogyakarta"
    ></iframe>
    </div>

      <div class="contact-info">
        <div class="contact-block" role="region" aria-label="Alamat Kopma UGM">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#146936" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M12 21s8-4.5 8-10a8 8 0 1 0-16 0c0 5.5 8 10 8 10z" />
            <circle cx="12" cy="11" r="3" />
          </svg>
          <div class="contact-text">
            <strong>Alamat</strong><br />
            Jl. Cik Di Tiro No.14, Terban, Kec. Gondokusuman<br />
            Kota Yogyakarta, Daerah Istimewa Yogyakarta 55223
          </div>
        </div>
        <div class="contact-block" role="region" aria-label="Dukungan Pelayanan Kopma UGM">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#146936" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M22 2H2v20l4-4h16V2z" />
          </svg>
          <div class="contact-text">
            <strong>Dukungan Pelayanan</strong><br />
            E-mail: <a href="mailto:info@kopma-ugm.net">info@kopma-ugm.net</a><br />
            Phone: (0274) 565774
          </div>
        </div>
      </div>

      <nav class="social-icons" aria-label="Sosial media Kopma UGM">
        <!-- Twitter -->
  <a href="https://x.com/kopma_ugm" target="_blank" aria-label="X KOPMA UGM">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
      stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M22 4.01c-.77.35-1.6.59-2.47.7a4.3 4.3 0 0 0 1.88-2.37 8.59 8.59 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.3 3.9 12.14 12.14 0 0 1-8.24-4.18 4.28 4.28 0 0 0 1.33 5.7A4.26 4.26 0 0 1 2 7.7v.05a4.28 4.28 0 0 0 3.43 4.2 4.28 4.28 0 0 1-1.94.07 4.29 4.29 0 0 0 4 2.97A8.6 8.6 0 0 1 2 19.54 12.13 12.13 0 0 0 8.56 21.46c7.88 0 12.2-6.53 12.2-12.2l-.01-.56A8.56 8.56 0 0 0 22 4.01z" />
    </svg>
  </a>

  <a href="https://instagram.com/kopmaugm" target="_blank" aria-label="Instagram" title="Instagram">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <rect x="4" y="4" width="16" height="16" rx="4" />
      <circle cx="12" cy="12" r="3" />
      <line x1="16.5" y1="7.5" x2="16.5" y2="7.501" />
    </svg>
  </a>

  

  <!-- Facebook -->
  <a href="https://www.facebook.com/koperasimahasiswaugm/" aria-label="Facebook Kopma UGM">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
      stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
    </svg>
  </a>


  <!-- LinkedIn -->
  <a href="https://id.linkedin.com/company/kopmaugm" target="_blank" aria-label="LinkedIn KOPMA UGM">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
      stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M16 8a6 6 0 0 1 6 6v5h-4v-5a2 2 0 0 0 -4 0v5h-4v-10h4v2" />
      <path d="M2 9h4v12h-4z" />
      <circle cx="4" cy="4" r="2" />
    </svg>
  </a>

  <!-- YouTube -->
  <a href="https://www.youtube.com/channel/UCbWV-2S-JsIM2gbS6umaayw" aria-label="Youtube Kopma UGM">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="currentColor">
      <path d="M22.5 6.162c-.3-1.14-1.2-2.04-2.34-2.34C17.7 3.5 12 3.5 12 3.5s-5.7 0-8.16.322c-1.14.3-2.04 1.2-2.34 2.34C1 8.7 1 12 1 12s0 3.3.5 5.836c.3 1.14 1.2 2.04 2.34 2.34C6.3 20.5 12 20.5 12 20.5s5.7 0 8.16-.322c1.14-.3 2.04-1.2 2.34-2.34.5-2.537.5-5.837.5-5.837s0-3.3-.5-5.837zM10 15.5v-7l6 3.5-6 3.5z" />
    </svg>
  </a>

      </nav>
    </footer>

    <!-- Link JS Eksternal -->
    <script src="/js/script.js" defer></script>
</body>
</html>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- Inisialisasi Carousel -->
<script>
  $(document).ready(function(){
    $(".owl-carousel").owlCarousel({
      items: 1,           // Tampilkan 1 gambar saja
      loop: true,
      margin: 10,
      nav: true,          // Tampilkan tombol prev/next
      dots: true,
      navText: ["‹","›"], // Tambahkan simbol navigasi
      autoplay: true,
      autoplayTimeout: 3000
    });
  });
</script>



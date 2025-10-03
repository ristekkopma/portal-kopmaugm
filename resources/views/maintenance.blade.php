{{-- resources/views/maintenance.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Portal Kopma UGM — Maintenance</title>

  {{-- Tailwind via CDN (cukup untuk halaman statis ini) --}}
  <script src="https://cdn.tailwindcss.com"></script>

  <meta name="theme-color" content="#4f46e5">
  <style>
    /* font fallback lembut */
    :root { --brand: 79 70 229; --accent: 16 185 129; }
    .grad-bg{
      background:
        radial-gradient(1200px 600px at 10% -20%, rgba(79,70,229,.18), transparent 60%),
        radial-gradient(800px 400px at 110% 0%, rgba(16,185,129,.20), transparent 45%),
        linear-gradient(180deg, #0b1020, #0b1020);
    }
    .glass { backdrop-filter: blur(10px); background: rgba(255,255,255,.06); }
    .shadow-soft { box-shadow: 0 10px 30px rgba(0,0,0,.25); }
  </style>
</head>
<body class="min-h-dvh grad-bg text-slate-100 antialiased">
  <div class="absolute inset-0 pointer-events-none opacity-20"
       style="background-image: radial-gradient(2px 2px at 20px 20px, rgba(255,255,255,.25) 1px, transparent 1px);
              background-size: 24px 24px;"></div>

  <main class="relative z-10 max-w-3xl mx-auto px-6 py-16 md:py-24">
    <header class="flex items-center gap-3 mb-10">
      <div class="size-11 rounded-2xl bg-white/10 grid place-items-center ring-1 ring-white/20">
        {{-- Placeholder logo huruf K --}}
        <span class="font-black text-lg tracking-wide">K</span>
      </div>
      <div>
        <h1 class="text-xl font-semibold leading-tight">Portal Kopma UGM</h1>
        <p class="text-slate-300 text-sm">Koperasi Mahasiswa Universitas Gadjah Mada</p>
      </div>
    </header>

    <section class="glass rounded-3xl p-8 md:p-10 shadow-soft ring-1 ring-white/10">
      <div class="flex flex-col md:flex-row md:items-start gap-8">
        <div class="md:w-2/3">
          <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-4">
            Sedang Perawatan Sistem
          </h2>
          <p class="text-slate-300 leading-relaxed">
            Kami sedang melakukan pemeliharaan portal agar layanan lebih stabil, aman, dan nyaman.
            Terima kasih atas pengertiannya. Sementara itu, pendaftaran keanggotaan tetap dapat dilakukan melalui tautan berikut.
          </p>

          <div class="mt-8 flex flex-wrap items-center gap-4">
            <a href="https://bit.ly/PendaftaranMembershipKopmaUGM"
               class="inline-flex items-center justify-center rounded-xl px-6 py-3 text-base font-semibold
                      ring-1 ring-white/20 bg-emerald-500/90 hover:bg-emerald-500 focus:outline-none
                      focus-visible:ring-2 focus-visible:ring-emerald-300 transition shadow-soft">
              Daftar Membership Kopma UGM
              <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 8l4 4m0 0l-4 4m4-4H3"/>
              </svg>
            </a>

            <span class="text-slate-400 text-sm">
              Login & Registrasi akun portal untuk sementara disembunyikan.
            </span>
          </div>
        </div>

        <div class="md:w-1/3">
          <div class="rounded-2xl ring-1 ring-white/10 bg-white/5 p-6">
            <h3 class="font-semibold mb-2">Info Singkat</h3>
            <ul class="space-y-2 text-sm text-slate-300">
              <li>• Perkiraan waktu selesai: secepatnya</li>
              <li>• Butuh bantuan? hubungi admin Kopma</li>
            </ul>
                    <div class="mt-6 h-px w-full bg-white/10"></div>
            <p>
            <a href="https://wa.me/6282189853570" target="_blank" class="hover:text-emerald-400 underline">
                CP Admin
            </a>

           </p>
           <p> <a href="https://wa.me/628812920446" target="_blank" class="hover:text-emerald-400 underline">
                CP Developer
            </a></p>
             </div>
      </div>
    </section>

    <footer class="mt-10 text-center text-sm text-slate-400">
      © <span id="y"></span> Kopma UGM — All rights reserved.
    </footer>
  </main>

  <script>
    document.getElementById('y').textContent = new Date().getFullYear();
  </script>
</body>
</html>

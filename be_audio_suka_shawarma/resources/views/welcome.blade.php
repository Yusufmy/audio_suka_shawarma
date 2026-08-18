<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Audio Shawarma — Satu Siaran, Semua Outlet</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,500&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --bg-char:#17110C;
    --bg-char-2:#211811;
    --cream:#F2E8D5;
    --paper:#FBF4E6;
    --ink:#241A12;
    --ink-soft:#5C4E3E;
    --chili:#D8452A;
    --chili-deep:#B3341C;
    --saffron:#E8A93D;
    --signal:#3FA796;
    --signal-dim:#3FA79633;
    --line:#3A2C1E;
    --line-soft:#E3D6BC;
  }
  *{box-sizing:border-box; margin:0; padding:0;}
  html{scroll-behavior:smooth;}
  body{
    background:var(--cream);
    color:var(--ink);
    font-family:'Work Sans', sans-serif;
    line-height:1.5;
    overflow-x:hidden;
  }
  .mono{font-family:'JetBrains Mono', monospace; letter-spacing:0.02em;}
  h1,h2,h3{font-family:'Fraunces', serif; font-weight:600; line-height:1.05; letter-spacing:-0.01em;}
  a{color:inherit; text-decoration:none;}
  img,svg{display:block;}
  .wrap{max-width:1180px; margin:0 auto; padding:0 28px;}
  @media(max-width:640px){.wrap{padding:0 20px;}}

  /* ---------- NAV ---------- */
  .nav{
    position:sticky; top:0; z-index:50;
    background:rgba(23,17,12,0.92); backdrop-filter:blur(8px);
    border-bottom:1px solid #3A2C1E;
  }
  .nav .wrap{display:flex; align-items:center; justify-content:space-between; height:68px;}
  .brand{display:flex; align-items:center; gap:10px; color:var(--paper);}
  .brand-mark{width:30px; height:30px; flex-shrink:0;}
  .brand-name{font-family:'Fraunces', serif; font-weight:600; font-size:19px; letter-spacing:-0.01em;}
  .navlinks{display:flex; gap:32px; font-size:14px; color:#C9BBA3;}
  .navlinks a:hover{color:var(--saffron);}
  .nav-cta{
    background:var(--chili); color:var(--paper); padding:9px 18px; border-radius:3px;
    font-size:13px; font-weight:600; letter-spacing:0.02em;
  }
  .nav-cta:hover{background:var(--chili-deep);}
  @media(max-width:800px){.navlinks{display:none;}}

  /* ---------- HERO ---------- */
  .hero{
    background:radial-gradient(ellipse 900px 500px at 78% 15%, #2A1E13 0%, var(--bg-char) 55%);
    color:var(--paper); position:relative; overflow:hidden;
    padding:88px 0 100px;
  }
  .hero .wrap{display:grid; grid-template-columns:1.05fr 0.95fr; gap:40px; align-items:center;}
  @media(max-width:900px){.hero .wrap{grid-template-columns:1fr; text-align:left;} }
  .eyebrow{
    display:inline-flex; align-items:center; gap:8px;
    font-family:'JetBrains Mono', monospace; font-size:12px; letter-spacing:0.12em;
    text-transform:uppercase; color:var(--signal); margin-bottom:22px;
  }
  .eyebrow .dot{width:7px; height:7px; border-radius:50%; background:var(--signal); box-shadow:0 0 0 0 var(--signal); animation:pulse-dot 1.8s infinite;}
  @keyframes pulse-dot{
    0%{box-shadow:0 0 0 0 rgba(63,167,150,0.55);}
    70%{box-shadow:0 0 0 9px rgba(63,167,150,0);}
    100%{box-shadow:0 0 0 0 rgba(63,167,150,0);}
  }
  .hero h1{font-size:clamp(38px,5vw,60px); margin-bottom:22px; color:#FAF3E4;}
  .hero h1 em{font-style:italic; color:var(--saffron); font-weight:500;}
  .hero p.lead{font-size:17px; color:#C9BBA3; max-width:480px; margin-bottom:34px;}
  .hero-ctas{display:flex; gap:14px; flex-wrap:wrap; margin-bottom:46px;}
  .btn-primary{
    background:var(--chili); color:var(--paper); padding:14px 26px; border-radius:3px;
    font-weight:600; font-size:14.5px; display:inline-flex; align-items:center; gap:8px;
    box-shadow:0 8px 20px -8px rgba(216,69,42,0.55);
  }
  .btn-primary:hover{background:var(--chili-deep);}
  .btn-ghost{
    border:1px solid #4A3B29; color:#EFE3CC; padding:14px 26px; border-radius:3px; font-weight:500; font-size:14.5px;
  }
  .btn-ghost:hover{border-color:var(--saffron); color:var(--saffron);}
  .hero-stats{display:flex; gap:34px; flex-wrap:wrap;}
  .hero-stats div{border-left:2px solid #3A2C1E; padding-left:14px;}
  .hero-stats .num{font-family:'JetBrains Mono',monospace; font-size:22px; color:var(--saffron); font-weight:600;}
  .hero-stats .lbl{font-size:12.5px; color:#9A8A70; margin-top:2px;}

  /* ---- broadcast dial signature element ---- */
  .dial-wrap{position:relative; width:100%; aspect-ratio:1/1; max-width:480px; margin:0 auto;}
  .dial-ring{
    position:absolute; border-radius:50%; border:1px solid #3A2C1E;
    top:50%; left:50%; transform:translate(-50%,-50%);
  }
  .r1{width:100%; height:100%;}
  .r2{width:74%; height:74%;}
  .r3{width:48%; height:48%;}
  .pulse-ring{
    position:absolute; top:50%; left:50%; width:22%; height:22%;
    border-radius:50%; border:1.5px solid var(--signal);
    transform:translate(-50%,-50%) scale(1); opacity:0;
    animation:dial-pulse 3.2s ease-out infinite;
  }
  .pulse-ring.d2{animation-delay:1.05s;}
  .pulse-ring.d3{animation-delay:2.1s;}
  @keyframes dial-pulse{
    0%{transform:translate(-50%,-50%) scale(1); opacity:0.65;}
    100%{transform:translate(-50%,-50%) scale(4.3); opacity:0;}
  }
  .hq-node{
    position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
    width:22%; height:22%; border-radius:50%;
    background:linear-gradient(155deg, var(--chili), var(--chili-deep));
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 0 0 6px rgba(216,69,42,0.12), 0 14px 30px -10px rgba(216,69,42,0.6);
    z-index:3;
  }
  .hq-node svg{width:44%; height:44%;}
  .outlet-node{
    position:absolute; width:15%; height:15%; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    z-index:3; font-family:'JetBrains Mono',monospace;
  }
  .outlet-node.live{background:var(--signal); box-shadow:0 0 0 5px rgba(63,167,150,0.18);}
  .outlet-node.idle{background:#332619; border:1px solid #4A3B29;}
  .outlet-node svg{width:52%; height:52%;}
  .outlet-node.live svg{color:#0E241F;}
  .outlet-node.idle svg{color:#8A7A64;}
  .o1{top:6%; left:50%; transform:translate(-50%,0);}
  .o2{top:26%; left:88%; transform:translate(-50%,0);}
  .o3{top:74%; left:88%; transform:translate(-50%,0);}
  .o4{top:94%; left:50%; transform:translate(-50%,-100%);}
  .o5{top:74%; left:12%; transform:translate(-50%,0);}
  .o6{top:26%; left:12%; transform:translate(-50%,0);}
  .dial-caption{
    position:absolute; bottom:-38px; left:50%; transform:translateX(-50%);
    font-family:'JetBrains Mono',monospace; font-size:11.5px; color:#8A7A64; letter-spacing:0.05em;
    white-space:nowrap;
  }

  /* ---------- SECTION HEADERS ---------- */
  .section{padding:96px 0;}
  .section.on-char{background:var(--bg-char); color:var(--paper);}
  .section.on-paper{background:var(--paper); border-top:1px solid var(--line-soft); border-bottom:1px solid var(--line-soft);}
  .sec-head{max-width:640px; margin-bottom:56px;}
  .sec-eyebrow{font-family:'JetBrains Mono',monospace; font-size:12px; letter-spacing:0.12em; text-transform:uppercase; color:var(--chili); margin-bottom:14px;}
  .on-char .sec-eyebrow{color:var(--signal);}
  .sec-head h2{font-size:clamp(28px,3.4vw,38px); margin-bottom:14px;}
  .sec-head p{color:var(--ink-soft); font-size:16px;}
  .on-char .sec-head p{color:#B9AB92;}

  /* ---------- ROLES ---------- */
  .roles{display:grid; grid-template-columns:1fr 1fr; gap:0; border:1px solid var(--line); border-radius:6px; overflow:hidden;}
  @media(max-width:760px){.roles{grid-template-columns:1fr;}}
  .role-card{padding:40px; background:var(--bg-char-2);}
  .role-card + .role-card{border-left:1px solid var(--line);}
  @media(max-width:760px){.role-card + .role-card{border-left:none; border-top:1px solid var(--line);}}
  .role-tag{
    display:inline-block; font-family:'JetBrains Mono',monospace; font-size:11.5px;
    padding:4px 10px; border-radius:3px; margin-bottom:20px; letter-spacing:0.04em;
  }
  .role-tag.op{background:rgba(216,69,42,0.16); color:#F2A489;}
  .role-tag.out{background:rgba(63,167,150,0.16); color:#8FD3C6;}
  .role-card h3{font-size:24px; color:var(--paper); margin-bottom:10px;}
  .role-card > p{color:#B9AB92; font-size:14.5px; margin-bottom:24px;}
  .role-list{list-style:none;}
  .role-list li{
    display:flex; gap:12px; padding:11px 0; border-top:1px solid #2B2118; font-size:14px; color:#D9CDB6;
  }
  .role-list li:first-child{border-top:none;}
  .role-list li svg{width:16px; height:16px; flex-shrink:0; margin-top:2px; color:var(--saffron);}

  /* ---------- BROADCAST TYPES ---------- */
  .types-grid{display:grid; grid-template-columns:repeat(3,1fr); gap:24px;}
  @media(max-width:820px){.types-grid{grid-template-columns:1fr;}}
  .type-card{
    background:var(--paper); border:1px solid var(--line-soft); border-radius:6px; padding:32px 28px;
    position:relative;
  }
  .type-card .idx{font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--chili); margin-bottom:18px;}
  .type-icon{
    width:46px; height:46px; border-radius:50%; background:var(--ink); color:var(--saffron);
    display:flex; align-items:center; justify-content:center; margin-bottom:20px;
  }
  .type-icon svg{width:22px; height:22px;}
  .type-card h3{font-size:19px; margin-bottom:10px;}
  .type-card p{font-size:14.5px; color:var(--ink-soft);}

  /* ---------- STATUS / DASHBOARD MOCK ---------- */
  .status-wrap{display:grid; grid-template-columns:0.9fr 1.1fr; gap:56px; align-items:center;}
  @media(max-width:900px){.status-wrap{grid-template-columns:1fr;}}
  .status-feats{display:flex; flex-direction:column; gap:26px;}
  .status-feat{display:flex; gap:16px;}
  .status-feat .mark{
    width:34px; height:34px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center;
    background:rgba(63,167,150,0.14); color:var(--signal); margin-top:2px;
  }
  .status-feat .mark svg{width:17px; height:17px;}
  .status-feat h4{font-size:16.5px; margin-bottom:6px; font-family:'Work Sans'; font-weight:600;}
  .status-feat p{font-size:14px; color:var(--ink-soft);}

  .mock{
    background:var(--bg-char); border-radius:8px; padding:22px; border:1px solid var(--line);
    box-shadow:0 30px 60px -30px rgba(23,17,12,0.5);
  }
  .mock-top{display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; padding-bottom:14px; border-bottom:1px solid #2B2118;}
  .mock-top .t1{font-family:'JetBrains Mono',monospace; font-size:12px; color:#8A7A64;}
  .mock-live{
    display:flex; align-items:center; gap:6px; font-family:'JetBrains Mono',monospace; font-size:11px;
    color:var(--chili); text-transform:uppercase; letter-spacing:0.08em;
  }
  .mock-live .dot{width:6px; height:6px; border-radius:50%; background:var(--chili); animation:pulse-dot 1.6s infinite;}
  .mock-row{
    display:flex; align-items:center; justify-content:space-between; padding:13px 4px;
    border-bottom:1px solid #221A12; font-size:13.5px; color:#D9CDB6;
  }
  .mock-row:last-child{border-bottom:none;}
  .mock-out{display:flex; align-items:center; gap:10px;}
  .mock-out .oc{
    width:8px; height:8px; border-radius:50%;
  }
  .mock-out .oc.ramai{background:var(--chili);}
  .mock-out .oc.sepi{background:var(--signal);}
  .mock-badge{
    font-family:'JetBrains Mono',monospace; font-size:10.5px; padding:3px 8px; border-radius:3px; text-transform:uppercase; letter-spacing:0.04em;
  }
  .mock-badge.on{background:rgba(216,69,42,0.16); color:#F2A489;}
  .mock-badge.wait{background:#2B2118; color:#8A7A64;}

  /* ---------- HARDWARE ---------- */
  .hw-grid{display:grid; grid-template-columns:1fr 1fr; gap:24px;}
  @media(max-width:760px){.hw-grid{grid-template-columns:1fr;}}
  .hw-card{border:1px solid var(--line-soft); border-radius:6px; padding:32px; background:var(--cream);}
  .hw-card h3{font-size:19px; margin-bottom:18px; display:flex; align-items:center; gap:10px;}
  .hw-card h3 .swatch{width:9px; height:9px; border-radius:50%;}
  .hw-card.hq .swatch{background:var(--chili);}
  .hw-card.out .swatch{background:var(--signal);}
  .hw-list{list-style:none; display:flex; flex-direction:column; gap:12px;}
  .hw-list li{display:flex; gap:10px; font-size:14.5px; color:var(--ink-soft);}
  .hw-list li:before{content:"—"; color:var(--ink-soft); flex-shrink:0;}

  /* ---------- CTA / FOOTER ---------- */
  .cta{
    background:linear-gradient(160deg, #221811 0%, var(--bg-char) 100%);
    color:var(--paper); padding:100px 0; text-align:center;
  }
  .cta h2{font-size:clamp(30px,4vw,44px); margin-bottom:16px;}
  .cta p{color:#B9AB92; font-size:16px; max-width:480px; margin:0 auto 34px;}
  .cta .hero-ctas{justify-content:center;}

  footer{background:var(--bg-char); border-top:1px solid var(--line); padding:34px 0; color:#8A7A64; font-size:13px;}
  footer .wrap{display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;}
  footer .brand{color:#B9AB92;}

  ::selection{background:var(--saffron); color:var(--ink);}

  @media (prefers-reduced-motion: reduce){
    *{animation-duration:0.001ms !important; animation-iteration-count:1 !important;}
  }
</style>
</head>
<body>

  <nav class="nav">
    <div class="wrap">
      <div class="brand">
        <svg class="brand-mark" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="20" cy="20" r="19" stroke="#D8452A" stroke-width="1.4"/>
          <circle cx="20" cy="20" r="6.5" fill="#D8452A"/>
          <path d="M20 6 L20 10.5 M20 29.5 L20 34 M6 20 L10.5 20 M29.5 20 L34 20" stroke="#E8A93D" stroke-width="1.4" stroke-linecap="round"/>
        </svg>
        <span class="brand-name">Audio Shawarma</span>
      </div>
      <div class="navlinks">
        <a href="#peran">Peran</a>
        <a href="#siaran">Siaran</a>
        <a href="#status">Dashboard</a>
        <a href="#perangkat">Perangkat</a>
      </div>
      <a class="nav-cta" href="#mulai">Mulai Sekarang</a>
    </div>
  </nav>

  <!-- HERO -->
  <header class="hero">
    <div class="wrap">
      <div>
        <div class="eyebrow"><span class="dot"></span> Siaran real-time ke seluruh outlet</div>
        <h1>Satu suara <em>HQ</em>,<br> menyala di setiap outlet.</h1>
        <p class="lead">Audio Shawarma menghubungkan operator pusat dengan seluruh gerai lewat siaran audio real-time — seperti radio, tapi terarah: pilih outlet mana yang perlu dengar, kapan, dan dengan pesan apa.</p>
        <div class="hero-ctas">
          <a class="btn-primary" href="#mulai">
            Coba Jadi Operator
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M3 7.5H12M12 7.5L8 3.5M12 7.5L8 11.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
          <a class="btn-ghost" href="#peran">Lihat Cara Kerja</a>
        </div>
        <div class="hero-stats">
          <div><div class="num">1</div><div class="lbl">Operator, kendali penuh</div></div>
          <div><div class="num">N</div><div class="lbl">Outlet, target bebas pilih</div></div>
        </div>
      </div>

      <div class="dial-wrap">
        <div class="dial-ring r1"></div>
        <div class="dial-ring r2"></div>
        <div class="dial-ring r3"></div>
        <div class="pulse-ring"></div>
        <div class="pulse-ring d2"></div>
        <div class="pulse-ring d3"></div>

        <div class="hq-node">
          <svg viewBox="0 0 24 24" fill="none" stroke="#FBF4E6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M8 6l4-4 4 4M6 10c2 1.5 10 1.5 12 0M5 14c3 2 11 2 14 0M4 18c4 2.5 12 2.5 16 0"/></svg>
        </div>

        <div class="outlet-node live o1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6v12M8 9v6M16 9v6M4 11v2M20 11v2"/></svg></div>
        <div class="outlet-node live o2"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6v12M8 9v6M16 9v6M4 11v2M20 11v2"/></svg></div>
        <div class="outlet-node idle o3"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6v12M8 9v6M16 9v6M4 11v2M20 11v2"/></svg></div>
        <div class="outlet-node live o4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6v12M8 9v6M16 9v6M4 11v2M20 11v2"/></svg></div>
        <div class="outlet-node idle o5"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6v12M8 9v6M16 9v6M4 11v2M20 11v2"/></svg></div>
        <div class="outlet-node live o6"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6v12M8 9v6M16 9v6M4 11v2M20 11v2"/></svg></div>

        <div class="dial-caption">HQ → OUTLET TERPILIH SAJA</div>
      </div>
    </div>
  </header>

  <!-- ROLES -->
  <section class="section on-char" id="peran">
    <div class="wrap">
      <div class="sec-head">
        <div class="sec-eyebrow">Dua peran</div>
        <h2>Operator menyiarkan, outlet mendengarkan.</h2>
        <p>Setiap sisi punya panel yang dibuat khusus untuk tugasnya — operator mengarahkan, outlet cukup siap sedia.</p>
      </div>
      <div class="roles">
        <div class="role-card">
          <span class="role-tag op">Operator / HQ</span>
          <h3>Kendali penuh dari satu layar</h3>
          <p>Login sebagai operator dan atur siaran ke seluruh jaringan outlet dari satu dashboard.</p>
          <ul class="role-list">
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Lihat semua outlet, status online/offline real-time</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Pilih target: siaran ke semua atau outlet tertentu saja</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Siaran langsung, upload audio, atau jadwalkan</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Hentikan atau batalkan siaran kapan saja</li>
          </ul>
        </div>
        <div class="role-card">
          <span class="role-tag out">Outlet</span>
          <h3>Siap dengar, tanpa perlu disentuh</h3>
          <p>Daftar sekali dengan nama atau kode outlet, lalu biarkan tablet menunggu di dashboard.</p>
          <ul class="role-list">
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Login cukup dengan kode outlet atau nama cabang</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Halaman "menunggu siaran" selalu aktif di tablet</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Terima siaran otomatis begitu operator mulai</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Info siaran yang sedang berjalan tampil jelas di layar</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- BROADCAST TYPES -->
  <section class="section on-paper" id="siaran">
    <div class="wrap">
      <div class="sec-head">
        <div class="sec-eyebrow">Jenis siaran</div>
        <h2>Cara mengirim suara ke outlet</h2>
        <p>Operator memilih sendiri caranya di setiap siaran — bebas disesuaikan dengan situasi hari itu.</p>
      </div>
      <div class="types-grid">
        <div class="type-card">
          <div class="idx mono">01 / LANGSUNG</div>
          <div class="type-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15a3 3 0 0 0 3-3V6a3 3 0 0 0-6 0v6a3 3 0 0 0 3 3z"/><path d="M19 11a7 7 0 0 1-14 0M12 18v3"/></svg></div>
          <h3>Siaran suara real-time</h3>
          <p>Operator bicara langsung lewat mic, terdengar seketika di outlet yang dipilih — cocok untuk arahan cepat atau koreksi.</p>
        </div>
        <div class="type-card">
          <div class="idx mono">02 / UNGGAH</div>
          <div class="type-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5M4 20h16"/></svg></div>
          <h3>Unggah file audio</h3>
          <p>Kirim rekaman yang sudah disiapkan sebelumnya — pengumuman rutin atau promo — tinggal pilih file dan target outlet.</p>
        </div>
        {{-- <div class="type-card">
          <div class="idx mono">03 / TERJADWAL</div>
          <div class="type-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg></div>
          <h3>Jadwalkan audio</h3>
          <p>Tentukan hari dan jam, audio akan diputar otomatis ke outlet tujuan tanpa operator perlu berjaga saat itu.</p>
        </div> --}}
      </div>
    </div>
  </section>

  <!-- STATUS / DASHBOARD -->
  <section class="section on-char" id="status">
    <div class="wrap">
      <div class="status-wrap">
        <div>
          <div class="sec-eyebrow">Dashboard operator</div>
          <h2 style="font-size:clamp(26px,3vw,34px); margin-bottom:22px;">Tahu outlet mana yang perlu disiarkan</h2>
          <div class="status-feats">
            <div class="status-feat">
              <div class="mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10M12 20V4M6 20v-6"/></svg></div>
              <div>
                <h4 style="color:var(--paper)">Status ramai / sepi per outlet</h4>
                <p style="color:#B9AB92">Operator menandai kondisi tiap outlet secara manual dari dashboard, jadi siaran bisa diarahkan ke yang benar-benar butuh.</p>
              </div>
            </div>
            <div class="status-feat">
              <div class="mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg></div>
              <div>
                <h4 style="color:var(--paper)">Filter target siaran</h4>
                <p style="color:#B9AB92">Lewati outlet yang sedang sibuk, fokuskan siaran hanya ke outlet yang sepi — supaya tidak mengganggu yang sedang ramai pelanggan.</p>
              </div>
            </div>
            <div class="status-feat">
              <div class="mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v2M12 19v2M5 5l1.4 1.4M17.6 17.6 19 19M3 12h2M19 12h2M5 19l1.4-1.4M17.6 6.4 19 5"/></svg></div>
              <div>
                <h4 style="color:var(--paper)">"Siaran semangat" untuk outlet sepi</h4>
                <p style="color:#B9AB92">Siaran khusus yang dikirim manual oleh operator untuk membangkitkan suasana di outlet yang lagi lengang.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="mock">
          <div class="mock-top">
            <span class="t1 mono">operator / dashboard</span>
            <span class="mock-live"><span class="dot"></span> ON AIR</span>
          </div>
          <div class="mock-row">
            <div class="mock-out"><span class="oc ramai"></span> Outlet — Margonda</div>
            <span class="mock-badge on">Menerima</span>
          </div>
          <div class="mock-row">
            <div class="mock-out"><span class="oc sepi"></span> Outlet — Cimanggis</div>
            <span class="mock-badge on">Menerima</span>
          </div>
          <div class="mock-row">
            <div class="mock-out"><span class="oc ramai"></span> Outlet — Depok Baru</div>
            <span class="mock-badge wait">Dilewati (ramai)</span>
          </div>
          <div class="mock-row">
            <div class="mock-out"><span class="oc sepi"></span> Outlet — Sawangan</div>
            <span class="mock-badge on">Menerima</span>
          </div>
          <div class="mock-row">
            <div class="mock-out"><span class="oc ramai"></span> Outlet — Beji</div>
            <span class="mock-badge wait">Offline</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- HARDWARE -->
  <section class="section on-paper" id="perangkat">
    <div class="wrap">
      <div class="sec-head">
        <div class="sec-eyebrow">Perangkat</div>
        <h2>Yang perlu disiapkan tiap sisi</h2>
        <p>Tidak butuh perangkat mahal — yang penting stabil dan selalu menyala.</p>
      </div>
      <div class="hw-grid">
        <div class="hw-card hq">
          <h3><span class="swatch"></span> HQ / Operator</h3>
          <ul class="hw-list">
            <li>Smartphone atau tablet Android</li>
            <li>Headset dengan mic (disarankan, untuk kualitas suara lebih jernih)</li>
            <li>Koneksi internet yang stabil</li>
          </ul>
        </div>
        <div class="hw-card out">
          <h3><span class="swatch"></span> Outlet</h3>
          <ul class="hw-list">
            <li>Tablet Android yang terpasang tetap di outlet</li>
            <li>Speaker aktif / Bluetooth yang selalu tersambung</li>
            <li>Koneksi internet yang stabil</li>
            <li>Charger atau sumber daya tetap agar tablet selalu menyala</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta" id="mulai">
    <div class="wrap">
      <h2>Siap menyalakan semua outlet<br>dari satu suara?</h2>
      <p>Daftarkan HQ sebagai operator, tambahkan outlet satu per satu, dan mulai siaran pertama hari ini.</p>
      <div class="hero-ctas">
        <a class="btn-primary" href="#">Daftar Sebagai Operator</a>
        <a class="btn-ghost" href="#">Daftarkan Outlet</a>
      </div>
    </div>
  </section>

  <footer>
    <div class="wrap">
      <span class="brand">Audio Shawarma</span>
      <span class="mono">HQ ⟶ SEMUA OUTLET, TERARAH.</span>
    </div>
  </footer>

</body>
</html>

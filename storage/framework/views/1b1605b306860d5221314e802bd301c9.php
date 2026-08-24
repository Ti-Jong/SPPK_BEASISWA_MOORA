<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'SPK Beasiswa'); ?> — Cerdas Humanis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a3a5c; --primary-light: #2563a8; --accent: #e8a020;
            --success: #059669; --danger: #dc2626; --warning: #d97706;
            --bg: #f0f4f8; --card: #ffffff; --text: #1e293b;
            --text-muted: #64748b; --border: #e2e8f0; --sidebar-w: 260px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--bg); color:var(--text); }
        /* Sidebar */
        .sidebar { position:fixed; left:0; top:0; bottom:0; width:var(--sidebar-w); background:var(--primary); overflow-y:auto; z-index:100; display:flex; flex-direction:column; }
        .sidebar-brand { padding:20px; border-bottom:1px solid rgba(255,255,255,0.08); display:flex; align-items:center; gap:12px; }
        .brand-icon { width:46px; height:46px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0; box-shadow:0 2px 8px rgba(232,160,32,0.4); padding:3px; }
        .brand-icon img { width:100%; height:100%; object-fit:contain; border-radius:50%; }
        .brand-text h2 { color:#fff; font-size:0.9rem; font-weight:700; }
        .brand-text p { color:rgba(255,255,255,0.45); font-size:0.68rem; }
        .sidebar-user { padding:14px 20px; border-bottom:1px solid rgba(255,255,255,0.08); display:flex; align-items:center; gap:10px; }
        .user-avatar { width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,var(--primary-light),#3b82f6); display:flex; align-items:center; justify-content:center; font-weight:700; color:#fff; font-size:13px; }
        .user-info p { color:rgba(255,255,255,0.9); font-size:0.82rem; font-weight:600; }
        .user-info span { color:rgba(255,255,255,0.45); font-size:0.7rem; }
        nav.sidebar-nav { flex:1; padding:8px 0; }
        .nav-section { padding:10px 20px 4px; color:rgba(255,255,255,0.3); font-size:0.62rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; }
        .nav-item { display:flex; align-items:center; gap:10px; padding:10px 20px; color:rgba(255,255,255,0.6); font-size:0.83rem; cursor:pointer; border-left:3px solid transparent; text-decoration:none; transition:all .2s; }
        .nav-item:hover { background:rgba(255,255,255,0.06); color:rgba(255,255,255,0.9); }
        .nav-item.active { background:rgba(255,255,255,0.1); color:#fff; border-left-color:var(--accent); }
        .nav-item i { width:18px; text-align:center; font-size:0.85rem; }
        .sidebar-footer { padding:16px 20px; border-top:1px solid rgba(255,255,255,0.08); }
        /* Main */
        .main-content { margin-left:var(--sidebar-w); min-height:100vh; display:flex; flex-direction:column; }
        .topbar { background:#fff; border-bottom:1px solid var(--border); padding:14px 24px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; }
        .topbar h2 { font-size:1.05rem; font-weight:700; }
        .page-content { padding:24px; flex:1; }
        /* Cards */
        .card { background:#fff; border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 3px rgba(0,0,0,0.05); margin-bottom:20px; }
        .card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
        .card-header h3 { font-size:0.92rem; font-weight:700; }
        .card-body { padding:20px; }
        /* Stats */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px; margin-bottom:20px; }
        .stat-card { background:#fff; border-radius:14px; padding:18px; border:1px solid var(--border); display:flex; align-items:center; gap:14px; }
        .stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
        .si-blue{background:#eff6ff;color:#2563eb} .si-green{background:#f0fdf4;color:#059669} .si-orange{background:#fff7ed;color:#ea580c} .si-purple{background:#faf5ff;color:#7c3aed} .si-gold{background:#fffbeb;color:#d97706}
        .stat-val { font-size:1.7rem; font-weight:800; line-height:1; }
        .stat-label { color:var(--text-muted); font-size:0.75rem; margin-top:3px; }
        /* Table */
        .table-wrap { overflow-x:auto; border-radius:10px; border:1px solid var(--border); }
        table { width:100%; border-collapse:collapse; }
        th { background:#f8fafc; padding:10px 14px; font-size:0.76rem; font-weight:700; color:var(--text-muted); text-align:left; border-bottom:1px solid var(--border); white-space:nowrap; }
        td { padding:10px 14px; font-size:0.82rem; border-bottom:1px solid var(--border); vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#f8fafc; }
        /* Badges */
        .badge { display:inline-flex; align-items:center; gap:3px; padding:3px 9px; border-radius:20px; font-size:0.7rem; font-weight:600; }
        .b-success{background:#dcfce7;color:#15803d} .b-warning{background:#fef3c7;color:#92400e} .b-danger{background:#fee2e2;color:#b91c1c} .b-info{background:#dbeafe;color:#1d4ed8} .b-gray{background:#f1f5f9;color:#475569} .b-gold{background:#fef9c3;color:#713f12}
        /* Buttons */
        .btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:none; font-family:inherit; font-size:0.82rem; font-weight:600; cursor:pointer; transition:all .2s; text-decoration:none; }
        .btn-primary{background:var(--primary-light);color:#fff} .btn-primary:hover{background:var(--primary)}
        .btn-accent{background:var(--accent);color:#fff} .btn-accent:hover{background:#c8870a}
        .btn-success{background:var(--success);color:#fff} .btn-danger{background:var(--danger);color:#fff}
        .btn-outline{background:none;border:1px solid var(--border);color:var(--text)} .btn-outline:hover{background:var(--bg)}
        .btn-sm{padding:5px 11px;font-size:0.74rem}
        /* Form */
        .form-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:14px; }
        .f-group { display:flex; flex-direction:column; gap:5px; }
        .f-group label { font-size:0.78rem; font-weight:600; color:var(--text-muted); }
        .f-group input,.f-group select,.f-group textarea { padding:9px 12px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:0.84rem; transition:border-color .2s; }
        .f-group input:focus,.f-group select:focus,.f-group textarea:focus { outline:none; border-color:var(--primary-light); box-shadow:0 0 0 3px rgba(37,99,168,0.08); }
        /* Alert */
        .alert { padding:11px 15px; border-radius:9px; font-size:0.83rem; display:flex; align-items:center; gap:9px; margin-bottom:16px; }
        .alert-success{background:#dcfce7;color:#15803d;border:1px solid #86efac}
        .alert-danger{background:#fee2e2;color:#b91c1c;border:1px solid #fca5a5}
        .alert-warning{background:#fef9c3;color:#713f12;border:1px solid #fde68a}
        .alert-info{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}
        /* Rank Card */
        .rank-card { border:1px solid var(--border); border-radius:11px; padding:14px; display:flex; align-items:center; gap:12px; margin-bottom:8px; }
        .rank-num { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.95rem; flex-shrink:0; }
        .r1{background:#fef9c3;color:#713f12} .r2{background:#f1f5f9;color:#334155} .r3{background:#fef3c7;color:#92400e} .rn{background:#f8fafc;color:#94a3b8}
        .progress-bar { height:5px; background:#e2e8f0; border-radius:3px; margin-top:5px; overflow:hidden; }
        .progress-fill { height:100%; border-radius:3px; background:linear-gradient(90deg,var(--primary-light),var(--accent)); }
        ::-webkit-scrollbar{width:5px;height:5px} ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:3px}
        @media(max-width:768px){:root{--sidebar-w:0px}.sidebar{display:none}.main-content{margin-left:0}}
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><img src="<?php echo e(asset('images/logo-usd.png')); ?>" alt="Logo USD"></div>
        <div class="brand-text"><h2>Cerdas Humanis</h2><p>SPK Beasiswa MOORA</p></div>
    </div>
    <div class="sidebar-user">
        <div class="user-avatar"><?php echo e(strtoupper(substr(auth()->user()->name,0,1))); ?></div>
        <div class="user-info">
            <p><?php echo e(auth()->user()->name); ?></p>
            <span><?php echo e(strtoupper(auth()->user()->role)); ?></span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Menu Utama</div>
        <?php if(auth()->user()->isBaa()): ?>
            <a href="<?php echo e(route('baa.dashboard')); ?>"        class="nav-item <?php echo e(request()->routeIs('baa.dashboard')    ? 'active' : ''); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="<?php echo e(route('baa.calon.index')); ?>"      class="nav-item <?php echo e(request()->routeIs('baa.calon.*')      ? 'active' : ''); ?>"><i class="fas fa-user-plus"></i> Input Calon</a>
            <a href="<?php echo e(route('baa.dokumen.index')); ?>"    class="nav-item <?php echo e(request()->routeIs('baa.dokumen.*')    ? 'active' : ''); ?>"><i class="fas fa-folder-open"></i> Dokumen</a>
        <?php elseif(auth()->user()->isBapsi()): ?>
            <a href="<?php echo e(route('bapsi.dashboard')); ?>"      class="nav-item <?php echo e(request()->routeIs('bapsi.dashboard')       ? 'active' : ''); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="<?php echo e(route('bapsi.verifikasi.index')); ?>" class="nav-item <?php echo e(request()->routeIs('bapsi.verifikasi.*') ? 'active' : ''); ?>"><i class="fas fa-clipboard-check"></i> Diproses</a>
            <a href="<?php echo e(route('bapsi.kriteria.index')); ?>" class="nav-item <?php echo e(request()->routeIs('bapsi.kriteria.*')     ? 'active' : ''); ?>"><i class="fas fa-sliders-h"></i> Kriteria</a>
            <a href="<?php echo e(route('bapsi.kuota.index')); ?>"    class="nav-item <?php echo e(request()->routeIs('bapsi.kuota.*')        ? 'active' : ''); ?>"><i class="fas fa-chart-pie"></i> Kuota Prodi</a>
            <a href="<?php echo e(route('bapsi.moora.index')); ?>"   class="nav-item <?php echo e(request()->routeIs('bapsi.moora.*')        ? 'active' : ''); ?>"><i class="fas fa-calculator"></i> Hitung MOORA</a>
        <?php elseif(auth()->user()->isLkm()): ?>
            <a href="<?php echo e(route('lkm.dashboard')); ?>"        class="nav-item <?php echo e(request()->routeIs('lkm.dashboard')        ? 'active' : ''); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="<?php echo e(route('lkm.ranking.index')); ?>"    class="nav-item <?php echo e(request()->routeIs('lkm.ranking.*')        ? 'active' : ''); ?>"><i class="fas fa-trophy"></i> Ranking MOORA</a>
            <a href="<?php echo e(route('lkm.keputusan.index')); ?>"  class="nav-item <?php echo e(request()->routeIs('lkm.keputusan.*')      ? 'active' : ''); ?>"><i class="fas fa-check-double"></i> Tetapkan Penerima</a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-footer">
        
        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           style="display:flex;align-items:center;gap:8px;color:rgba(255,255,255,0.5);
                  font-size:0.82rem;cursor:pointer;text-decoration:none;
                  padding:8px 0;transition:color .2s;"
           onmouseover="this.style.color='#f87171'"
           onmouseout="this.style.color='rgba(255,255,255,0.5)'">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
        <form id="logout-form" method="POST" action="<?php echo e(route('logout')); ?>" style="display:none;">
            <?php echo csrf_field(); ?>
        </form>
    </div>
</aside>

<div class="main-content">
    <div class="topbar">
        <div>
            <h2><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h2>
            <span style="font-size:0.72rem;color:var(--text-muted)">SPK Beasiswa Cerdas Humanis</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
            <span style="font-size:0.8rem;color:var(--text-muted)"><?php echo e(auth()->user()->name); ?></span>
        </div>
    </div>
    <div class="page-content">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/layouts/app.blade.php ENDPATH**/ ?>
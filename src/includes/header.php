<style>
  html, body{margin:0;padding:0;}
  .ues-header{background:#7a0b0b;color:#fff;position:sticky;top:0;left:0;right:0;z-index:1000;width:100%;box-sizing:border-box;margin:0;box-shadow:0 1px 0 rgba(255,255,255,0.15);} 
  .ues-header .ues-container{height:56px;display:flex;align-items:center;gap:16px;padding:0 20px;width:100%;max-width:1200px;margin:0 auto;}
  .ues-header .ues-brand{display:flex;align-items:center;color:#fff;text-decoration:none;gap:10px;}
  .ues-header .ues-brand img{height:28px;display:block;object-fit:contain;}
  .ues-header .ues-brand span{font-weight:600;white-space:nowrap;}
  .ues-header .ues-right{margin-left:auto;display:flex;align-items:center;gap:16px;}
  .ues-header .tag{opacity:.9;white-space:nowrap;}
  .ues-header .pill{background:#fdebd2;color:#7a0b0b;padding:6px 10px;border-radius:10px;text-decoration:none;font-weight:600;}
</style>
<header class="ues-header">
  <div class="ues-container">
    <a href="index.php" class="ues-brand">
      <img src="img/logo_bl_v2.png" alt="Universidad de El Salvador">
    </a>
    <div class="ues-right">
      <span class="tag">Sistema de gestión de prácticas profesionales– UES</span>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=logout" class="pill" title="Cerrar sesión">Salir</a>
      <?php endif; ?>
    </div>
  </div>
</header>

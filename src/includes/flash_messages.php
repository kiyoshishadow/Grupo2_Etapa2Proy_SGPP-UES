<?php
require_once __DIR__ . '/flash.php';
$__flash_messages = flash_pull_all();
if (!empty($__flash_messages)): ?>
  <div class="flash-container mb-3">
    <?php foreach ($__flash_messages as $__flash_item):
      $type = htmlspecialchars($__flash_item['type'] ?? 'info', ENT_QUOTES, 'UTF-8');
      $message = htmlspecialchars($__flash_item['message'] ?? '', ENT_QUOTES, 'UTF-8');
    ?>
      <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php unset($__flash_messages, $__flash_item, $type, $message); ?>

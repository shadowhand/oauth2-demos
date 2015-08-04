<h1>An Error Occurred</h1>
<dl>
<dt>Input</dt>
<?php foreach ($input as $key => $value): ?>
<dd><?= $this->e($key) ?>: <?= $this->e($value) ?></dd>
<?php endforeach ?>
</dl>
<dl>
<?php if (!empty($messages)): ?>
<dt>Messages</dt>
<?php foreach ($messages as $msg): ?>
<dd><?= $this->e($msg) ?></dd>
<?php endforeach ?>
</dl>
<?php endif ?>

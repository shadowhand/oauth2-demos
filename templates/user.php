<h1>Your User Information</h1>
<p>You logged into <?= $this->e($provider) ?> as user <tt><?= $this->e($id) ?></tt>. Your user details:</p>
<dl>
    <?php foreach ($details as $key => $value): ?>
    <dt><?= $this->e($key) ?></dt>
    <dd><?= $this->e(var_export($value, true)) ?></dd>
    <?php endforeach; ?>
</dl>
<p><strong>Would you like to <a href="/logout/<?= $this->e($provider) ?>">logout</a> or <a href="/">login to another provider</a>?</strong></p>

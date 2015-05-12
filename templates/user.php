<h1>Your User Information</h1>
<p>You logged into <?= $this->e($provider) ?> as user <tt><?= $this->e($id) ?></tt>. Your user details:</p>
<dl>
    <?php foreach ($details as $key => $value): ?>
    <dt><?= $this->e($key) ?></dt>
    <dd><?= $this->e($value) ?></dd>
    <?php endforeach; ?>
</dl>

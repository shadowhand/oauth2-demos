<h1>Login With:</h1>
<ul>
<?php foreach ($providers as $provider): ?>
    <li><a href="/login/<?= $this->e($provider) ?>"><?= $this->e($provider) ?></li>
<?php endforeach; ?>
</ul>

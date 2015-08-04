<h1>Login With:</h1>
<ul>
<?php foreach ($providers as $provider => $connected): ?>
    <li>
        <a href="/connect/<?= $this->e($provider) ?>"><?= $this->e($provider) ?></a>
        <?php if ($connected): ?>
        <small><a href="/user/<?= $this->e($provider) ?>">info</a></small>
        <?php endif ?>
    </li>
<?php endforeach; ?>
</ul>

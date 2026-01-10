<?php
require_once __DIR__ . '/../../templates/header.php';
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Tableau de bord</h1>
    <p class="text-gray-600 mt-2">Voici un aperçu de vos activités aujourd'hui.</p>
</div>

<div class="mb-8">
    <h2 class="text-xl font-semibold text-gray-800">
        Bienvenue, <?php echo htmlspecialchars((string)($user['first_name'] ?? 'Utilisateur')); ?>!
    </h2>
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <?php foreach (($stats ?? []) as $stat): ?>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="<?php echo htmlspecialchars((string)($stat['color'] ?? 'bg-blue-500')); ?> rounded-lg p-3">
                            <i class="<?php echo htmlspecialchars((string)($stat['icon'] ?? 'fas fa-chart-line')); ?> text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate"><?php echo htmlspecialchars((string)($stat['title'] ?? '')); ?></dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900"><?php echo htmlspecialchars((string)($stat['value'] ?? '')); ?></div>
                                <?php if (isset($stat['change'])): ?>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                        <i class="fas fa-arrow-up text-xs mr-1"></i>
                                        <?php echo htmlspecialchars((string)$stat['change']); ?>
                                    </div>
                                <?php endif; ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>

<?php
require_once __DIR__ . '/../../templates/header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mon Planning</h1>
            <p class="text-sm text-gray-600">Vos rendez-vous</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/dashboard.php" class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">Retour</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Rendez-vous</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($appointments ?? [])): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">Aucun rendez-vous</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach (($appointments ?? []) as $a): ?>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($a['date'] ?? '')); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($a['time'] ?? '')); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($a['patient_id'] ?? '')); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($a['status'] ?? '')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>

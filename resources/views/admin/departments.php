<?php
require_once __DIR__ . '/../../templates/header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestion des départements</h1>
            <p class="text-sm text-gray-600">Ajouter et gérer les départements de la clinique</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/dashboard.php" class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">Retour</a>
            <a href="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/logout.php" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Déconnexion</a>
        </div>
    </div>

    <?php if (($error ?? '') !== ''): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
            <?php echo htmlspecialchars((string)$error); ?>
        </div>
    <?php endif; ?>

    <?php if (($success ?? '') !== ''): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            <?php echo htmlspecialchars((string)$success); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow rounded-xl p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Ajouter un département</h2>
        <form method="POST" action="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/admin/departments.php" class="grid grid-cols-1 gap-4">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string)($csrf_token ?? '')); ?>">
            <input type="hidden" name="action" value="create">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du département *</label>
                <input name="name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars((string)($_POST['name'] ?? '')); ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars((string)($_POST['description'] ?? '')); ?></textarea>
            </div>

            <div>
                <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Ajouter le département</button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Liste des départements</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de médecins</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach (($departments ?? []) as $department): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($department['id'] ?? '')); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium"><?php echo htmlspecialchars((string)($department['name'] ?? '')); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($department['description'] ?? '')); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($department['doctor_count'] ?? 0)); ?></td>
                            <td class="px-6 py-4 text-sm">
                                <form method="POST" action="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/admin/departments.php" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string)($csrf_token ?? '')); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)($department['id'] ?? '')); ?>">
                                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce département ?')" class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700 text-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>

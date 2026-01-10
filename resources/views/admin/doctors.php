<?php
require_once __DIR__ . '/../../templates/header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestion des médecins</h1>
            <p class="text-sm text-gray-600">Ajouter et gérer les médecins de la clinique</p>
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
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Ajouter un médecin</h2>
        <form method="POST" action="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/admin/doctors.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string)($csrf_token ?? '')); ?>">
            <input type="hidden" name="action" value="create">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input name="last_name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars((string)($_POST['last_name'] ?? '')); ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                <input name="first_name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars((string)($_POST['first_name'] ?? '')); ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input name="email" type="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars((string)($_POST['email'] ?? '')); ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur</label>
                <input name="username" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars((string)($_POST['username'] ?? '')); ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input name="password" type="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Spécialité</label>
                <input name="specialty" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars((string)($_POST['specialty'] ?? '')); ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input name="phone" type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars((string)($_POST['phone'] ?? '')); ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <input name="address" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars((string)($_POST['address'] ?? '')); ?>">
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Ajouter le médecin</button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Liste des médecins</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spécialité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach (($doctors ?? []) as $doctor): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($doctor['id'] ?? '')); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <?php echo htmlspecialchars((string)($doctor['first_name'] ?? '')); ?> 
                                <?php echo htmlspecialchars((string)($doctor['last_name'] ?? '')); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($doctor['email'] ?? '')); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($doctor['specialty'] ?? '')); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars((string)($doctor['phone'] ?? '')); ?></td>
                            <td class="px-6 py-4 text-sm">
                                <form method="POST" action="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/admin/doctors.php" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string)($csrf_token ?? '')); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)($doctor['id'] ?? '')); ?>">
                                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce médecin ?')" class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700 text-sm">Supprimer</button>
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

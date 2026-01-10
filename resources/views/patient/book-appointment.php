<?php
require_once __DIR__ . '/../../templates/header.php';
?>

<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Prendre un Rendez-vous</h1>
            <p class="text-sm text-gray-600">Choisissez une date, une heure et un médecin</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/patient/appointments.php" class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">Retour</a>
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

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/patient/book-appointment.php" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Médecin *</label>
                <select name="doctor_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Choisir...</option>
                    <?php foreach (($doctors ?? []) as $d): ?>
                        <option value="<?php echo htmlspecialchars((string)($d['id'] ?? '')); ?>">
                            <?php echo htmlspecialchars((string)($d['first_name'] ?? '') . ' ' . (string)($d['last_name'] ?? '')); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                    <input type="date" name="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Heure *</label>
                    <input type="time" name="time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motif</label>
                <textarea name="reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
            </div>

            <div>
                <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Créer</button>
            </div>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>

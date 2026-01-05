<?php
session_start();
require_once '../index.php';

if (Auth::check()) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (Auth::login($email, $password)) {
        header('Location: /dashboard.php');
        exit;
    } else {
        $error = 'Email ou mot de passe incorrect.';
    }
}
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="flex justify-center">
                <i class="fas fa-clinic-medical text-5xl text-blue-600"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Unity Care Clinic V2
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Système de gestion médicale
            </p>
        </div>
        
        <?php if ($error): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700"><?php echo htmlspecialchars($error); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <form class="mt-8 space-y-6" action="/login.php" method="POST">
                        
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Adresse email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="Adresse email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                </div>
                <div>
                    <label for="password" class="sr-only">Mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="Mot de passe">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="togglePassword">
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Se souvenir de moi
                    </label>
                </div>

                <div class="text-sm">
                    <a href="/forgot-password.php" class="font-medium text-blue-600 hover:text-blue-500">
                        Mot de passe oublié?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt"></i>
                    </span>
                    Se connecter
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Démonstration: utilisez admin@clinic.com / password
                </p>
                <div class="mt-4 grid grid-cols-3 gap-3">
                    <button type="button" onclick="fillCredentials('admin')" 
                            class="px-3 py-2 bg-red-100 text-red-700 text-xs font-medium rounded hover:bg-red-200">
                        Admin
                    </button>
                    <button type="button" onclick="fillCredentials('doctor')" 
                            class="px-3 py-2 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200">
                        Docteur
                    </button>
                    <button type="button" onclick="fillCredentials('patient')" 
                            class="px-3 py-2 bg-green-100 text-green-700 text-xs font-medium rounded hover:bg-green-200">
                        Patient
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

function fillCredentials(role) {
    const credentials = {
        admin: { email: 'admin@clinic.com', password: 'password' },
        doctor: { email: 'doctor@clinic.com', password: 'password' },
        patient: { email: 'patient@clinic.com', password: 'password' }
    };
    
    document.getElementById('email').value = credentials[role].email;
    document.getElementById('password').value = credentials[role].password;
}
</script>

<?php require_once '../templates/footer.php'; ?>
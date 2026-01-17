<!DOCTYPE html>
<html lang="ca" class="dark">
<head>
    <meta charset="utf-8">
    <title>Pagament completat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center">
    <div class="text-center p-10 bg-slate-800 rounded-3xl border border-green-500/30 shadow-2xl max-w-lg animate-bounce-in">
        <div class="text-6xl mb-6">🎉</div>
        <h1 class="text-3xl font-bold mb-4 text-green-400">Pagament realitzat amb èxit!</h1>
        <p class="text-slate-300 mb-8">Els llibres s'han afegit a la teva biblioteca.</p>
        <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-8 rounded-full transition">Tornar a l'inici</a>
    </div>
</body>
</html>
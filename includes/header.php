<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – Power Level</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#8b5cf6">
    <link rel="icon" type="image/png" href="/assets/icons/icon-192.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Source+Code+Pro:ital,wght@0,200..900;1,200..900&display=swap');
        body {
            font-family: "Source Code Pro", monospace;
            font-size: 16px;
        }
        .max-w-7xl.mx-auto.bg-gray-900.border.border-purple-600.rounded-2xl.shadow-2xl.p-8 {
            backdrop-filter: blur(10px);
            background-color: rgb(17 24 39 / 50%);
        }       
        .bg-gray-800.rounded-xl {
            backdrop-filter: blur(10px);
            background-color: rgb(17 24 39 / 50%);  
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: "Montserrat", sans-serif;       
        }
    </style>
        <style>
        .exercise-item.locked {
            opacity: 0.5;
            pointer-events: none;
        }

        .exercise-item.unlocked {
            opacity: 1;
            pointer-events: auto;
        }
        /* Personnalisation des checkboxes */
input.repos-checkbox,       
input.exercise-checkbox {
    appearance: none;
    -webkit-appearance: none;
    background-color:rgba(0, 0, 0, 0.35); /* bg-gray-800 */
    border: 1px solid rgba(196, 196, 196, 0.19); /* purple-500 */
    width: 20px;
    height: 20px;
    border-radius: 6px;
    display: inline-block;
    position: relative;
    transition: all 0.2s ease;
    cursor: pointer;
}
input.repos-checkbox:checked + .label-text {
  color: rgba(34, 197, 94, 0.43)!important; /* green-500 transparent */
}

input.repos-checkbox:checked,
input.exercise-checkbox:checked {
    background-color:rgba(34, 197, 94, 0.43); /* green-500 */
    border-color: #22c55e;
}
input.repos-checkbox:checked::after,
input.exercise-checkbox:checked::after {
    content: "✓";
    color: white;
    font-weight: bold;
    position: absolute;
    top: 11px;
    left: 50%;
    transform: translate(-50%, -58%);
    font-size: 12px;
}
input.repos-checkbox:disabled,
input.exercise-checkbox:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}

    </style>
</head>

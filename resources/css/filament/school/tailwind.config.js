import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/School/**/*.php',
        './resources/views/filament/school/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    plugins: [
        require('flowbite/plugin')
    ],
}

var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

//elixir(function(mix) {
//    mix.less('app.less');
//});

// Merge style script
elixir(function(mix) {
    mix.styles([
        "bootstrap.css",
        "animate.css",
        "font-awesome.css",
        "simple-line-icons.css",
        "font.css",
        "main.css"
    ]);
});

// Merge javascript files
elixir(function(mix) {
    mix.scripts([
        "ui-load.js",
        "ui-jp.config.js",
        "ui-jp.js",
        "ui-nav.js",
        "ui-toggle.js"
    ]);
});

// 压缩代码
elixir(function(mix) {
    mix.version(["css/all.css", "js/all.js"]);
});

elixir(function(mix) {
    mix.copy('resources/assets/fonts', 'public/build/fonts');
    mix.copy('resources/assets/img', 'public/build/img');
    mix.copy('resources/assets/js/vendor', 'public/build/js');
});
let Encore = require('@symfony/webpack-encore');

let vendorJs = [
    './web/assets/js/vendor.js',
    './web/assets/js/core/libraries/jquery.min.js',
    './web/assets/js/core/libraries/bootstrap.min.js',
    './web/assets/js/plugins/loaders/blockui.min.js',
    './web/assets/js/plugins/ui/nicescroll.min.js',
    './web/assets/js/plugins/ui/drilldown.js',
    './web/assets/js/plugins/ui/ripple.min.js',
    './web/assets/js/plugins/forms/validation/validate.min.js',
    './web/assets/js/plugins/forms/styling/uniform.min.js',
    './web/assets/js/plugins/forms/styling/switchery.min.js',
    './web/assets/js/plugins/forms/inputs/touchspin.min.js',
    './web/assets/js/plugins/forms/selects/select2.min.js',
    './web/assets/js/core/app.js'
];
let vendorCSS = [
    './web/assets/css/icons/icomoon/styles.css',
    './web/assets/css/bootstrap.css',
    './web/assets/css/core.css',
    './web/assets/css/components.css',
    './web/assets/css/colors.css',
    './web/assets/css/custom.css'
];

let frontCSS = [
    './web/assets/css/icons/icomoon/styles.css',
    './web/assets/css/demo.css',
    './web/assets/css/colors.css',
    './web/assets/css/dashboard.css',
    './web/assets/css/bootstrap_v4/bootstrap.min.css',
    './web/assets/css/bootstrap_v4/bootstrap-grid.min.css',
    './web/assets/css/bootstrap_v4/bootstrap-reboot.min.css',
    
];

let frontJs = [
    './web/assets/css/bootstrap_v4/bootstrap.bundle.min.js',
    './web/assets/css/bootstrap_v4/bootstrap.min.js',
    './web/assets/css/bootstrap_v4/callapse.js',
    './web/assets/css/bootstrap_v4/carousel.js',
    './web/assets/css/bootstrap_v4/transitions.js',
    './web/assets/css/bootstrap_v4/bootstrap-datetimepicker.js',
]

let loginJs = vendorJs.concat([
    './web/assets/js/pages/login_validation.js'
]);

let homeJs =vendorJs.concat( [
    './web/assets/js/pages/home.js'
]);

let trainingJs = [
    './web/assets/js/pages/learning_detailed.js',
    './web/assets/js/plugins/ui/fullcalendar/fullcalendar.min.js',
    './web/assets/js/plugins/ui/moment/moment.min.js'
];

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .addEntry('js/home', homeJs)
    .addEntry('js/login', loginJs)
    .addEntry('js/front/script', frontJs)
    .createSharedEntry('js/app', vendorJs)
    // .addEntry('js/training', trainingJs)
    .addStyleEntry('css/style', vendorCSS)
    .addStyleEntry('css/front/style', frontCSS)
    .enableVersioning(false)
    // .enableSassLoader()
    .enableReactPreset()
    .autoProvidejQuery()
    // .addPlugin(new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/))
    .enableSourceMaps(!Encore.isProduction())
;
module.exports = Encore.getWebpackConfig();

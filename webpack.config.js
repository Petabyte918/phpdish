const Encore = require('@symfony/webpack-encore');
const path = require('path');
const glob = require('glob');
const buildPath = 'web/build';

Encore
    .setOutputPath(buildPath)
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()
    .enableSassLoader()
    .autoProvideVariables({
        '$': 'jquery',
        'jQuery': 'jquery',
        "window.jQuery": "jquery",
        '_': 'lodash',
        "window.lodash": "lodash"
    })
    .enableVersioning(true)
    .enableSourceMaps(!Encore.isProduction());

if (!Encore.isProduction()) {
    Encore.enableVersioning(false);
    Encore.setPublicPath('http://127.0.0.1:8080')
        .setManifestKeyPrefix('build/');
}
const config = {
    buildPath: path.resolve(buildPath),
    assetsPath: path.resolve("./assets"),
    cssPath: path.resolve("./assets/css"),
    jsPath: path.resolve("./assets/js"),
    modulesPath: path.resolve("./assets/modules"),
    pluginsPath: path.resolve("./assets/plugins"),
    mapPath: path.resolve(buildPath + '/manifest.json')
};

//add js entries
function findEntries(entryPath){
    const entries = {};
    const srcDirName = entryPath + '/**/*.js';
    glob.sync(srcDirName).forEach(function (filepath) {
        const name = filepath.slice(filepath.indexOf('js'), -3);
        entries[name] = filepath;
    });
    return entries;
}
const foundEntries = findEntries(config.jsPath);
for (const entryName in foundEntries) {
    Encore.addEntry(entryName, foundEntries[entryName]);
}
//add shared entry
Encore.createSharedEntry('common', [
    path.resolve(config.modulesPath, 'common.js'),
    path.resolve(config.modulesPath, 'editor.js')
]);

//add style entries
Encore.addStyleEntry('css/style', config.cssPath + '/_style.css');

//final webpack config
const webpackConfig = Encore.getWebpackConfig();
webpackConfig.resolve.alias = {
    css: config.cssPath,
    js: config.jsPath,
    module: config.modulesPath,
    plugin: config.pluginsPath
};
webpackConfig.externals = {
    'jquery': 'window.$',
    'lodash': 'window._',
};
module.exports = webpackConfig;
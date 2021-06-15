const mix = require('laravel-mix');
require('mix-tailwindcss');

const path = require('path');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .webpackConfig({
        resolve: {
            alias: {
                '~': path.resolve(__dirname, 'resources/js/'),
            },
        },
    })
    .tailwind();

/* CKEDITOR */
const CKEStyles = require('@ckeditor/ckeditor5-dev-utils').styles;
const CKEditorWebpackPlugin = require('@ckeditor/ckeditor5-dev-webpack-plugin');

const CKERegex = {
    svg: /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
    css: /ckeditor5-[^/\\]+[/\\].+\.css$/,
};

Mix.listen('configReady', (webpackConfig) => {
    const { rules } = webpackConfig.module;
    const targetSVG = /(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/;
    const targetFont = /(\.(woff2?|ttf|eot|otf)$|font.*\.svg$)/;
    const targetCSS = /\.css$/;

    // Exclude CK Editor regex from mix's default rules
    for (const rule of rules) {
        if (rule.test.toString() === targetSVG.toString()) {
            rule.exclude = CKERegex.svg;
        } else if (rule.test.toString() === targetFont.toString()) {
            rule.exclude = CKERegex.svg;
        } else if (rule.test.toString() === targetCSS.toString()) {
            rule.exclude = CKERegex.css;
        }
    }
});

/**
 * Webpack Config for CK Editor
 */
mix.webpackConfig({
    module: {
        rules: [
            {
                test: CKERegex.svg,
                use: ['raw-loader'],
            },
            {
                test: CKERegex.css,
                use: [
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: CKEStyles.getPostCssConfig({
                                themeImporter: {
                                    themePath: require.resolve('@ckeditor/ckeditor5-theme-lark'),
                                },
                                minify: true,
                            }),
                        },
                    },
                ],
            },
        ],
    },
});

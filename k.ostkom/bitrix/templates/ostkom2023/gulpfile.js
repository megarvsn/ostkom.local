"use strict";

const { src, dest, parallel, series, watch } = require("gulp");
const file_include = require("gulp-file-include");
const plumber = require("gulp-plumber");
const uglify = require("gulp-uglify");
const rename = require("gulp-rename");
const sass = require("gulp-sass");
const autoprefixer = require("gulp-autoprefixer");
const cleancss = require("gulp-clean-css");
const newer = require("gulp-newer");
const imagemin = require("gulp-imagemin");
const pngquant = require("imagemin-pngquant");

// Path
const srcDir = "./";
const appDir = "./";

const arrStylesCSS = [
  /*{src: 'assets/sass/styles.scss', dest_path: 'css/', dest_name: 'styles.min.css'},*/
];

const arrComponentsCSS = [
  /*{src: 'assets/sass/components/c-breadcrumb.scss', dest_path: 'components/bitrix/breadcrumb/.default/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-cookie.scss', dest_path: 'components/baza23/local.empty/header--section-cookie/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-filter-home-title.scss', dest_path: '../../components/baza23/local.filter.home/templates/.default/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-filter-home-title.scss', dest_path: '../../components/baza23/local.filter.home/templates/ajax/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-filter-catalog-title.scss', dest_path: '../../components/baza23/local.filter.catalog/templates/.default/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-filter-catalog-title.scss', dest_path: '../../components/baza23/local.filter.catalog/templates/ajax/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-header-favourites.scss', dest_path: 'components/baza23/block.catalog.favourites.line/header/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-header-desktop.scss', dest_path: 'components/baza23/local.empty/header--desktop/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-header-menu-main.scss', dest_path: 'components/bitrix/menu/header--main/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-header-menu-mobile.scss', dest_path: 'components/baza23/local.empty/header--mobile-menu-button/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-header-mobile.scss', dest_path: 'components/baza23/local.empty/header--mobile/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-footer-menu-main.scss', dest_path: 'components/bitrix/menu/footer--main/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-page-home-about.scss', dest_path: 'components/baza23/local.empty/page-home--about/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-section-deal-types.scss', dest_path: 'components/bitrix/news.list/section--deal-types/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-section-logo.scss', dest_path: 'components/baza23/local.empty/section--logo/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-section-news.scss', dest_path: 'components/bitrix/news.list/section--news/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-section-partners.scss', dest_path: 'components/bitrix/news.list/section--partners/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-section-subtypes.scss', dest_path: 'components/bitrix/catalog.section.list/section--subtypes/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-section-tab-forms.scss', dest_path: 'components/baza23/local.empty/section--tab-forms', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-section-tab-links.scss', dest_path: 'components/baza23/local.empty/section--tab-links', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-wf-consultation.scss', dest_path: 'components/bitrix/form.result.new/consultation/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-wf-footer-subscribe.scss', dest_path: 'components/bitrix/form.result.new/footer--subscribe/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-wf-estate-pick-up.scss', dest_path: 'components/bitrix/form.result.new/estate-pick-up/', dest_name: 'style.css'},
    {src: 'assets/sass/components/c-wf-estate-sale-rent.scss', dest_path: 'components/bitrix/form.result.new/estate-sale-rent/', dest_name: 'style.css'},*/
];

const arrPagesCSS = [
  /*{src: 'assets/sass/gulp_pages/home.scss', dest_path: 'css/pages/', dest_name: 'home.min.css'},
    {src: 'assets/sass/gulp_pages/rent.scss', dest_path: 'css/pages/', dest_name: 'rent.min.css'},
    {src: 'assets/sass/gulp_pages/sale.scss', dest_path: 'css/pages/', dest_name: 'sale.min.css'},
    {src: 'assets/sass/gulp_pages/404.scss', dest_path: 'css/pages/', dest_name: '404.min.css'},*/
];

const arrVendorsCSS = [
  /*{src: 'assets/sass/gulp_vendors/bootstrap.scss', dest_path: '../../../local/vendors/bootstrap5/', dest_name: 'bootstrap.min.css'},*/
];

const arrScriptsJS = [
  /*{src: 'assets/js/script.js', dest_path: 'js/', dest_name: 'script.min.js'},
    {src: 'assets/js/web-form.js', dest_path: 'js/', dest_name: 'web-form.min.js'},
    {src: 'assets/js/web-form-modal.js', dest_path: 'js/', dest_name: 'web-form-modal.min.js'},*/
];

const arrComponentsJS = [];
const arrPagesJS = [];

function jsf_processCSS(p_src, p_destPath, p_destName) {
  return src(srcDir + p_src)
    .pipe(plumber())
    .pipe(
      sass({
        outputStyle: "compressed",
      }).on("error", sass.logError)
    )
    .pipe(
      autoprefixer({
        cascade: false,
      })
    )
    .pipe(
      cleancss({
        level: { 1: { specialComments: 0 } } /* , format: 'beautify' */,
      })
    )
    .pipe(rename(p_destName))
    .pipe(dest(appDir + p_destPath));
}

// РЎР±РѕСЂРєР° styles.css
async function stylesCSS() {
  const ret = [];
  for (var i = 0, max = arrStylesCSS.length; i < max; i++) {
    const result = jsf_processCSS(
      arrStylesCSS[i].src,
      arrStylesCSS[i].dest_path,
      arrStylesCSS[i].dest_name
    );
    ret.push(result);
  }
  return ret;
}

// РЎР±РѕСЂРєР° СЃС‚РёР»РµР№ РґР»СЏ РєРѕРјРїРѕРЅРµРЅС‚РѕРІ Р‘РёС‚СЂРёРєСЃ
async function componentsCSS() {
  const ret = [];
  for (var i = 0, max = arrComponentsCSS.length; i < max; i++) {
    const result = jsf_processCSS(
      arrComponentsCSS[i].src,
      arrComponentsCSS[i].dest_path,
      arrComponentsCSS[i].dest_name
    );
    ret.push(result);
  }
  return ret;
}

// РЎР±РѕСЂРєР° СЃС‚РёР»РµР№ РґР»СЏ СЃС‚СЂР°РЅРёС† СЃР°Р№С‚Р°
async function pagesCSS() {
  const ret = [];
  for (var i = 0, max = arrPagesCSS.length; i < max; i++) {
    const result = jsf_processCSS(
      arrPagesCSS[i].src,
      arrPagesCSS[i].dest_path,
      arrPagesCSS[i].dest_name
    );
    ret.push(result);
  }
  return ret;
}

// РЎР±РѕСЂРєР° СЃС‚РёР»РµР№ РґР»СЏ JS РїР»Р°РіРёРЅРѕРІ
function vendorsCSS() {
  const ret = [];
  for (var i = 0, max = arrVendorsCSS.length; i < max; i++) {
    const result = jsf_processCSS(
      arrVendorsCSS[i].src,
      arrVendorsCSS[i].dest_path,
      arrVendorsCSS[i].dest_name
    );
    ret.push(result);
  }
  return ret;
}

// РЎР±РѕСЂРєР° fonts.css
async function fontsCSS() {
  return jsf_processCSS("assets/sass/fonts.scss", "css/", "fonts.min.css");
}

function jsf_processJS(p_src, p_destPath, p_destName) {
  return src(srcDir + p_src)
    .pipe(plumber())
    .pipe(
      file_include({
        prefix: "@@",
        basepath: "@file",
      })
    )
    .pipe(uglify()) // РЎР¶РёРјР°РµРј JavaScript
    .pipe(rename(p_destName))
    .pipe(dest(appDir + p_destPath));
}

// РЎР±РѕСЂРєР° script.js
async function scriptJS() {
  const ret = [];
  for (var i = 0, max = arrScriptsJS.length; i < max; i++) {
    const result = jsf_processJS(
      arrScriptsJS[i].src,
      arrScriptsJS[i].dest_path,
      arrScriptsJS[i].dest_name
    );
    ret.push(result);
  }
  return ret;
}

// РЎР±РѕСЂРєР° СЃРєСЂРёРїС‚Р° РґР»СЏ РєРѕРјРїРѕРЅРµРЅС‚РѕРІ Р‘РёС‚СЂРёРєСЃ
async function componentsJS() {
  const ret = [];
  for (var i = 0, max = arrComponentsJS.length; i < max; i++) {
    const result = jsf_processJS(
      arrComponentsJS[i].src,
      arrComponentsJS[i].dest_path,
      arrComponentsJS[i].dest_name
    );
    ret.push(result);
  }
  return ret;
}

// РЎР±РѕСЂРєР° СЃРєСЂРёРїС‚Р° РґР»СЏ СЃС‚СЂР°РЅРёС† СЃР°Р№С‚Р°
async function pagesJS() {
  const ret = [];
  for (var i = 0, max = arrPagesJS.length; i < max; i++) {
    const result = jsf_processJS(
      arrPagesJS[i].src,
      arrPagesJS[i].dest_path,
      arrPagesJS[i].dest_name
    );
    ret.push(result);
  }
  return ret;
}

// РћРїС‚РёРјРёР·Р°С†РёСЏ РёР·РѕР±СЂР°Р¶РµРЅРёР№
function images() {
  return src(srcDir + "assets/img/**/*")
    .pipe(newer(srcDir + "assets/img/**/*")) // Р—Р°РїСѓСЃРєР°РµС‚ С‚Р°СЃРєРё С‚РѕР»СЊРєРѕ РґР»СЏ РёР·РјРµРЅРёРІС€РёС…СЃСЏ С„Р°Р№Р»РѕРІ
    .pipe(
      imagemin([
        imagemin.gifsicle({ interlaced: true }),
        imagemin.mozjpeg({ quality: 75, progressive: true }),
        imagemin.optipng({ optimizationLevel: 5 }),
        imagemin.svgo({
          plugins: [{ removeViewBox: true }, { cleanupIDs: true }],
        }),
        pngquant(),
      ])
    )
    .pipe(dest(appDir + "img/"));
}

function startwatch() {
  watch(srcDir + "assets/js/**/*", scriptJS);
  watch(
    srcDir + "assets/sass/**/*",
    series(stylesCSS, componentsCSS, pagesCSS)
  );
}

exports.scriptJS = scriptJS;
exports.stylesCSS = stylesCSS;
exports.componentsCSS = componentsCSS;
exports.pagesCSS = pagesCSS;
exports.vendorsCSS = vendorsCSS;
exports.fontsCSS = fontsCSS;
exports.componentsJS = componentsJS;
exports.pagesJS = pagesJS;
exports.images = images;

exports.default = parallel(
  stylesCSS,
  scriptJS,
  componentsCSS,
  pagesCSS,
  vendorsCSS,
  componentsJS,
  pagesJS,
  startwatch
);

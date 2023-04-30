"use strict";

const { src, dest, parallel, series, watch } = require("gulp");
const file_include = require("gulp-file-include");
const plumber = require("gulp-plumber");
const uglify = require("gulp-uglify");
const rename = require("gulp-rename");
const cleancss = require("gulp-clean-css");
const newer = require("gulp-newer");
const imagemin = require("gulp-imagemin");
const pngquant = require("imagemin-pngquant");
const browserSync = require("browser-sync");
const postcss = require("gulp-postcss");
const autoprefixer = require("autoprefixer");
const precss = require("precss");
const postcssPresetEnv = require("postcss-preset-env");
const clearfix = require("postcss-clearfix");
const mqpacker = require("css-mqpacker");
const cssnano = require("cssnano");
const sourcemaps = require("gulp-sourcemaps");
const removeSourcemaps = require("gulp-remove-sourcemaps");

// Path
const srcDir = "./local/templates/ostkom2023/assets/";
const appDir = "./local/templates/ostkom2023/";

const arrStylesCSS = [
  { src: "css/main.css", dest_path: "css/", dest_name: "styles.min.css" },
];
const arrScriptsJS = [
  { src: "js/main.js", dest_path: "js/", dest_name: "script.min.js" },
];

// function myServer() {
//   browserSync.init({
//     server: {
//       baseDir: appDir, // здесь указываем корневую папку для локального сервера
//     },
//     notify: false, // отключаем уведомления
//   });
//   watch(appDir + "**/*.html").on("change", browserSync.reload);
//   watch(appDir + "**/styles*.css").on("change", browserSync.reload);
//   watch(appDir + "**/script*.js").on("change", browserSync.reload);
// }

// Non GULP tasks functions
function jsf_processCSS(p_src, p_destPath, p_destName) {
  return src(srcDir + p_src)
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(
      postcss([
        clearfix, // Clearfix (clear: fix)
        precss, // Импорт, вложенность, пересменные, миксины как в SCSS
        postcssPresetEnv, // Новая версия cssnext. Преобразование CSS будущего в настоящий
        autoprefixer, // Добавляет префиксы для разных браузеров
        mqpacker(), // Группирует и перемещает все медиазапросы в конец файлва CSS
        cssnano({
          preset: ["default", { discardComments: { removeAll: true } }],
        }), // Минимизация CSS с удалением всех комментариев
      ])
    )
    .pipe(sourcemaps.write())
    .pipe(rename(p_destName))
    .pipe(dest(appDir + p_destPath));
}
function jsf_rmSourcemaps(p_destPath, p_destName) {
  return src(appDir + p_destPath + p_destName)
    .pipe(plumber())
    .pipe(removeSourcemaps())
    .pipe(dest(appDir + p_destPath));
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
    .pipe(uglify())
    .pipe(rename(p_destName))
    .pipe(dest(appDir + p_destPath));
}

// Gulp tasks
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

async function rmSourcemaps() {
  const ret = [];
  for (var i = 0, max = arrStylesCSS.length; i < max; i++) {
    const result = jsf_rmSourcemaps(
      arrStylesCSS[i].dest_path,
      arrStylesCSS[i].dest_name
    );
    ret.push(result);
  }
  return ret;
}

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

function images() {
  return src(srcDir + "img/**/*")
    .pipe(newer(srcDir + "img/**/*"))
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

exports.stylesCSS = stylesCSS;
exports.rmSourcemaps = rmSourcemaps;
exports.scriptJS = scriptJS;
exports.images = images;
//exports.myServer = myServer;

exports.default = function () {
  //myServer();
  watch(srcDir + "js/**/*", scriptJS);
  watch(srcDir + "css/**/*", stylesCSS);
};
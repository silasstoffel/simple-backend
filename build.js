/**
 * https://www.npmjs.com/package/fs-extra
 */
const watch = require('node-watch');
const path = require('path');
const fs = require('fs-extra');

const args = process.argv.slice(2);
const build_mode = typeof args[0] !== 'undefined' ? args[0] : '--dev';
const args_accept = ['--dev', '--prod'];
const path_build_dev = path.resolve('build');
const path_build_prod = path.resolve('bin');
const path_src = path.resolve('src');

if (args_accept.indexOf(build_mode) === -1) {
  console.error('\nArgumento inválido, informe --prod ou --dev\n');
  process.exit(1);
}

const buildWatch = () => {
  watch('src/', { recursive: true }, function(evt, name) {
    const file = path.resolve(name);
    const dest = file.replace(path_src, path_build_dev);
    console.log('%s changed.', file);
    fs.copy(file, dest)
      .then(() => {
      })
      .catch(err => {
        console.error(err);
      });
  });
};

if (build_mode === '--dev') {
  fs.copy(path_src, path_build_dev)
    .then(() => {
      console.log('Watch changes...');
      buildWatch();
    })
    .catch(err => {
      console.error(err);
    });
} else if (build_mode === '--prod') {
  let date = new Date();
  let date_format = `${date.getDate()}-${date.getMonth() +
    1}-${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
  console.log(`> Starting ${date_format}`);
  const ignored_files = [
    path.resolve(path_build_prod, 'composer.json'),
    path.resolve(path_build_prod, 'composer.lock'),
    path.resolve(path_build_prod, 'config', 'config_ambiente.php'),
    path.resolve(path_build_prod, 'config', 'config_db.php'),
    path.resolve(path_build_prod, 'config', 'config_php.php')
  ];

  const clear = async file => {
    try {
      await fs.remove(file);
      console.log(`> Limpando ${file}`);
    } catch (err) {
      console.error(err);
    }
  };

  const removeConfigFiles = async file => {
    try {
      await fs.remove(file);
      console.log(`> Remove ${file}`);
    } catch (err) {
      console.error(err);
    }
  };

  const copy = async (src, dest) => {
    console.log(`> Copy ${src} to ${dest}`);
    try {
      await fs.copy(src, dest);

      for (let f of ignored_files) {
        await removeConfigFiles(f);
      }
    } catch (err) {
      console.error(err);
    }
  };

  const main = async () => {
    try {
      await clear(path_build_prod);
      await copy(path_src, path_build_prod);
      console.log('> Build success \\o/');
      let date = new Date();
      let date_format = `${date.getDate()}-${date.getMonth() +
        1}-${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
      console.log(`> Finish ${date_format}`);
    } catch (err) {
      console.error(err);
    }
  };

  main();
}

module.exports = {
  entry: {
    app: ['./resources/assets/sass/app.scss', './resources/assets/js/app.js']
  },
  port: 3003,
  html: false,
  assets_url: '/assets/dynamic/',  // Urls dans le fichier final
  assets_path: './public/assets/dynamic/', // ou build ?
  refresh: ['app/**/*.php', 'resources/views/**/*.php'] // Permet de forcer le rafraichissement du navigateur lors de la modification de ces fichiers
}

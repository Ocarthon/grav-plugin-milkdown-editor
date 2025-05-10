const path = require('path');

const entries = {
  'milkdown': './js/milkdown.js',
};

module.exports = {
  mode: 'production',
  entry: entries,
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: '[name].js',
    library: 'milkdown',
  },
  module: {
    rules: [
      {
        test: /\.css$/,
        use: ["style-loader", "css-loader"],
      },
    ]
  }
};

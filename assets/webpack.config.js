const path = require('path');
const fs = require('fs');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

/**
 * Recursively collect files by extension from a directory.
 *
 * @param {string} dir
 * @param {string} ext
 * @returns {string[]}
 */
function walkFiles(dir, ext) {
	const entries = [];
	const items = fs.existsSync(dir) ? fs.readdirSync(dir, { withFileTypes: true }) : [];

	items.forEach((item) => {
		const fullPath = path.join(dir, item.name);

		if (item.isDirectory()) {
			entries.push(...walkFiles(fullPath, ext));
			return;
		}

		if (item.isFile() && item.name.endsWith(ext)) {
			entries.push(fullPath);
		}
	});

	return entries;
}

/**
 * Build entry object from SCSS files inside /styles.
 *
 * @returns {Record<string, string>}
 */
function getScssEntries() {
	const stylesDir = path.resolve(__dirname, 'styles');
	const files = walkFiles(stylesDir, '.scss');

	return files.reduce((acc, file) => {
		const rel = path.relative(stylesDir, file);
		const name = rel.replace(/\.scss$/, '').replace(/\\/g, '/');
		acc[`styles/${name}`] = file;
		return acc;
	}, {});
}

/**
 * Build entry object from JS files inside /scripts.
 *
 * @returns {Record<string, string>}
 */
function getJsEntries() {
	const scriptsDir = path.resolve(__dirname, 'scripts');
	const files = walkFiles(scriptsDir, '.js');

	return files.reduce((acc, file) => {
		const rel = path.relative(scriptsDir, file);
		const name = rel.replace(/\.js$/, '').replace(/\\/g, '/');
		acc[`scripts/${name}`] = file;
		return acc;
	}, {});
}

module.exports = (env, argv) => {
	const isProd = argv.mode === 'production';

	return {
		context: __dirname,

		// Combine SCSS + JS entries.
		entry: {
			...getScssEntries(),
			...getJsEntries(),
		},

		output: {
			path: path.resolve(__dirname, 'dist'),
			filename: '[name].js',
			clean: true,
		},

		devtool: isProd ? false : 'source-map',

		module: {
			rules: [
				// JS (minimal modern processing).
				{
					test: /\.js$/,
					exclude: /node_modules/,
					use: {
						loader: 'babel-loader',
						options: {
							// Keep it minimal.
							presets: [
								[
									'@babel/preset-env',
									{
										// A sane modern baseline without overthinking.
										targets: 'defaults',
									},
								],
							],
						},
					},
				},

				// SCSS.
				{
					test: /\.s[ac]ss$/i,
					use: [
						MiniCssExtractPlugin.loader,
						{
							loader: 'css-loader',
							options: {
								sourceMap: !isProd,
							},
						},
						{
							loader: 'sass-loader',
							options: {
								sourceMap: !isProd,
							},
						},
					],
				},
			],
		},

		plugins: [
			new MiniCssExtractPlugin({
				// Because our SCSS entries are prefixed with "styles/"
				// this will output to dist/styles/...
				filename: '[name].css',
			}),
		],

		optimization: {
			minimize: isProd,
		},

		resolve: {
			extensions: ['.js', '.scss', '.css'],
		},

		stats: 'errors-warnings',
	};
};

module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  globals: {
    ProcessMaker: true,
  },
  extends: [
    'airbnb-base',
    'plugin:vue/recommended',
  ],
  overrides: [
    {
      env: {
        node: true,
      },
      files: [
        '.eslintrc.{js,cjs}',
      ],
      parserOptions: {
        sourceType: 'script',
      },
    },
  ],
  parserOptions: {
    ecmaVersion: 'latest',
    sourceType: 'module',
  },
  plugins: [
    'vue',
  ],
  rules: {
    'no-new': 0,
    'import/no-extraneous-dependencies': 1,
    'max-len': ['error', { code: 120 }],
    'vue/max-attributes-per-line': ['error', {
      singleline: {
        max: 3,
      },
      multiline: {
        max: 1,
      },
    }],
  },
  settings: {
    'import/resolver': {
      alias: {
        map: [
          ['@', './resources/js'],
        ],
        extensions: ['.js', '.vue'],
      },
    },
  },
};

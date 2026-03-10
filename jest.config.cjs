module.exports = {
    testEnvironment: 'jsdom',
    roots: ['<rootDir>/resources/js'],
    moduleFileExtensions: ['js', 'jsx', 'ts', 'tsx', 'vue'],
    testRegex: ['resources/js/.*\\.(test|spec)\\.[jt]sx?$'],
    testPathIgnorePatterns: ['/node_modules/', '<rootDir>/tests/'],
    moduleNameMapper: {
        '^@/(.*)$': '<rootDir>/resources/js/$1',
        '^ziggy-js$': '<rootDir>/vendor/tightenco/ziggy/dist/vue.mjs',
    },
    transform: {
        '^.+\\.vue$': '@vue/vue3-jest',
        '^.+\\.(js|jsx|ts|tsx)$': 'babel-jest',
    },
    transformIgnorePatterns: [
        'node_modules/(?!(ziggy-js)/)',
    ],
    setupFilesAfterEnv: [
        '<rootDir>/jest.setup.cjs',
    ],
};


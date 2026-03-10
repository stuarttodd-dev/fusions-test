const { config } = require('dotenv');

config({ path: '.env' });

globalThis.matchMedia = globalThis.matchMedia || function matchMedia() {
    return {
        matches: false,
        addListener() {},
        removeListener() {},
        addEventListener() {},
        removeEventListener() {},
        dispatchEvent() {
            return false;
        },
    };
};


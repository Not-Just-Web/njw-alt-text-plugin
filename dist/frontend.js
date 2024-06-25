/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/style/frontend.scss":
/*!*********************************!*\
  !*** ./src/style/frontend.scss ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/frontend/conf.ts":
/*!******************************!*\
  !*** ./src/frontend/conf.ts ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   apiEndpoint: () => (/* binding */ apiEndpoint),
/* harmony export */   elementId: () => (/* binding */ elementId),
/* harmony export */   getApiEndpoint: () => (/* binding */ getApiEndpoint)
/* harmony export */ });
const elementId = 'page-content';
const apiEndpoint = [
    {
        method: 'POST',
        name: 'sample_post', // 'sample' is the name of the endpoint
        url: 'https://jsonplaceholder.typicode.com/posts',
        body: {
            title: 'foo',
            body: 'bar',
            userId: 1,
        },
        headers: {
            'Content-type': 'application/json; charset=UTF-8',
        },
    },
    {
        name: 'sample_get',
        method: 'GET',
        url: 'https://jsonplaceholder.typicode.com/posts',
        body: {},
        headers: {},
    },
];
const getApiEndpoint = (name) => {
    return apiEndpoint.find((endpoint) => endpoint.name === name);
};


/***/ }),

/***/ "./src/frontend/index.ts":
/*!*******************************!*\
  !*** ./src/frontend/index.ts ***!
  \*******************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.a(module, async (__webpack_handle_async_dependencies__, __webpack_async_result__) => { try {
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helpers_util__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/util */ "./src/helpers/util.ts");
/* harmony import */ var _helpers_api__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers/api */ "./src/helpers/api.ts");
/* harmony import */ var _conf__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./conf */ "./src/frontend/conf.ts");
var _a;
// Replace the URLs on the anchor tags with the transformed URL



const urls = (0,_helpers_util__WEBPACK_IMPORTED_MODULE_0__.retrieveUrlsFromElement)(_conf__WEBPACK_IMPORTED_MODULE_2__.elementId);
const sample_api = (0,_conf__WEBPACK_IMPORTED_MODULE_2__.getApiEndpoint)('sample_post') || { url: '' };
// Send a POST request to the server to get the transformed URLs
const transformedUrlsResponse = await (0,_helpers_api__WEBPACK_IMPORTED_MODULE_1__.sendPostRequest)((sample_api === null || sample_api === void 0 ? void 0 : sample_api.url) ? sample_api.url : '', { urls });
// Extract the transformed URLs from the response
const transformedUrls = transformedUrlsResponse.data;
// Replace the URLs on the anchor tags with the transformed URLs
const anchorTags = (_a = document.getElementById(_conf__WEBPACK_IMPORTED_MODULE_2__.elementId)) === null || _a === void 0 ? void 0 : _a.getElementsByTagName('a');
if (anchorTags) {
    for (let i = 0; i < anchorTags.length; i++) {
        const anchorTag = anchorTags[i];
        const url = anchorTag.getAttribute('href');
        if (url && transformedUrls[url]) {
            anchorTag.setAttribute('href', transformedUrls[url]);
        }
    }
}

__webpack_async_result__();
} catch(e) { __webpack_async_result__(e); } }, 1);

/***/ }),

/***/ "./src/helpers/api.ts":
/*!****************************!*\
  !*** ./src/helpers/api.ts ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   fetchPosts: () => (/* binding */ fetchPosts),
/* harmony export */   sendGetRequest: () => (/* binding */ sendGetRequest),
/* harmony export */   sendPostRequest: () => (/* binding */ sendPostRequest)
/* harmony export */ });
const fetchPosts = async () => {
    try {
        const response = await fetch('https://jsonplaceholder.typicode.com/posts');
        if (!response.ok) {
            throw new Error('Error fetching posts');
        }
        const data = await response.json();
        return data;
    }
    catch (error) {
        console.error('Error fetching posts:', error);
        throw error;
    }
};
const sendPostRequest = async (url, data, headers = {}) => {
    return fetch(url, {
        method: 'POST',
        headers: Object.assign(Object.assign({}, headers), { 'Content-Type': 'application/json' }),
        body: JSON.stringify(data),
    });
};
const sendGetRequest = async (url, query = {}, headers = {}) => {
    var _a, _b;
    const queryString = new URLSearchParams(query).toString();
    const requestUrl = `${url}?${queryString}`;
    const response = await fetch(requestUrl, {
        method: 'GET',
        headers: Object.assign(Object.assign({}, headers), { 'X-WP-Nonce': (_a = window.njwVars) === null || _a === void 0 ? void 0 : _a.nonce, 'Access-Key': (_b = window.njwVars) === null || _b === void 0 ? void 0 : _b.accessKey, 'Content-Type': 'application/json' }),
    });
    const data = await response.json();
    const header = response.headers;
    return { data, header };
};


/***/ }),

/***/ "./src/helpers/util.ts":
/*!*****************************!*\
  !*** ./src/helpers/util.ts ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   retrieveUrlsFromElement: () => (/* binding */ retrieveUrlsFromElement)
/* harmony export */ });
// Function to retrieve all URLs from a specific HTML element
const retrieveUrlsFromElement = (elementId) => {
    const element = document.getElementById(elementId);
    if (element) {
        const urls = Array.from(element.getElementsByTagName('a')).map((a) => a.href);
        return urls;
    }
    return [];
};


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/async module */
/******/ 	(() => {
/******/ 		var webpackQueues = typeof Symbol === "function" ? Symbol("webpack queues") : "__webpack_queues__";
/******/ 		var webpackExports = typeof Symbol === "function" ? Symbol("webpack exports") : "__webpack_exports__";
/******/ 		var webpackError = typeof Symbol === "function" ? Symbol("webpack error") : "__webpack_error__";
/******/ 		var resolveQueue = (queue) => {
/******/ 			if(queue && queue.d < 1) {
/******/ 				queue.d = 1;
/******/ 				queue.forEach((fn) => (fn.r--));
/******/ 				queue.forEach((fn) => (fn.r-- ? fn.r++ : fn()));
/******/ 			}
/******/ 		}
/******/ 		var wrapDeps = (deps) => (deps.map((dep) => {
/******/ 			if(dep !== null && typeof dep === "object") {
/******/ 				if(dep[webpackQueues]) return dep;
/******/ 				if(dep.then) {
/******/ 					var queue = [];
/******/ 					queue.d = 0;
/******/ 					dep.then((r) => {
/******/ 						obj[webpackExports] = r;
/******/ 						resolveQueue(queue);
/******/ 					}, (e) => {
/******/ 						obj[webpackError] = e;
/******/ 						resolveQueue(queue);
/******/ 					});
/******/ 					var obj = {};
/******/ 					obj[webpackQueues] = (fn) => (fn(queue));
/******/ 					return obj;
/******/ 				}
/******/ 			}
/******/ 			var ret = {};
/******/ 			ret[webpackQueues] = x => {};
/******/ 			ret[webpackExports] = dep;
/******/ 			return ret;
/******/ 		}));
/******/ 		__webpack_require__.a = (module, body, hasAwait) => {
/******/ 			var queue;
/******/ 			hasAwait && ((queue = []).d = -1);
/******/ 			var depQueues = new Set();
/******/ 			var exports = module.exports;
/******/ 			var currentDeps;
/******/ 			var outerResolve;
/******/ 			var reject;
/******/ 			var promise = new Promise((resolve, rej) => {
/******/ 				reject = rej;
/******/ 				outerResolve = resolve;
/******/ 			});
/******/ 			promise[webpackExports] = exports;
/******/ 			promise[webpackQueues] = (fn) => (queue && fn(queue), depQueues.forEach(fn), promise["catch"](x => {}));
/******/ 			module.exports = promise;
/******/ 			body((deps) => {
/******/ 				currentDeps = wrapDeps(deps);
/******/ 				var fn;
/******/ 				var getResult = () => (currentDeps.map((d) => {
/******/ 					if(d[webpackError]) throw d[webpackError];
/******/ 					return d[webpackExports];
/******/ 				}))
/******/ 				var promise = new Promise((resolve) => {
/******/ 					fn = () => (resolve(getResult));
/******/ 					fn.r = 0;
/******/ 					var fnQueue = (q) => (q !== queue && !depQueues.has(q) && (depQueues.add(q), q && !q.d && (fn.r++, q.push(fn))));
/******/ 					currentDeps.map((dep) => (dep[webpackQueues](fnQueue)));
/******/ 				});
/******/ 				return fn.r ? promise : getResult();
/******/ 			}, (err) => ((err ? reject(promise[webpackError] = err) : outerResolve(exports)), resolveQueue(queue)));
/******/ 			queue && queue.d < 0 && (queue.d = 0);
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module used 'module' so it can't be inlined
/******/ 	__webpack_require__("./src/frontend/index.ts");
/******/ 	var __webpack_exports__ = __webpack_require__("./src/style/frontend.scss");
/******/ 	
/******/ })()
;
//# sourceMappingURL=frontend.js.map
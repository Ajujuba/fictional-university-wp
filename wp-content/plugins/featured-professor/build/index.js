/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/index.scss":
/*!************************!*\
  !*** ./src/index.scss ***!
  \************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ (function(module) {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["data"];

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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.scss */ "./src/index.scss");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__);


 //This imports the useSelect function from the WordPress state management package. This function is used to fetch data from the WordPress global state.
 //This imports the useState and useEffect functions from React. They are used to manage local state and side effects in the React component.
 //This imports the apiFetch function, which is used to make requests to the WordPress REST API.
const __ = wp.i18n.__; //create a shortcut from the global scope of WP, The Loco Translate doens't work very well if we not use this directly

wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout",
  description: "Include a short description and link to a professor of your choice",
  icon: "welcome-learn-more",
  category: "common",
  attributes: {
    profId: {
      type: "string"
    }
  },
  edit: EditComponent,
  save: function () {
    return null;
  }
});
function EditComponent(props) {
  const [thePreview, setThePreview] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(""); // Local state is managed with useState, and the initial value of thePreview is an empty string.

  //Fired when the props.attributes.profId property changes, and is used to fetch and update the teacher's HTML based on this ID
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    updateTheMeta();
    async function go() {
      const response = await _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({
        // call to the WordPress REST API using apiFetch. It is looking for the teacher's HTML based on the ID provided in props.attributes.profId.
        path: `featuredProfessor/v1/getHTML?profId=${props.attributes.profId}`,
        method: "GET"
      });
      setThePreview(response); //updates thePreview local state with the HTML returned by the API.
    }

    go();
  }, [props.attributes.profId]); //The useEffect function is used to make a request to the WordPress REST API to get the teacher's HTML based on the profId provided in the block properties.

  //is executed once, when the component is unmounted, and is used to update the metadata of the "Featured Professor" block before it is unmounted. This ensures that metadata is always up to date in WordPress. + (continue in the next line)
  //here, you return a function that will be executed when the component is disassembled. React automatically calls this function when the component is removed from the DOM. This is a fundamental part of the React components lifecycle.
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    return () => {
      updateTheMeta(); //This returns a function that is executed when the component is unmounted. In this case, the updateTheMeta function is called when the component is about to be unmounted, which updates the metadata before the component is removed.
    };
  }, []); // The absence of dependencies ([]) means that this callback function will be called only once, when the component is mounted, and again when the component is unmounted.

  //This function will update our meta, creating a new meta:
  function updateTheMeta() {
    const profsForMeta = wp.data.select("core/block-editor").getBlocks().filter(x => x.name == "ourplugin/featured-professor") // Filter only blocks of type "ourplugin/featured-professor"
    .map(x => x.attributes.profId) // Map to get the "profId" attribute of each block
    .filter((x, index, arr) => {
      return arr.indexOf(x) == index; // Remove duplicate values
    });

    console.log(profsForMeta);

    // Trigger an action to update the post metadata
    wp.data.dispatch("core/editor").editPost({
      meta: {
        featuredprofessor: profsForMeta
      }
    });
  }
  const allProfs = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useSelect)(select => {
    return select("core").getEntityRecords("postType", "professor", {
      per_page: -1
    });
  }); //I'm getting the professors' data here
  console.log(allProfs);

  //as this solution of getting the teachers takes a few milliseconds to return a response, if the user accesses it without having loaded the block it appears as 'Loading...' 
  if (allProfs == undefined) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, "Loading...");
  }

  //and when it has allProfs defined, it returns the real block to me where the user can choose a teacher and a <div> where the selected teacher's HTML is rendered using dangerouslySetInnerHTML.
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "featured-professor-wrapper"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "professor-select-container"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", {
    onChange: e => props.setAttributes({
      profId: e.target.value
    })
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    value: "Select a professor"
  }, __('Select a professor', 'featured-professor')), allProfs.map(prof => {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
      value: prof.id,
      selected: props.attributes.profId == prof.id
    }, " ", prof.title.rendered, " ");
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    dangerouslySetInnerHTML: {
      __html: thePreview
    }
  }));
}
}();
/******/ })()
;
//# sourceMappingURL=index.js.map
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

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

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
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);

 //This line will created our style css config from our scss

function ourStartFunction() {
  let locked = false;

  //WP calls this function each and every time any of the data in the block changes, the advantage is that the data is always updated because the function is constantly called
  wp.data.subscribe(function () {
    //searches within all blocks on the page for any 'correctAnswer' set to 'undefined'
    const results = wp.data.select("core/block-editor").getBlocks().filter(function (block) {
      return block.name == "ourplugin/are-you-paying-attention" && block.attributes.correctAnswer == undefined;
    });
    //If the 'CorrectAnswer' property is undefined, then I want to block the page from being saved
    if (results.length && locked == false) {
      locked = true;
      wp.data.dispatch("core/editor").lockPostSaving("noanswer");
    }
    //If the 'CorrectAnswer' property is not undefined, I want to allow the page to be saved
    if (!results.length && locked) {
      locked = false;
      wp.data.dispatch("core/editor").unlockPostSaving("noanswer");
    }
  });
}
ourStartFunction();
wp.blocks.registerBlockType('ourplugin/are-you-paying-attention',
// slug
{
  title: "Are you Payins Attention?",
  //visual title
  icon: "smiley",
  category: "common",
  //block category
  attributes: {
    question: {
      type: "string"
    },
    answers: {
      type: "array",
      default: [""]
    },
    //We defined default="" because when loading the page for the first time, we can see at least 1 answer field
    correctAnswer: {
      type: "number",
      default: undefined
    }
  },
  edit: EditComponent,
  //Control what you see in the editor screen
  save: function (props) {
    return null; //let's remove from JS the responsibility of returning something and sending it to php, in the database we won't save anything static, we'll let php handle the values in real time
  } //Controls what the public sees in the content
} //configuration object 
);

function EditComponent(props) {
  //This fn is linked to an input from the Wordpress components and not to a traditional input so we have facilities, here we don't need to receive the event and search within it, we can just receive 'value' and set it in the attribute.
  function updateQuestion(value) {
    props.setAttributes({
      question: value
    });
  }
  function deleteAnswer(indexToDelete) {
    //the filter return  a new array with true for each item in the array, except for the one we want to delete
    const newAnswers = props.attributes.answers.filter(function (x, index) {
      return index != indexToDelete;
    });
    props.setAttributes({
      answers: newAnswers
    });
    if (indexToDelete == props.attributes.correctAnswer) {
      props.setAttributes({
        correctAnswer: undefined
      }); //If I delete my correctAnswer, I want set undefined again in correctAnswer
    } else if (indexToDelete < props.attributes.correctAnswer) {
      props.setAttributes({
        correctAnswer: props.attributes.correctAnswer - 1 //when I'm deleting an answer which has a smaller index number than the correct one, the correct answer will be shifted to the subsequent item in the array because of the new order. To resolve this add this elseif 
      });
    }
  }

  function markAsCorrect(index) {
    props.setAttributes({
      correctAnswer: index
    });
  }
  return (
    //our JSX:
    (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "paying-attention-edit-block"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
      style: {
        fontSize: "20px"
      },
      label: "Question: ",
      value: props.attributes.question,
      onChange: updateQuestion
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
      style: {
        fontSize: "13px",
        margin: "20px 0px 8px 0px"
      }
    }, "Answers: "), props.attributes.answers.map(function (answer, index) {
      //map will see my array, each element
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Flex, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FlexBlock, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
        value: answer,
        onChange: newValue => {
          const newAnswers = props.attributes.answers.concat([]); //Created a copy of array
          newAnswers[index] = newValue;
          props.setAttributes({
            answers: newAnswers
          });
        },
        autoFocus: answer == undefined
      })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FlexItem, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
        onClick: () => markAsCorrect(index)
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
        className: "mark-as-correct",
        icon: props.attributes.correctAnswer == index ? "star-filled" : "star-empty"
      }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FlexItem, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
        variant: "link",
        className: "attention-delete",
        onClick: () => deleteAnswer(index)
      }, "Delete")));
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "primary",
      onClick: () => {
        props.setAttributes({
          answers: props.attributes.answers.concat([undefined])
        }); //Set undefined to make my autofocus on this field when creating my TextControl
      }
    }, " Add another answer"))
  );
}
}();
/******/ })()
;
//# sourceMappingURL=index.js.map
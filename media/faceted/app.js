'use strict';

// This code is originally https://github.com/kmaida/angular-faceted-search
// It has been modified by Exakat ltd.

// Reference source - http://jsfiddle.net/rzgWr/19/

var myApp = angular.module('myApp',[]);

//---------------------------------------- HELPERS FACTORY

myApp.factory('Helpers', function() {
	return {
		uniq: function(data, key) {
			var result = [];

			for (var i = 0; i < data.length; i++) {
				var value = data[i][key];

				if (result.indexOf(value) == -1) {
					result.push(value);
				}
			}
			return result;
		},
		contains: function(data, obj) {
			for (var i = 0; i < data.length; i++) {
				if (data[i] === obj) {
					return true;
				}
			}
			return false;
		}
	};
});

//---------------------------------------- CONTROLLER

myApp.controller('MainCtrl', function($scope, Helpers) {
	$scope.useFacets = {};

    $scope.items = DUMP_JSON;
	$scope.count = function (prop, value) {
		return function (el) {
			return el[prop] == value;
		};
	};
	
	// Sort the available facets alphabetically/numerically.
	// http://stackoverflow.com/a/18261306
	$scope.orderByValue = function(value) {
		return value;
	};
	
	/*---
		FACET MANIPULATION FUNCTIONS
			- clear facets, search
			- FacetResults constructor
	---*/

	// Reset all previously-selected facets.
	$scope.clearAllFacets = function() {
		$scope.activeFacets = [];
		$scope.useFacets = {};
	};

	// Clear search query.
	$scope.clearQuery = function() {
		$scope.query = null;
	};

	// Clear a specific facet.
	$scope.clearFacet = function(facet) {
		// Find the index of the facet so we can remove it from the active facets.
		var i = $scope.activeFacets.indexOf(facet);

		// If it exists, remove the facet from the list of active facets.
		if (i != -1) {
			$scope.activeFacets.splice(i, 1);

			// Find the corresponding facet in the filter models and turn it off.
			for (var k in $scope.useFacets) {
				if ($scope.useFacets[k]) {
					$scope.useFacets[k][facet] = false;
				}
			}
		}
	};

	// Clear any active facets when a search query is entered (or cleared).
	// Add newValue && (!!oldValue === false) to if statement to allow search query to be changed and preserve facets.
	$scope.$watch('query', function (newValue, oldValue) {
		if ((newValue !== oldValue) && $scope.activeFacets.length) {
			$scope.clearAllFacets();
		}
	});
	
	var filterAfters = [];

	// FacetResults "constructor" object.
	// http://davidwalsh.name/javascript-objects-deconstruction
	var FacetResults = {
		init: function(facetIndex, facetName) {
			this.facetIndex = facetIndex;
			this.facetName = facetName;
		},
		filterItems: function(filterAfterArray) {
			// Name the new array created after filter is run.
			var newArrayIndex = this.facetIndex;
			// Add the new array to the filterAfters array
			filterAfters[newArrayIndex] = [];

			var selected = false;

			// Iterate over previously filtered items.
			for (var n in filterAfterArray) {
				var itemObj = filterAfterArray[n],
					useFacet = $scope.useFacets[this.facetName];

				// Iterate over new facet.
				for (var facet in useFacet) {
						selected = true;

						// Push facet to list of active facets if doesn't already exist.
						if (!Helpers.contains($scope.activeFacets, useFacet[facet])) {
							$scope.activeFacets.push(useFacet[facet]);
						}

						// Push item from previous filter to new array if matches new facet and unique.
						// (Using == instead of === enables matching integers to strings)
						if (itemObj[this.facetName] == useFacet[facet] && !Helpers.contains(filterAfters[newArrayIndex], itemObj)) {
							filterAfters[newArrayIndex].push(itemObj);
							break;
						}
				}
			}

			if (!selected) {
				filterAfters[newArrayIndex] = filterAfterArray;
			}
		}
	};
	
	/*---
		SET UP FACETS
			- define facet group names
			- compile all facets that belong in each group
			- create new objects for each set of facet results
	---*/
	
	// Define the facet group names.
	// If fetching from data, this will need to be in resolve/callback.
	var facetGroupNames = ['file','error', 'severity', 'impact'];//, 'recipes'
	var facetGroupNamesLen = facetGroupNames.length;
	$scope.facetGroups = [];

	// Collect all options for each facet group from items dataset.
	// The HTML template will iterate over the facetGroups array to generate filter options.
	// (Alternately, we could pre-define the facets we want to use)
	for (var i = 0; i < facetGroupNamesLen; i++) {
	    var facets = {};
	    for (var p in $scope.items) { //, facetGroupNames[i]
	        var n = $scope.items[p][facetGroupNames[i]];
	        if (facets.hasOwnProperty(n)) {
    	        facets[n]++;
	        } else {
    	        facets[n] = 1;
    	    }
        }
		var facetGroupObj = {
				name: facetGroupNames[i],
				facets: facets,
				count: i
			};

		$scope.facetGroups.push(facetGroupObj);
	}

	// Create new object for each set of facet results (ie., like "new"ing).
	var filterBy = [];

	for (var i = 0; i < facetGroupNamesLen; i++) {
		var thisName = facetGroupNames[i];

		filterBy.push(Object.create(FacetResults));
		filterBy[i].init(i, thisName);
	}
	
	/*---
		WATCH FACET SELECTION
			- "new" each facet results set
			- run filters
			- return final list of items after last filter is run
	---*/

	$scope.activeFacets = [];
	
	$scope.$watch('useFacets', function(newVal, oldVal) {
	    $scope.activeFacets = [];
	    
		// Filter each facet set.
		for (var i = 0; i < facetGroupNamesLen; i++) {
			if (i === 0) {
				filterBy[0].filterItems($scope.items);
			} else {
				filterBy[i].filterItems(filterAfters[i - 1]);
			}
		}

		// Return the final filtered list of items.
		$scope.filteredItems = filterAfters[facetGroupNamesLen - 1];

	}, true);
});
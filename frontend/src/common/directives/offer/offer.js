angular.module('common.directives.offer', [])
.directive('offer', function () {
    return {
        scope: {
            'offer': '=offerData'
        },
        templateUrl: 'common/directives/offer/offer.tpl.html',
        controller: ['$scope', 'offerLogic', function ($scope, offerLogic) {
            $scope.saveOffer = function (offer) {
                if (offer.saved) {
                    offer.saved = false;
                    offerLogic.unsave(offer);
                }
                else {
                    offer.saved = true;
                    offerLogic.save(offer);
                }


            };

            $scope.calcECTS = function (offer) {
                return (parseInt(offer.duration) * 4 * 5 * parseInt(offer.hours)) / 25;
            };

            $scope.calcPayPerHour = function (offer) {
                return (parseFloat(offer.pay) / (parseFloat(offer.hours) * 4 * 5));
            };
        }]
    };
})
    .factory('offerLogic', ['$window', function ($window) {
        return {
            unsave: function (offer) {
                var oldSaved = $window.localStorage.getItem('saved');
                if (!oldSaved) oldSaved = "";

                var oldCodes = oldSaved.split(',');

                var found = false;

                for (var i = 0; i < oldCodes.length; i++) {
                    if (oldCodes === offer.code) {
                        oldCodes = oldCodes.splice(i, 1);
                        found = true;
                        break;
                    }
                }
                if (found) {
                    $window.localStorage.setItem('saved', oldCodes.join(','));
                }
            },

            save: function (offer) {

                console.log('saved');

                var oldSaved = $window.localStorage.getItem('saved');
                if (!oldSaved) oldSaved = "";

                var oldCodes = oldSaved.split(',');

                console.log(oldCodes);

                var found = false;

                for (var i = 0; i < oldCodes.length; i++) {
                    if (oldCodes === offer.code) {
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    console.log('not found');
                    oldCodes.push(offer.code);
                    $window.localStorage.setItem('saved', oldCodes.join(','));
                }

            }
        };
    }]);
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
                if (!oldSaved) oldSaved = '';

                var splited = oldSaved.split(',');
                if (oldSaved === '') { splited = []; }

                for (var i = 0; i < splited.length; i++) {
                    if (splited[i] == offer.code) {
                        splited.splice(i, 1);
                        break;
                    }
                }

                $window.localStorage.setItem('saved', splited.join(','));
            },

            save: function (offer) {
                var oldSaved = $window.localStorage.getItem('saved');
                if (!oldSaved) oldSaved = '';

                var splited = oldSaved.split(',');
                if (oldSaved === '') { splited = []; }

                var found = false;
                for (var i = 0; i < splited.length && !found; i++) {
                    if (splited[i] == offer.code) {
                        found = true;
                    }
                }

                if (!found) {
                    splited.push(offer.code);
                }

                $window.localStorage.setItem('saved', splited.join(','));

            }
        };
    }]);
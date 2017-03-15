angular.module('app', ['ngResource', 'common.directives.offer'])
    .controller('AppController', ['$scope', 'appLogic', '$q', function ($scope, appLogic, $q) {

        $scope.offers = appLogic.getOffers();
        $scope.filters = {};

        $scope.isLoading = function () {
            return $scope.offers.$resolved === false;
        };

        $scope.getAveragePay = function () {
            var sum = 0;
            for (var i = 0; i < $scope.offers.length; i++) {
                sum += parseFloat($scope.offers[i].pay);
            }
            return sum / $scope.offers.length;
        };

        $scope.getAveragePayPerHour = function () {
            var sum = 0;
            for (var i = 0; i < $scope.offers.length; i++) {
                sum += (parseFloat($scope.offers[i].pay) / (parseFloat($scope.offers[i].hours) * 4 * 5));
            }
            return sum / $scope.offers.length;
        };

        $scope.getFilteredOffers = function () {
            return appLogic.getFilteredOffers($scope.offers, $scope.filters);
        };

        $scope.getSavedOffers = function () {
            return appLogic.getSavedOffers($scope.offers);
        }

        console.log($scope.offers);
    }])
    .factory('appLogic', ['$resource', function ($resource) {

        var Offer = $resource('http://localhost/peix/backend/get.php');

        function unset(attr) {
            return attr === undefined || attr === null || attr === "";
        }

        return {
            getOffers: function () {

                return Offer.query();

            },

            getFilteredOffers: function (offers, filters) {
                var fOffers = [];
                for (var i = 0; i < offers.length; i++) {
                    var offer = offers[i];
                    var matching = filters.minPay === undefined || parseInt(offer.pay) >= filters.minPay;
                    matching = matching && (unset(filters.minMonths) || parseInt(offer.duration) >= filters.minMonths);
                    matching = matching && (unset(filters.maxMonths) || parseInt(offer.duration) <= filters.maxMonths);
                    matching = matching && (unset(filters.minHours) || parseInt(offer.hours) >= filters.minHours);
                    matching = matching && (unset(filters.maxHours) || parseInt(offer.hours) <= filters.maxHours);

                    if (matching) {
                        fOffers.push(offer);
                    }


                }

                return fOffers;
            },

            getSavedOffers: function (offers) {
                var sOffers = [];
                for (var i = 0; i < offers.length; i++) {
                    var offer = offers[i];
                    if (offer.saved) {
                        sOffers.push(offer);
                    }
                }
                return sOffers;
            }

        };

    }]);
angular.module('app', ['ngResource'])
    .controller('AppController', ['$scope', 'appLogic', '$q', function ($scope, appLogic, $q) {

        $scope.offers = appLogic.getOffers();
        $scope.filters = {};

        $scope.isLoading = function () {
            return $scope.offers.$resolved === false;
        };

        $scope.calcECTS = function (offer) {
            return (parseInt(offer.duration) * 4 * 5 * parseInt(offer.hours)) / 25;
        };

        $scope.calcPayPerHour = function (offer) {
            return (parseFloat(offer.pay) / (parseFloat(offer.hours) * 4 * 5));
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

        console.log($scope.offers);
    }])
    .factory('appLogic', ['$resource', function ($resource) {

        var Offer = $resource('http://localhost/peix/backend/get.php');

        return {
            getOffers: function () {

                return Offer.query();

            },

            getFilteredOffers: function (offers, filters) {
                var fOffers = [];

                for (var i = 0; i < offers.length; i++) {
                    var offer = offers[i];
                    var matching = filters.minPay === undefined || parseInt(offer.pay) >= filters.minPay;
                    matching = matching && (filters.minMonths === undefined || parseInt(offer.duration) >= filters.minMonths);
                    matching = matching && (filters.maxMonths === undefined || parseInt(offer.duration) <= filters.maxMonths);
                    matching = matching && (filters.minHours === undefined || parseInt(offer.hours) >= filters.minHours);
                    matching = matching && (filters.maxHours === undefined || parseInt(offer.hours) <= filters.maxHours);

                    if (matching) {
                        fOffers.push(offer);
                    }


                }

                return fOffers;
            }

        };

    }]);
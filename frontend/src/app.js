angular.module('app', ['ngResource'])
    .controller('AppController', ['$scope', 'appLogic', function ($scope, appLogic) {

        $scope.offers = appLogic.getOffers();

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

        console.log($scope.offers);
    }])
    .factory('appLogic', ['$resource', function ($resource) {

        var Offer = $resource('http://localhost/peix/backend/scraper.php');

        return {
            getOffers: function () {

                return Offer.query();

            },

        };

    }]);
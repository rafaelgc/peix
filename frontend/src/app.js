angular.module('app', ['ngResource'])
    .controller('AppController', ['$scope', 'appLogic', function ($scope, appLogic) {

        $scope.offers = appLogic.getOffers();

        $scope.isLoading = function () {
            return $scope.offers.$resolved === false;
        };

        console.log($scope.offers);
    }])
    .factory('appLogic', ['$resource', function ($resource) {

        var Offer = $resource('http://localhost/peix/backend/scrapper.php');

        return {
            getOffers: function () {

                return Offer.query();

            }
        };

    }]);
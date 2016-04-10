var app = angular.module('vHostExtended',['ui.bootstrap','ngProgress','angular-terminal']);

app.directive('uploadfile', function () {
    return {
        restrict: 'A',
        link: function(scope, element) {

            element.bind('click', function(e) {
                angular.element(e.target).siblings('#document_path').trigger('click');
            });
        }
    };
});
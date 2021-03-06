'use strict';

define([
        'jquery',
        'module',
        'routing'
    ], function (
        $,
        module,
        Routing
    ) {
        return {
            /**
             * Save the given datagridView for the given gridAlias.
             * Return the POST request promise.
             *
             * @param {object} datagridView
             * @param {string} gridAlias
             *
             * @returns {Promise}
             */
            save: function (datagridView, gridAlias) {
                var saveRoute = Routing.generate(module.config().url, {alias: gridAlias});

                return $.post(saveRoute, {view: datagridView});
            }
        };
    }
);

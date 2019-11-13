// Imports
var express = require('express');
var usersCtrl = require('./routes/usersCtrl');
var promotionCtrl = require('./routes/promotionCtrl');
var campusCtrl = require('./routes/campusCtrl');

// Router
exports.router = (function() {
    var apiRouter = express.Router();

    // Users routes
    apiRouter.route('/users').post(usersCtrl.register);
    apiRouter.route('/campus').post(campusCtrl.addCampus);
    apiRouter.route('/users/campus').post(campusCtrl.getCampus);
    apiRouter.route('/promotion').post(promotionCtrl.addPromotion);
    apiRouter.route('/users/login/').post(usersCtrl.login);
    apiRouter.route('/users/:param').get(usersCtrl.getUserProfile);
    apiRouter.route('/users').get(usersCtrl.getUser);
    apiRouter.route('/users/:param').put(usersCtrl.updateUser);
    apiRouter.route('/users/:param').delete(usersCtrl.deleteUser);

    return apiRouter;
})();
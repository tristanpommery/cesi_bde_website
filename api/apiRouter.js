// Imports
var express = require('express');
var usersCtrl = require('./routes/usersCtrl');
var promotionCtrl = require('./routes/promotionCtrl');
var campusCtrl = require('./routes/campusCtrl');
var users_eventCtrl = require('./routes/users_eventCtrl');
var users_galleryCtrl = require('./routes/users_galleryCtrl');
var associationsCtrl = require('./routes/associationsCtrl');
var eventsCtrl = require('./routes/eventsCtrl');
var productsCtrl = require('./routes/productsCtrl');


// Router
exports.router = (function() {
    var apiRouter = express.Router();

    // Users routes
    apiRouter.route('/users').post(usersCtrl.register);
    apiRouter.route('/campus').post(campusCtrl.addCampus);
    apiRouter.route('/users/campus').post(campusCtrl.getCampus);
    apiRouter.route('/users/promotion').post(promotionCtrl.getPromotion);
    apiRouter.route('/promotion').post(promotionCtrl.addPromotion);
    apiRouter.route('/users/login/').post(usersCtrl.login);
    apiRouter.route('/users/:param').get(usersCtrl.getUserProfile);
    apiRouter.route('/users').get(usersCtrl.getUser);
    apiRouter.route('/users/:param').put(usersCtrl.updateUser);
    apiRouter.route('/users/:param').delete(usersCtrl.deleteUser);
    apiRouter.route('/ajax').get(users_eventCtrl.getUser);
    apiRouter.route('/ajax/:param').get(users_eventCtrl.getUserProfile);
    apiRouter.route('/gallery/:param').get(users_galleryCtrl.getUserProfile);
    apiRouter.route('/gallery').get(users_galleryCtrl.getUser);
    apiRouter.route('/like').post(users_galleryCtrl.Like);
    apiRouter.route('/associations').get(associationsCtrl.getAssociation);
    apiRouter.route('/events').get(eventsCtrl.getEvent);
    apiRouter.route('/products').get(productsCtrl.getProduct);
    apiRouter.route('/unlike').post(users_galleryCtrl.deleteLike);
    apiRouter.route('/like').post(users_galleryCtrl.createLike);

    return apiRouter;
})();
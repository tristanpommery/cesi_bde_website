// Imports
var jwtUtils = require('../utils/jwt.utils');
var models = require('../models');
var asyncLib = require('async');

// Routes
module.exports = {
    addPromotion: function (req, res) {
        var name = req.body.name;
        var headerAuth = req.headers['authorization'];
        var userRole = jwtUtils.getUserId(headerAuth);

        if (userRole !== "admin")
            return res.status(400).json({ 'error': 'wrong token or not admin' });

        if (param == null) {
            return res.status(400).json({ 'error': 'invalid parameters' });
        }

        var newPromotion = models.promotion.create({
            name: name
        }).then(function (newPromotion) {
            return res.status(201).json({
                'name': newPromotion.name
            })
        })
            .catch(function (newPromotion) {
                return res.status(500).json({ 'error': 'cannot add campus' });
            })
    },
    getPromotion: function (req, res) {
        var promotionName = req.body.id;

        if (promotionName == null)
            return res.status(400).json({ 'error': 'invalid parameters' });

        models.promotion.findOne({
            attributes: ['id', 'name'],
            where: { id: promotionName }
        }).then(function (promotion) {
            if (promotion) {
                res.status(201).json(promotion);
            } else {
                res.status(404).json({ 'error': 'user not found' });
            }
        }).catch(function (err) {
            res.status(500).json({ 'error': 'cannot fetch user' });
        });
    }
}
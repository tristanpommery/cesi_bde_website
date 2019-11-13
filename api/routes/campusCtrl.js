// Imports
var jwtUtils = require('../utils/jwt.utils');
var models = require('../models');
var asyncLib = require('async');

// Routes
module.exports = {
    addCampus: function (req, res) {
        var name = req.body.name;
        var headerAuth = req.headers['authorization'];
        var userRole = jwtUtils.getUserId(headerAuth);

        if (userRole !== "admin")
            return res.status(400).json({ 'error': 'wrong token or not admin' });

        asyncLib.waterfall([
            function (done) {
                models.campus.findOne({
                    where: { name: name },
                    attributes: ['name']
                })
                    .then(function (campusFound) {
                        done(null, campusFound);
                    })
                    .catch(function (err) {
                        return res.status(500).json({ 'error': 'unable to verify user' });
                    });
            },
            function (campusFound, done) {
                if (!campusFound) {
                    var newCampus = models.campus.create({
                        name: name
                    }).then(function (newCampus) {
                        return res.status(201).json({
                            'name': newCampus.name
                        })
                    })
                        .catch(function (newCampus) {
                            return res.status(500).json({ 'error': 'cannot add campus' });
                        })
                } else {
                    return res.status(409).json({ 'error': 'campus already exist' });
                }
            }]);
    },
    getCampus: function (req, res) {
        // Getting auth header
        var campusName = req.body.id;

        if (campusName == null)
            return res.status(400).json({ 'error': 'invalid parameters' });

        models.campus.findOne({
            where: { id: campusName },
            attributes: ['id', 'name']
        }).then(function (campus) {
            if (campus) {
                res.status(201).json(campus);
            } else {
                res.status(404).json({ 'error': 'user not found' });
            }
        }).catch(function (err) {
            res.status(500).json({ 'error': 'cannot fetch user' });
        });
    }
}
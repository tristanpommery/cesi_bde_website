// Imports
var models = require('../models');
var asyncLib = require('async');

// Routes
module.exports = {

    getUserProfile: function (req, res) {

        var param = req.params.param;

        if (param == null) {
            return res.status(400).json({ 'error': 'invalid parameters' });
        }
        models.user_gallery.findAll({
            where: {
                user_id: param
            },
            include: [{
                model: models.user,
                attributes: []
            },
            {
                model: models.gallery,
                attributes: ['id', 'image']
            }
            ],
            attributes: ['user_id', 'gallery_id']
        }).then(function (user) {
            if (user) {
                res.status(201).json(user);
            } else {
                res.status(404).json(param);
            }
        }).catch(function (err) {
            res.status(500).json({ 'error': 'cannot fetch user' });
        });
    },
    getUser: function (req, res) {
        models.user_gallery.findAll({
            include: [{
                model: models.user,
                attributes: ['id', 'first_name', 'last_name', 'genre', 'email']
                },
                {
                    model: models.gallery,
                    attributes: ['id', 'image']
                },
            ],
            attributes: ['user_id', 'gallery_id']
        }).then(function (user) {
            if (user) {
                res.status(201).json(user);
            } else {
                res.status(404).json({ 'error': 'user not found' });
            }
        }).catch(function (err) {
            res.status(500).json({ 'error': 'cannot fetch user' });
        });
    }, Like: function (req, res) {

        //Parameters
        var user = req.body.user_id;
        var gallery = req.body.gallery_id;

        asyncLib.waterfall([
            function (done) {
                models.user_gallery.findOne({
                    attributes: ['user_id', 'gallery_id'],
                    where: {
                        user_id: user,
                        gallery_id: gallery
                    }
                }).then(function (userFound) {
                       done(null, userFound);
                    })
                    .catch(function (err) {
                        return res.status(500).json({ 'error': 'unable to verify user' });
                    });
            },
            function (userFound, done) {
                if (!userFound) {
                    var newLike = models.user_gallery.create({
                        user_id: user,
                        gallery_id: gallery
                    })
                    .then(function (newLike) {
                        done(newLike)
                    }).catch(function(err) {
                        return res.status(500).json({ 'error': 'aled' });
                    });
                } else {
                    models.userFound.destroy({
                        where: {
                            user_id: user_id,
                            gallery_id: gallery_id
                        }
                    }).then(function (likeDestroyed) {
                        if (likeDestroyed) {
                            res.status(200).json({ 'success': 'Like deleted' });
                        } else {
                            res.status(404).json({ 'error': 'Like not found' });
                        }
                    }).catch(function (err) {
                        res.status(500).json({ 'error': 'cannot fetch Like' });
                    });
                }
            }
        ], function (newLike) {
                if (newLike) {
                return res.status(201).json({
                    'userId': newLike.user_id,
                    'galleryId': newLike.gallery_id
                });
            } else {
                return res.status(500).json({ 'error': 'cannot like image' });
            }
        });
    }
}
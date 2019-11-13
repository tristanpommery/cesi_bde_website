// Imports
var bcrypt = require('bcrypt');
var jwtUtils = require('../utils/jwt.utils');
var models = require('../models');
var asyncLib = require('async');

// Constants
const EMAIL_REGEX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
const PASSWORD_REGEX = /^(?=.*\d).{2,18}$/;

// Routes
module.exports = {
    register: function(req, res) {

        //Parameters
        var email = req.body.email;
        var firstName = req.body.first_name;
        var lastName = req.body.last_name;
        var password = req.body.password;
        var genre = req.body.genre;
        var promotion = req.body.promotion;
        var campusName = req.body.campus;
        var roles = req.body.roles;
        var image =req.body.image;

       if (email == null || firstName == null || password == null || lastName == null || campusName == null || promotion == null || genre == null) {
            return res.status(400).json({ 'error': 'missing parameters'});
        }

        if (!EMAIL_REGEX.test(email)) {
            return res.status(400).json({ 'error': 'email is not valid' })
        }
        
        if (!PASSWORD_REGEX.test(password)) {
            return res.status(400).json({ 'error': 'password invalid (must length 2 - 18 and include 1 number at least )'})
        }
        
        asyncLib.waterfall([
            function (done) {
                models.user.findOne({
                    where: { email: email },
                    attributes: ['email']
                })
                    .then(function (userFound) {
                        done(null, userFound);
                    })
                    .catch(function (err) {
                        return res.status(500).json({ 'error': 'unable to verify user' });
                    });
            },
            function (userFound, done) {
                if (!userFound) {
                    bcrypt.hash(password, 5, function (err, bcryptedPassword, campusFound) {
                        models.campus.findOne({
                            where: { name: campusName },
                            attributes: ['id', 'name']
                        }).then(function (campusFound) {
                           done(null, userFound, campusFound, bcryptedPassword);
                        })
                            .catch(function (err) {
                                return res.status(500).json({ 'error': 'unnable to find campus' });
                            });
                    });
                    
                } else {
                    return res.status(409).json({ 'error': 'user already exist' });
                }
            },
            function (userFound, campusFound, bcryptedPassword, done) {
                models.promotion.findOne({
                    where: { name: promotion },
                    attributes: ['id', 'name']
                }).then(function (promotionFound) {
                    done(null, userFound, campusFound, bcryptedPassword, promotionFound);
                })
                .catch(function (err) {
                    return res.status(500).json({ 'error': 'unnable to find promotion' });
                });
            },
            function (userFound, campusFound, bcryptedPassword, promotionFound, done) {
                var newUser = models.user.create({
                    email: email,
                    first_name: firstName,
                    last_name: lastName,
                    password: bcryptedPassword,
                    genre: genre,
                    campusId: campusFound.id,
                    promotionId: promotionFound.id,
                    image: image,
                    roles: roles
                }).then(function (newUser) {
                        done(newUser);
                    })
                    .catch(function (err) {
                        return res.status(500).json({ 'error': 'cannot add user 1' });
                    });
            }
        ], function (newUser) {
            if (newUser) {
                return res.status(201).json({
                    'userId': newUser.id
                });
            } else {
                return res.status(500).json({ 'error': 'cannot add user' });
            }
        });
    },
    login: function(req, res) {

        // Parmas
        var email = req.body.email;
        var password = req.body.password;

        if (email == null || password == null) {
            return res.status(400).json({ 'error': 'missing parameters' });
        }

        asyncLib.waterfall([
            function(done){
                models.user.findOne({
                    where: { email: email }
                })
                .then(function(userFound){
                    done(null, userFound);
                })
                .catch(function(err){
                    return res.status(500).json({ 'error': 'unable to verify user' });
                });
            },
            function(userFound, done){
                if (userFound) {
                    bcrypt.compare(password, userFound.password, function(errBcrypt, resBcrypt) {
                        done(null, userFound, resBcrypt);
                    });
                } else {
                    return res.status(404).json({ 'error' : 'user not exist in DB' });
                }
            },
            function(userFound, resBcrypt, done) {
                if (resBcrypt) {
                    done(userFound);
                } else {
                    return res.status(403).json({ 'error': 'invalid password' });
                }
            }
        ], function(userFound) {
            if(userFound) {
                return res.status(201).json({ 
                    'userId': userFound.id,
                    'token': jwtUtils.generateTokenForUser(userFound)
             });
            } else {
                return res.status(400).json({ 'error': 'cannot login' });
            }
        });
    }, 
    getUserProfile: function (req, res) {

        var param = req.params.param;
        var headerAuth = req.headers['authorization'];
        var userRole = jwtUtils.getUserId(headerAuth);

        if (userRole !== "admin")
            return res.status(400).json({ 'error': 'wrong token or not admin' });  

        if (param == null) {
            return res.status(400).json({ 'error': 'invalid parameters' });
        }

        if(!EMAIL_REGEX.test(param)) {

            models.user.findOne({
                attributes: ['id', 'email', 'first_name', 'last_name', 'roles', 'image'],
                where: { id: param }
            }).then(function (user) {
                if (user) {
                    res.status(201).json(user);
                } else {
                    res.status(404).json({ 'error': 'user not found' });
                }
            }).catch(function (err) {
                res.status(500).json({ 'error': 'cannot fetch user' });
            });
        } else {

            models.user.findOne({
                attributes: ['id', 'email', 'first_name', 'last_name', 'roles', 'image'],
                where: { email: param }
            }).then(function (user) {
                if (user) {
                    res.status(201).json(user);
                } else {
                    res.status(404).json({ 'error': 'user not found' });
                }
            }).catch(function (err) {
                res.status(500).json({ 'error': 'cannot fetch user' });
            });
        }   
    },
    updateUser: function (req, res) {
        var headerAuth = req.headers['authorization'];
        var userRole = jwtUtils.getUserRole(headerAuth);
        var param = req.params.param;
        var roles = req.body.roles;

        if (userRole !== "admin")
            return res.status(400).json({ 'error': 'wrong token or not admin' });  

        if (param == null) {
            return res.status(400).json({ 'error': 'invalid parameters' });
        }

        if (!EMAIL_REGEX.test(param)) {
            asyncLib.waterfall([
                function (done) {
                    models.user.findOne({
                        attributes: ['id', 'roles'],
                        where: { id: param }
                    }).then(function (userFound) {
                        done(null, userFound);
                    })
                        .catch(function (err) {
                            return res.status(500).json({ 'error': 'unable to verify user' });
                        });
                },
                function (userFound, done) {
                    if (userFound) {
                        userFound.update({
                            roles: (roles ? roles : userFound.roles)
                        }).then(function () {
                            done(userFound);
                        }).catch(function (err) {
                            res.status(500).json({ 'error': 'cannot update user' });
                        });
                    } else {
                        res.status(404).json({ 'error': 'user not found' });
                    }
                },
            ], function (userFound) {
                if (userFound) {
                    return res.status(201).json(userFound);
                } else {
                    return res.status(500).json({ 'error': 'cannot update user profile' });
                }
            });
        } else {
            asyncLib.waterfall([
                function (done) {
                    models.user.findOne({
                        attributes: ['id', 'roles'],
                        where: { email: param }
                    }).then(function (userFound) {
                        done(null, userFound);
                    })
                        .catch(function (err) {
                            return res.status(500).json({ 'error': 'unable to verify user' });
                        });
                },
                function (userFound, done) {
                    if (userFound) {
                        userFound.update({
                            roles: (roles ? roles : userFound.roles)
                        }).then(function () {
                            done(userFound);
                        }).catch(function (err) {
                            res.status(500).json({ 'error': 'cannot update user' });
                        });
                    } else {
                        res.status(404).json({ 'error': 'user not found' });
                    }
                },
            ], function (userFound) {
                if (userFound) {
                    return res.status(201).json(userFound);
                } else {
                    return res.status(500).json({ 'error': 'cannot update user profile' });
                }
            });
        }  
    },
    deleteUser: function (req, res) {

        var param = req.params.param;
        var headerAuth = req.headers['authorization'];
        var userRole = jwtUtils.getUserRole(headerAuth);

        if (userRole !== "admin")
            return res.status(400).json({ 'error': 'wrong token or not admin' });  

        if (param == null) {
            return res.status(400).json({ 'error': 'invalid parameters' });
        }

        if (!EMAIL_REGEX.test(param)) {

            models.user.destroy({
                where: {
                    id: param
                }
            }).then(function (user) {
                if (user) {
                    res.status(200).json({ 'success': 'user deleted' });
                } else {
                    res.status(404).json({ 'error': 'user not found' });
                }
            }).catch(function (err) {
                res.status(500).json({ 'error': 'cannot fetch user' });
            });
        } else {

            models.user.destroy({
                where: { email: param }
            }).then(function (user) {
                if (user) {
                    res.status(200).json({ 'success': 'user deleted'});
                } else {
                    res.status(404).json({ 'error': 'user not found' });
                }
            }).catch(function (err) {
                res.status(500).json({ 'error': 'cannot fetch user' });
            });
        }
    },
    getUser: function (req, res) {
        // Getting auth header
        var headerAuth = req.headers['authorization'];
        var userRole = jwtUtils.getUserRole(headerAuth);

        if (userRole !== "admin")
            return res.status(400).json({ 'error': 'wrong token or not admin' });

        models.user.findAll({
            attributes: ['id', 'email', 'first_name', 'last_name', 'image', 'roles']
        }).then(function (user) {
            if (user) {
                res.status(201).json(user);
            } else {
                res.status(404).json({ 'error': 'user not found' });
            }
        }).catch(function (err) {
            res.status(500).json({ 'error': 'cannot fetch user' });
        });
    }
}
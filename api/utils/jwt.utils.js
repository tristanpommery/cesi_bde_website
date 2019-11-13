// Imports
var jwt = require('jsonwebtoken');

const JWT_SIGN_SECRET = '0356djcoaptbdos591sgvdolapxbns5c2dxqjbwlkqdsfksdf465f168i45zokhsbodd881d29';

// Exported function
module.exports = {
    generateTokenForUser: function(userData){
        return jwt.sign({
            roles: userData.roles,
            userId: userData.id
        },
        JWT_SIGN_SECRET,
        {
            expiresIn: '1h'
        })
    },
    parseAuthorization: function(authorization) {
        return (authorization != null) ? authorization.replace('Bearer ', '') : null;
    },
    getUserId: function(authorization) {
        var userId = -1;
        var token = module.exports.parseAuthorization(authorization);
        if(token != null) {
            try {
                var jwtToken = jwt.verify(token, JWT_SIGN_SECRET);
                if(jwt != null)
                userId = jwtToken.userId;
            } catch(err) { }
        }
        return userId;
    },
    getUserRole: function (authorization) {
        var userRole = "wrong";
        var token = module.exports.parseAuthorization(authorization);
        if (token != null) {
            try {
                var jwtToken = jwt.verify(token, JWT_SIGN_SECRET);
                if (jwt != null)
                    userRole = jwtToken.roles;
            } catch (err) { }
        }
        return userRole;
    }
}
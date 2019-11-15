'use strict';
module.exports = (sequelize, DataTypes) => {
    const user_gallery = sequelize.define('user_gallery', {
        user_id: {
            type: DataTypes.INTEGER,
            primaryKey: true
        },
        gallery_id: {
            type: DataTypes.INTEGER,
            primaryKey: true
        }
    }, {
        timestamps: false,
        underscored: true,
        freezeTableName: true,
        tableName: 'user_gallery'
    });
    user_gallery.associate = function (models) {
        // associations can be defined here
    };
    return user_gallery;
};